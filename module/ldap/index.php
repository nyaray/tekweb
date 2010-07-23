<?php
echo 'HELLO LDAPTEST' . '<br \>';
$hostUrl = 'ldap.user.uu.se';
$hostPort = 389;
$baseDN = 'cn=People,dc=uu,dc=se';
$ldapAttributes = array('givenname', 'sn', 'mail', 'cn',
    'telephonenumber', 'mobile', 'facsimiletelephonenumber',
    'registeredaddress;lang-sv', 'department;lang-sv', 'title;lang-sv',
    'roomnumber');
$maxEntriesToGet = 500; //Maximum number of entries to get from LDAP-server
//$requestString = strip_tags($_REQUEST['search']);

require_once 'empsearch.php';


$empsearch = new EmpSearch(array('banan' => 45));


$kul = $empsearch->getXML();

echo 'XML:' . "\n" . $kul;
// put your code here
?>