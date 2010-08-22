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
    protected $numSearchEntries = 0;
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
  <button>
    <type>submit</type>
    <value>Sök</value>
  </button>
</form>
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

    protected function genNonExactFilter($searchStrings) {
        $numStr = count($searchStrings);

        switch ($numStr) {
            case 0:
                return '';
                break;

            case 1:
                return '(|(givenname~=' . $searchStrings[0] . ')(sn~='
                . $searchStrings[0] . '))';
                break;

            case 2:
                return '(|(&(givenname~=' . $searchStrings[0] . ')'
                . '(sn~=' . $searchStrings[1] . '))' . '(&(givenname~='
                . $searchStrings[1] . ')' . '(sn~=' . $searchStrings[0] . ')))';

            default:
                $tmpA = '(&';
                for ($i = 0; $i < $numStr; $i++) {
                    $tmpA .= '(|(sn~=' . $searchStrings[$i] . ')'
                            . '(givenname~=' . $searchStrings[$i] . '))';
                }
                $tmpA .= ')';
                return $tmpA;
                break;
        }
    }

    protected function search() {
        $searchStrings = explode(' ', $this->searchString);
        $this->numSearchEntries = 0;
        $filter = $this->genExactFilter($searchStrings);
        $this->numSearchEntries = $this->ldap->doSearch($filter);

        if ($this->numSearchEntries != 0) {
            $this->exactMatch = true;
        } else {
            $filter = $this->genStarFilter($searchStrings);
            $this->numSearchEntries = $this->ldap->doSearch($filter);
        }

        if ($this->numSearchEntries != 0) {
            $this->exactMatch = true;
        } else {
            $this->exactMatch = false;
            $filter = $this->genNonExactFilter($searchStrings);
            $this->numSearchEntries = $this->ldap->doSearch($filter);
        }
        return $this->ldap->getSearchEntries();
    }

    private function getEmployeeXML($employee, $attribute, $tag) {
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
                    //$tmpStr .= '<' . $tag . '>' . $tmpA . '</' . $tag . '>' . "\n";
                    $tmpStr .= '<' . $tag . '>' . $tmpA;
                    $tmpStr .= '</' . $tag . '>' . "\n";
                }
            }
        }
        return $tmpStr;
    }

    protected function buildEmployeesXML() {

        if ($this->numSearchEntries > 0) {
            if ($this->exactMatch) {
                $tmpStr = '<message>' . 'Din sökning gav '
                        . htmlspecialchars($this->numSearchEntries)
                        . ' resulat' . '</message>' . "\n";
            } else {
                $tmpStr = '<message>' . 'Din sökning gav inga exakta resultat'
                        . ' men ' . htmlspecialchars($this->numSearchEntries);

                if ($this->numSearchEntries != 1)
                    $tmpStr .= ' gissningar' . '</message>' . "\n";
                else
                    $tmpStr .= ' gissning' . '</message>' . "\n";
            }
            
//determine how many employes to return
            if ($this->numSearchEntries <= $this->numToShow)
                $numEmps = $this->numSearchEntries;
            else {
                if ($this->searchResult) {
                    $tmpStr .= '<message>visar de ' . $this->numToShow
                            . ' första resulaten</message>' . "\n";

                    $numEmps = $this->numToShow;
                } else
                    $tmpStr .= '<message>Gör din sökning mer specifik'
                            . '</message>' . "\n";
            }

            if ($this->searchResult) {
                $tmpStr .= '<employeelist>' . "\n";

                for ($i = 0; $i < $numEmps; $i++) {
                    $employee = $this->searchResult[$i];
                    $tmpStr .= '<employee>' . "\n";
                    $tmpStr .= $this->getEmployeeXML($employee, 'givenname', 'givenname');
                    $tmpStr .= $this->getEmployeeXML($employee, 'sn', 'surname');
                    $tmpStr .= "<titleatdep>" . htmlspecialchars($employee['title;lang-sv'][0] . ' vid ' . $employee["department;lang-sv"][0]) . '</titleatdep>' . "\n";
                    $tmpStr .= $this->getEmployeeXML($employee, 'registeredaddress;lang-sv', 'visitingaddress', '$', ' ');
                    $tmpStr .= $this->getEmployeeXML($employee, 'roomnumber', 'roomnumber');
                    $tmpStr .= $this->getEmployeeXML($employee, 'mail', 'mail');
                    $tmpStr .= $this->getEmployeeXML($employee, 'telephonenumber', 'phonenumber', ' ', '');
                    $tmpStr .= $this->getEmployeeXML($employee, 'mobile', 'mobilenumber', ' ', '');
                    $tmpStr .= $this->getEmployeeXML($employee, 'facsimiletelephonenumber', 'faxnumber', ' ', '');
                    $tmpStr .= '</employee>' . "\n";
                }
                $tmpStr .= '</employeelist>';
            }
        } else {
            $tmpStr = '<message>';
            $tmpStr .= htmlspecialchars('Din sökning gav inga resultat');
            $tmpStr .= '</message>' . "\n";
        }
        return $tmpStr;
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
        if ($this->nonEmptySearchStr) {
            $this->searchResult = $this->search();
            $this->ldap->disconnect();
            $this->contentXML = '<toggler><empsearch>' . $this->name
                    . $this->head . $this->icon . $this->form . "\n"
                    . $this->buildEmployeesXML()
                    . "\n" . '</empsearch></toggler>';
        } else
            $this->contentXML = '<toggler><empsearch>' . $this->name . "\n"
                    . $this->head . $this->icon /* . $this->form */ . "\n"
                    . '</empsearch></toggler>';
    }

    protected function generateAjax() {
        if ($this->nonEmptySearchStr) {
            $this->searchResult = $this->search();
            $this->ldap->disconnect();
            $this->contentXML = '<ajax><empsearch>' . $this->name
                    . $this->head . $this->icon . $this->form . "\n"
                    . $this->buildEmployeesXML()
                    . "\n" . '</empsearch></ajax>';
        } else {
            $this->contentXML = '<ajax><empsearch>' . $this->name . "\n"
                    . $this->head . $this->icon . $this->form . "\n"
                    . '</empsearch></ajax>';
        }
    }

}

?>
