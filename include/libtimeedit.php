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
  public static function generateCalendar($objects, $startWeek, $stopWeek)
  {
    if(count($objects) < 1)
      return '<calendar />';

    $objStr = 'wv_obj1='.$objects[0];

    $objectCount = count($objects);
    for($i = 1; $i < $objectCount; $i++)
    {
      $object = $objects[$i];
      $objStr .= "&wv_obj";
      $objStr .= $i+1;
      $objStr .= "=$object";
    }

    $url = "http://schema.angstrom.uu.se/4DACTION/WebShowSearchPrint/2/1?wv_text=text&$objStr&wv_startWeek=$startWeek&wv_stopWeek=$stopWeek";

    // echo "<!--\n";
    // echo "objStr: $objStr\n";
    // echo "startWeek: $startWeek\n";
    // echo "stopWeek: $stopWeek\n";
    // echo "url: $url\n";
    // echo "-->\n";

    $doc = new DOMDocument();
    $doc->loadHTMLFile($url);

    $xsl = new DOMDocument();
    $xsl->load(MODULE_DIR.'timeedit/calendar.xsl');

    $proc = new XSLTProcessor();
    $proc->importStyleSheet($xsl);
    $procXML = $proc->transformToXML($doc);
    return ($procXML);
    // fwrite(fopen('./lintit.xml', 'w'), $procXML);
    // echo exec("xmllint --format lintit.xml > lintfree 2> linterr");
    // return file_get_contents('lintfree');
  }

  public static function generateConfigForm()
  {
    // basket + search
    // $url = "http://schema.angstrom.uu.se/4DACTION/WebShowSearch/2/1-0?wv_type=3&wv_ts=20100724T151511X%3C%3C%3C%3C&wv_search=kanddv&wv_startWeek=1029&wv_stopWeek=1031&wv_first=0&wv_addObj=75598000&wv_delObj=&wv_obj1=69016002&wv_obj2=69016003";

    // search
    // $url = "http://schema.angstrom.uu.se/4DACTION/WebShowSearch/2/1-0?wv_type=3&wv_ts=20100724T174107X%3C%3C%3C%3C&wv_search=kanddv&wv_startWeek=1029&wv_stopWeek=1031&wv_first=0&wv_addObj=&wv_delObj=75598000&wv_obj1=75598000";

    // basket
    // $url = "http://schema.angstrom.uu.se/4DACTION/WebShowSearch/2/1-0?wv_type=3&wv_ts=20100724T183603X%3C%3C%3C%3C&wv_bSearch=S%F6k&wv_startWeek=1029&wv_stopWeek=1031&wv_first=0&wv_addObj=&wv_delObj=&wv_obj1=96003&wv_obj2=69016002&wv_obj3=75598000";

    // plain form
    $url = "http://schema.angstrom.uu.se/4DACTION/WebShowSearch/2/1";

    $doc = new DOMDocument();
    $doc->loadHTMLFile($url);

    $xsl = new DOMDocument();
    $xsl->load('search.xsl');

    $proc = new XSLTProcessor();
    $proc->importStyleSheet($xsl);
    $procXML = ($proc->transformToXML($doc));
    return ($procXML);
    // fwrite(fopen('./lintit.xml', 'w'), $procXML);
    // echo exec("xmllint --format lintit.xml > lintfree 2> linterr");
    // return file_get_contents('lintfree');
  }
}
?>