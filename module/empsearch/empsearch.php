<?php

// <PROGRAM_NAME>
// Copyright (C) 2010 Magnus Söderling (magnus.soderling@gmail.com)
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.

require_once INCLUDE_DIR . 'lib_ldap.php';

class EmpSearch extends ContentModule {

    protected $settings = null;
    protected $ldap = null;
    protected $searchString = '';
    protected $nExactSEntries = 0;
    protected $nNonExactSEntries = 0;
    protected $searchResult = null;
    protected $numToShow = null;
    protected $form = '';
    protected $nonEmptySearchStr = false;
    protected $head = '';
    protected $page = '';
    protected $exactMatch = false;
    protected $alwdChars = 'a-zà-öù-ÿ\s\-';

    public function __construct($settings) {
        parent::__construct();
        $this->name = "<name>$settings[name]</name>";
        $this->icon = "<icon>$settings[icon]</icon>";
        $this->head = "<head>$settings[head]</head>";
        $this->settings = $settings;

        $this->ldap = new LDAP($settings['hosturl'],
                        $settings['hostport'], $settings['basedn'],
                        $settings['ldapattribs'],
                        $settings['maxetoget']);
//FIXME only for testing with many replies?
        $this->numToShow = (int) $settings['numtoshow'];
        if (isset($_REQUEST['numtoshow'])) {
            $this->numToShow = strip_tags($_REQUEST['numtoshow']);
        }

        if (isset($_REQUEST['page'])) {
            $this->page = '<page><value>' . strip_tags($_REQUEST['page'])
                    . '</value></page>';
        }
        // Used by javascript JS knows instance name for separation
        if (isset($_REQUEST['empsearchstring'])) {
            $this->searchString = strip_tags($_REQUEST['empsearchstring']);
            $this->searchString = trim($this->searchString);
            $this->searchString = preg_replace('/\s+/', ' '
                            , $this->searchString);
            mb_internal_encoding("UTF-8");
            mb_regex_encoding("UTF-8");
            $this->searchString = mb_strtolower($this->searchString);
            $this->searchString = mb_ereg_replace('[^' . $this->alwdChars . ']'
                            , '', $this->searchString);
        }
        //Uses instance name('name') for instance separation when not using js.
        if (isset($_REQUEST[$settings['name']])) {
            $this->searchString = strip_tags($_REQUEST[$settings['name']]);
            $this->searchString = trim($this->searchString);
            $this->searchString = preg_replace('/\s+/', ' '
                            , $this->searchString);
            mb_internal_encoding("UTF-8");
            mb_regex_encoding("UTF-8");
            $this->searchString = mb_strtolower($this->searchString);
            $this->searchString = mb_ereg_replace('[^' . $this->alwdChars . ']'
                            , '', $this->searchString);
        }

        $this->nonEmptySearchStr = ($this->searchString != '');
//Default form.
        if ($this->nonEmptySearchStr)
            $formValue = '<value>' . $this->searchString . '</value>';
        else
            $formValue = '';

        $this->form = <<< FORM
<form>
  <name>$settings[name]</name>
  <action></action>
  <method>get</method>
  $formValue
  $this->page
  <empbutton>
    <type>submit</type>
    <value>Sök</value>
  </empbutton>
</form>
FORM;
        $this->ajaxForm = <<< FORM
<ajaxform>
  <name>$settings[name]</name>
  <action></action>
  <method>get</method>
  $formValue
  $this->page
  <ajaxempbutton>
    <type>submit</type>
    <value>Sök</value>
  </ajaxempbutton>
</ajaxform>
FORM;
    }

    protected function genExactFilter($searchStrings) {
        $numStr = count($searchStrings);

        switch ($numStr) {
            case 0:
                return '';
                break;
            case 1:
                return '(|(givenname=' . $searchStrings[0] . ')(sn='
                . $searchStrings[0] . '))';
                break;
            case 2:
                return '(|(&(givenname=' . $searchStrings[0] . ')'
                . '(sn=' . $searchStrings[1] . '))' . '(&(givenname='
                . $searchStrings[1] . ')' . '(sn=' . $searchStrings[0] . ')))';
                break;
            //Best way I could think of to handle >2 strings in exact
            default:
                $tmpA = '(&';
                for ($i = 0; $i < $numStr; $i++) {
                    $tmpA .= '(cn=*' . $searchStrings[$i] . '*)';
                }
                $tmpA .= ')';
                return $tmpA;
                break;
        }
    }

    protected function genStarFilter($searchStrings) {
        $numStr = count($searchStrings);

        switch ($numStr) {
            case 0:
                return '';
                break;

            case 1:
                return '(|(givenname=*' . $searchStrings[0] . '*)(sn=*'
                . $searchStrings[0] . '*))';
                break;

            case 2:
                return '(&(cn=*' . $searchStrings[0] . '*)'
                . '(cn=*' . $searchStrings[1] . '*))';
                break;

            default:
                $tmpA = '(&';
                for ($i = 0; $i < $numStr; $i++) {
                    $tmpA .= '(cn=*' . $searchStrings[$i] . '*)';
                }
                $tmpA .= ')';
                return $tmpA;
                break;
        }
    }

    protected function genSndsLikeFilter($searchStrings) {
        $numStr = count($searchStrings);

        switch ($numStr) {
            case 0:
                return '';
                break;
            case 1:
                return '(&(|(givenname~=' . $searchStrings[0] . ')(sn~='
                . $searchStrings[0] . '))' .
                '(!(|(givenname=' . $searchStrings[0] . ')(sn='
                . $searchStrings[0] . '))))';
                break;
            case 2:

                return '(&(|(&(givenname~=' . $searchStrings[0] . ')'
                . '(sn~=' . $searchStrings[1] . '))'
                . '(&(givenname~=' . $searchStrings[1] . ')'
                . '(sn~=' . $searchStrings[0] . ')))'
                . '(!(|(&(givenname=' . $searchStrings[0] . ')'
                . '(sn=' . $searchStrings[1] . '))' . '(&(givenname='
                . $searchStrings[1] . ')' . '(sn=' . $searchStrings[0]
                . ')))))';
                break;
            default:
                $tmpA = '(&';
                for ($i = 0; $i < $numStr; $i++) {
                    $tmpA .= '(|(sn~=' . $searchStrings[$i] . ')'
                            . '(givenname~=' . $searchStrings[$i] . '))';
                }
                $tmpA .= ')';

                $tmpB = '(&';
                for ($i = 0; $i < $numStr; $i++) {
                    $tmpB .= '(cn=*' . $searchStrings[$i] . '*)';
                }
                $tmpB .= ')';
                return '(&' . $tmpA . '(!' . $tmpB . '))';
                break;
        }
    }

    protected function search() {
        $searchStrings = explode(' ', $this->searchString);
        $this->nExactSEntries = 0;
        $this->nNonExactSEntries = 0;

        $filter = $this->genExactFilter($searchStrings);
        $this->nExactSEntries = $this->ldap->doSearch($filter);

        if ($this->nExactSEntries == 0) {
            $filter = $this->genStarFilter($searchStrings);
            $this->nExactSEntries = $this->ldap->doSearch($filter);
        }
        if ($this->nExactSEntries != 0) {
            $result['exact'] = $this->ldap->getSearchEntries();
        }

        $filter = $this->genSndsLikeFilter($searchStrings);
        $this->nNonExactSEntries = $this->ldap->doSearch($filter);
        if ($this->nNonExactSEntries != 0) {
            $result['soundslike'] = $this->ldap->getSearchEntries();
        }
        return $result;
    }

    private function getEmpXML($employee, $attribute, $tag) {
        $numAttribs = $employee[$attribute]['count']; //can return null
        $replaceStr = (func_num_args() > 4);
        $tmpStr = '';

        if ($numAttribs > 0) {
            $tmpArray = $employee[$attribute];
            if ($replaceStr) {
                $search = func_get_arg(3);
                $replace = func_get_arg(4);
                for ($i = 0; $i < $numAttribs; $i++) {
                    $tmpA = str_replace($search, $replace, $tmpArray[$i]);
                    $tmpA = htmlspecialchars($tmpA);
                    $tmpStr .= '<' . $tag . '>' . $tmpA;
                    $tmpStr .= '</' . $tag . '>' . "\n";
                }
            } else {
                for ($i = 0; $i < $numAttribs; $i++) {
                    $tmpA = htmlspecialchars($tmpArray[$i]);
                    $tmpStr .= '<' . $tag . '>' . $tmpA;
                    $tmpStr .= '</' . $tag . '>' . "\n";
                }
            }
        }
        return $tmpStr;
    }

    protected function buildEmployeesXML() {
        if ($this->nExactSEntries > 0) {
            $tmpSt = '<message>' . 'Din sökning gav '
                    . $this->nExactSEntries
                    . ' resulat' . '</message>' . "\n";
        }

        if (($this->nExactSEntries > 0) &&
                ($this->nExactSEntries <= $this->settings['maxetoget'])) {
            $tmpSt .= '<employeelist>' . "\n";
            $searchRst = $this->searchResult['exact'];
            //determine how many employes to return
            if ($this->nExactSEntries <= $this->numToShow)
                $numEmps = $this->nExactSEntries;
            else {
                $numEmps = $this->numToShow;
                $tmpSt .= '<message>Visar de ' . $numEmps . ' första resultaten';
                $tmpSt .= '</message>';
            }
            for ($i = 0; $i < $numEmps; $i++) {
                $employee = $searchRst[$i];
                $tmpSt .= '<employee>' . "\n";
                $tmpSt .= $this->getEmpXML($employee, 'givenname', 'givenname');
                $tmpSt .= $this->getEmpXML($employee, 'sn', 'surname');
                $tmpSt .= "<titleatdep>" . htmlspecialchars($employee['title;lang-sv'][0] . ' vid ' . $employee["department;lang-sv"][0]) . '</titleatdep>' . "\n";
                $tmpSt .= $this->getEmpXML($employee, 'registeredaddress;lang-sv', 'visitingaddress', '$', ' ');
                $tmpSt .= $this->getEmpXML($employee, 'roomnumber', 'roomnumber');
                $tmpSt .= $this->getEmpXML($employee, 'mail', 'mail');
                $tmpSt .= $this->getEmpXML($employee, 'telephonenumber', 'phonenumber', ' ', '');
                $tmpSt .= $this->getEmpXML($employee, 'mobile', 'mobilenumber', ' ', '');
                $tmpSt .= $this->getEmpXML($employee, 'facsimiletelephonenumber', 'faxnumber', ' ', '');
                $tmpSt .= '</employee>' . "\n";
            }
            $tmpSt .= '</employeelist>';
        } else {
            if ($this->nExactSEntries >= $this->settings['maxetoget'])
                $tmpSt .= '<message>Försök vara mer specifik</message>';
            
            $tmpSt .= '<employeelist></employeelist>' . "\n";
        }

        if ($this->nNonExactSEntries > 0 &&
                ($this->nExactSEntries <= $this->settings['maxetoget'])) {
            $tmpSt .= '<nonexactmessage>Liknande namn</nonexactmessage>';
            if ($this->nNonExactSEntries <= $this->numToShow)
                $numEmps = $this->nNonExactSEntries;
            else {
                $numEmps = $this->numToShow;
                //$tmpSt .= '<nonexactmessage>Visar de ' . $numEmps . ' första liknande resultaten';
                //$tmpSt .= '</nonexactmessage>';
            }
            $tmpSt .= '<employeelist>' . "\n";
            $searchRst = $this->searchResult['soundslike'];
            for ($i = 0; $i < $numEmps; $i++) {
                $employee = $searchRst[$i];
                $tmpSt .= '<employee>' . "\n";
                $tmpSt .= $this->getEmpXML($employee, 'givenname', 'givenname');
                $tmpSt .= $this->getEmpXML($employee, 'sn', 'surname');
                $tmpSt .= "<titleatdep>" . htmlspecialchars($employee['title;lang-sv'][0] . ' vid ' . $employee["department;lang-sv"][0]) . '</titleatdep>' . "\n";
                $tmpSt .= $this->getEmpXML($employee, 'registeredaddress;lang-sv', 'visitingaddress', '$', ' ');
                $tmpSt .= $this->getEmpXML($employee, 'roomnumber', 'roomnumber');
                $tmpSt .= $this->getEmpXML($employee, 'mail', 'mail');
                $tmpSt .= $this->getEmpXML($employee, 'telephonenumber', 'phonenumber', ' ', '');
                $tmpSt .= $this->getEmpXML($employee, 'mobile', 'mobilenumber', ' ', '');
                $tmpSt .= $this->getEmpXML($employee, 'facsimiletelephonenumber', 'faxnumber', ' ', '');
                $tmpSt .= '</employee>' . "\n";
            }
            $tmpSt .= '</employeelist>';
        } else {
            if ($this->nExactSEntries == 0) {
                $tmpSt .= '<nonexactmessage>Din sökning gav inga resultat';
                $tmpSt .= '</nonexactmessage>';
            }
        }
        return $tmpSt;
    }

    protected function generateDefault() {
        if ($this->nonEmptySearchStr) {
            $this->searchResult = $this->search();
            $this->ldap->disconnect();
            $this->contentXML = '<section><empsearch>' . $this->name
                    . $this->head . $this->icon . $this->form . "\n"
                    . $this->buildEmployeesXML()
                    . "\n" . '</empsearch></section>';
        } else
            $this->contentXML = '<section><empsearch>' . $this->name
                    . $this->head . $this->icon . "\n" . $this->form . "\n"
                    . '</empsearch></section>';
    }

    protected function generateToggler() {
        $this->contentXML = <<< XML
<toggler>
  <empsearch>
    $this->name
    $this->icon
    $this->head
  </empsearch>
</toggler>
XML;
    }

    protected function generateAjax() {
        if ($this->nonEmptySearchStr) {
            $this->searchResult = $this->search();
            $this->ldap->disconnect();
            $this->contentXML = '<ajax><empsearch>' . $this->name
                    . $this->head . $this->icon . $this->ajaxForm . "\n"
                    . $this->buildEmployeesXML()
                    . "\n" . '</empsearch></ajax>';
        } else {
            $this->contentXML = '<ajax><empsearch>' . $this->name . "\n"
                    . $this->head . $this->icon . $this->ajaxForm . "\n"
                    . '</empsearch></ajax>';
        }
    }

}

?>
