<?php

//FIXME: Just for testing reqs
require_once '../../include/contentmodule.php';
require_once 'ldap_lib.php';

class EmpSearch extends ContentModule {

    protected $settings = null;
    protected $ldap = null;
    protected $searchString = '';
    protected $numSearchEntries = 0;
    protected $searchResult = null;
    protected $numToShow = null;
    protected $form = '';

    public function __construct($settings) {
        parent::__construct();
//FIXME local $tmpSettings should be removed
        $tmpSettings = array('hosturl' => 'ldap.user.uu.se',
            'hostport' => '389',
            'basedn' => 'cn=People,dc=uu,dc=se',
            'numentriestoshow' => '20',
            'ldapattribs' => 'givenname sn mail cn telephonenumber mobile facsimiletelephonenumber registeredaddress;lang-sv department;lang-sv title;lang-sv roomnumber',
            'numtoshow' => '20', 'maxetoget' => '500');
//FIXME $tmpSettings
        $this->settings = $tmpSettings;

        $this->ldap = new LDAP($this->settings['hosturl'],
                        $this->settings['hostport'], $this->settings['basedn'],
                        $this->settings['ldapattribs'], $this->settings['maxetoget']);

        $this->numToShow = (int) $this->settings['numtoshow'];
        if (isset($_REQUEST['numtoshow'])) {
            $this->numToShow = strip_tags($_REQUEST['numtoshow']);
        }

        if (isset($_REQUEST['search'])) {
            $this->searchString = strip_tags($_REQUEST['search']);
            $this->searchString = trim($this->searchString);
            $this->searchString = preg_replace('/\s+/', ' ', $this->searchString);
        }
//Default form.
//FIXME: action? and function!
        $this->form = <<< FORM
<form>
  <name>empSearch</name>
  <action>index.php</action>
  <method>get</method>
  <button>
    <type>submit</type>
    <value>Sök</value>
  </button>
</form>
FORM;
    }

    protected function search() {
        $this->numSearchEntries = 0;
        $filter = '(|(givenname=' . $this->searchString . ')(sn=' . $this->searchString . '))'; //FIXME gen filter
        $this->numSearchEntries = $this->ldap->doSearch($filter);

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
                    $tmpStr .= '<' . $tag . '>' . $tmpA . '</' . $tag . '>' . "\n";
                }
            } else {
                for ($i = 0; $i < $numAttribs; $i++) {
                    $tmpA = htmlspecialchars($tmpArray[$i]);
                    $tmpStr .= '<' . $tag . '>' . $tmpA . '</' . $tag . '>' . "\n";
                }
            }
        }
        return $tmpStr;
    }

    protected function buildEmployeesXML() {

        if ($this->numSearchEntries > 0) {
            $tmpStr = '<message>' . 'Din sökning gav '
                    . htmlspecialchars($this->numSearchEntries)
                    . ' resulat' . '</message>' . "\n";
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
                    $tmpStr .= "<commonname>" . htmlspecialchars($employee["cn"][0]) . "</commonname>\n";
                    $tmpStr .= "<titleatdep>" . htmlspecialchars($employee['title;lang-sv'][0] . ' vid ' . $employee["department;lang-sv"][0]) . '</titleatdep>' . "\n";
                    $tmpStr .= $this->getEmployeeXML($employee, 'registeredaddress;lang-sv', 'visitingaddress', '$', ' ');
                    $tmpStr .= $this->getEmployeeXML($employee, 'roomnumber', 'roomnumber');
                    $tmpStr .= $this->getEmployeeXML($employee, 'mail', 'mail');
                    $tmpStr .= $this->getEmployeeXML($employee, 'telephonenumber', 'phonenumber');
                    $tmpStr .= $this->getEmployeeXML($employee, 'mobile', 'mobilenumber');
                    $tmpStr .= $this->getEmployeeXML($employee, 'facsimiletelephonenumber', 'faxnumber');
                    $tmpStr .= '</employee>' . "\n";
                }
                $tmpStr .= '</employeelist>';
            }
        } else {
            //FIXME ADD ~ search 
            $tmpStr = '<message>' . htmlspecialchars('Din sökning gav inga träffar') . '</message>' . "\n";
        }
        return $tmpStr;
    }

    protected function generateDefault() {
//$this->contentXML = $this->form;
        if ($this->searchString != '') {
            $this->searchResult = $this->search();
            $this->ldap->disconnect();
            $this->contentXML = '<?xml version="1.0" encoding="utf-8"?>'
                    . "\n" . '<section>' . "\n" . '<empsearch>' . "\n"
                    . $this->form . "\n" . $this->buildEmployeesXML()
                    . '</empsearch>' . "\n" . '</section>';
        } else
            $this->contentXML = '<?xml version="1.0" encoding="utf-8"?>' . "\n"
                    . '<section>' . "\n" . $this->form . "\n" . '</section>';
    }

}
?>
