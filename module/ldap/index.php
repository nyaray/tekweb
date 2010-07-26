<?php

require_once 'empsearch.php';


$empsearch = new EmpSearch(array('banan' => 45));

//$entries = $empsearch->search();
//echo 'entries:' . $entries[0]['cn'][0] . "<br />";

$kul = $empsearch->getXML();

echo $kul;
// put your code here
?>