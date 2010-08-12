<!DOCTYPE html> 
<html> 
    <head> 
        <meta charset="utf-8" />
        <title>Sök personal</title> 
    </head> 
    <body> 
        <?php
        $tmpSettings = array('hosturl' => 'ldap.user.uu.se',
            'hostport' => '389',
            'basedn' => 'cn=People,dc=uu,dc=se',
            'ldapattribs' => 'givenname sn mail telephonenumber mobile facsimiletelephonenumber registeredaddress;lang-sv department;lang-sv title;lang-sv roomnumber',
            'numtoshow' => '50', 'maxetoget' => '500');
        require_once 'empsearch.php';
        $empsearch = new EmpSearch($tmpSettings);

        $empsearch->setMode('toggler');

        $kul = $empsearch->getXML();
//        echo '<b>XML</b>'."\n" . $kul . "\n" . '<b>/XML</b>';
        $tmpDOM = new DOMDocument();
        $tmpDOM->loadXML($kul);
        $xsl = new DOMDocument();
        $xsl->load("empsearch.xsl");
        $proc = new XSLTProcessor();
        $proc->importStyleSheet($xsl);
        ?>
        <hr/>
        <?php
        echo $proc->transformToXML($tmpDOM);
        ?>
    </body>
</html>