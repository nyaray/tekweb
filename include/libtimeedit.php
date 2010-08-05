<?php
// <PROGRAM_NAME>
// Copyright (C) 2010 Emilio Nyaray (emny1105@student.uu.se)
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

class LibTimeEdit
{
  public static function generateCalendar($objects)
  {
    if(count($objects) < 1)
      return '<calendar />';

    $objStr = 'wv_obj1='.$objects[0];

    $objectCount = count($objects);
    for($i = 1; $i < $objectCount; $i++)
    {
      $object = $objects[$i];
      $num = $i+1;

      $objStr = "&wv_obj$num=$object";
    }

    $url = "http://schema.angstrom.uu.se/4DACTION/WebShowSearchPrint/2/1?".
      "wv_text=text&$objStr";

    $doc = new DOMDocument();
    $doc->loadHTMLFile($url);

    $xsl = new DOMDocument();
    $xsl->load(MODULE_DIR.'timeedit/calendar.xsl');

    $proc = new XSLTProcessor();
    $proc->importStyleSheet($xsl);
    $procXML = $proc->transformToXML($doc);

    // echo "---calXML---\n";
    // var_dump($procXML);
    return ($procXML);
  }

  public static function generateConfigForm($head)
  {
    $wvStr = '';
    $firstSet = false;
    foreach($_GET as $key => $val)
    {
      if(preg_match('/wv_[a-z0-9]+/', $key))
      {
        if($firstSet)
        {
          $wvStr .= "&$key=$val";
        }
        else
        {
          $wvStr .= "$key=$val";
          $firstSet = true;
        }
      }
    }

    $url = "http://schema.angstrom.uu.se/4DACTION/WebShowSearch/2/1?$wvStr";

    $xml = new DOMDocument();
    $xml->loadHTMLFile($url);

    $xsl = new DOMDocument();
    $xsl->load(MODULE_DIR.'timeedit/search.xsl');

    $proc = new XSLTProcessor();
    $proc->importStyleSheet($xsl);
    $doc = $proc->transformToDoc($xml);

    $headElem = $doc->createElement('head', $head);
    $searchNode = $doc->getElementsByTagName('search')->item(0);
    $searchNode->appendChild($headElem);

    $confXML = $doc->saveXML();

    // echo "---generateConfigForm XML---\n";
    // var_dump($confXML);
    return $confXML;
  }
}
?>