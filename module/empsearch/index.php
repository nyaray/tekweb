<!DOCTYPE html> 
<html> 
    <head> 
        <meta charset="utf-8" />
        <title>Sök personal</title> 
    </head> 
    <body> 
        <?php
        require_once 'empsearch.php';
        $empsearch = new EmpSearch(array('banan' => 45));
        $kul = $empsearch->getXML();
        //echo 'hurp' . $kul . 'hurp';
        $tmpDOM = new DOMDocument();
        $tmpDOM->loadXML($kul);
        $xsl = new DOMDocument();
        $xsl->load("empsearch.xsl");
        $proc = new XSLTProcessor();
        $proc->importStyleSheet($xsl);
        echo $proc->transformToXML($tmpDOM);
        ?>
    </body>
</html>