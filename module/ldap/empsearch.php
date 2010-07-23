<?php

require_once '../../include/contentmodule.php';

//FIXME: Just for testing

class EmpSearch extends ContentModule {

    protected $ldap = null;
    protected $searchResult = null;
    protected $form;

    public function __construct($settings) {
        parent::__construct();
        //Default form.
        //FIXME: action?
        $this->form = <<< FORM
<form>
  <name>empSearch</name>
  <action>index.php</action>
  <method>get</method>
  <button>
    <type>submit</type>
    <value>S&ouml;k</value>
  </button>
</form>
FORM;
        $this->settings = $settings;
    }

    public function getXML() {
        $this->contentXML = $this->form;
        return $this->contentXML;
    }

}
?>
