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
        $filter = '(givenname=' . $this->searchString . ')'; //FIXME gen filter
        $this->numSearchEntries = $this->ldap->doSearch($filter);
        /*
          if ($this->numSearchEntries > 0) {
          return $this->ldap->getSearchEntries();
          } else
          return false; */
        return $this->ldap->getSearchEntries();
    }

    protected function buildEmployeesXML() {


        if ($this->numSearchEntries > 0) {
            $buildString = '<message>' . 'Din sökning gav '
                    . htmlspecialchars($this->numSearchEntries)
                    . ' resulat' . '</message>' . "\n";
//determine how many employes to return
            if ($this->numSearchEntries <= $this->numToShow)
                $numEmps = $this->numSearchEntries;
            else {
                if ($this->searchResult) {
                    $buildString .= '<message>visar de ' . $this->numToShow
                            . ' första resulaten</message>' . "\n";

                    $numEmps = $this->numToShow;
                } else
                    $buildString .= '<message>Gör din sökning mer specifik'
                            . '</message>' . "\n";
            }


           
            if ($this->searchResult) {
                $buildString .= '<employees>' . "\n";

                for ($i = 0; $i < $numEmps; $i++) {
                    $employee = $this->searchResult[$i];
                    $buildString .= '<employee>' . "\n";
                    $buildString .= "<commonname>" . htmlspecialchars($employee["cn"][0]) . "</commonname>\n";
                    $buildString .= "<titleatdep>" . htmlspecialchars($employee['title;lang-sv'][0]) . ' vid ' . $employee["department;lang-sv"][0] . '</titleatdep>' . "\n";
                    $buildString .= '</employee>' . "\n";
                }
                $buildString .= '</employees>';
            }
        } else
        //FIXME ADD ~ search
            $buildString = '<message>' . htmlspecialchars('Din sökning gav inga träffar') . '</message>';


        return $buildString;
    }

    protected function generateDefault() {
//$this->contentXML = $this->form;
        if ($this->searchString != '') {
            $this->searchResult = $this->search();

            $this->contentXML = '<?xml version="1.0" encoding="utf-8"?>'
                    . "\n" . '<section>' . "\n" . $this->form . "\n"
                    . $this->buildEmployeesXML() . "\n" . '</section>';
        } else
            $this->contentXML = '<?xml version="1.0" encoding="utf-8"?>' . "\n"
                    . '<section>' . "\n" . $this->form . "\n" . '</section>';
    }

}
?>
