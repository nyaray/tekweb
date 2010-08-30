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


// This static class acts as a proxy for the timeedit server at
// schema.angstrom.uu.se, with some suitable additions
class LibTimeEdit
{
  // generate XML for the config/search form
  public static function generateSearch($name, $head)
  {
    //echo "<!--\n";

    // get the timeedit arguments
    $wvArgs = self::filterGETParams();
    $wvObjects = self::manageObjects($wvArgs);

    $i = 1;
    foreach($wvObjects as $objectID)
    {
      $wvArgs["wv_obj$i"] = $objectID;
      $i++;
    }

    $wvStr = http_build_query($wvArgs, '', '&');
    $url = "http://schema.angstrom.uu.se/4DACTION/WebShowSearch/2/1?$wvStr";
    //echo "---$url---\n";
    $timeeditHTML = getRemoteFile($url);

    $searchXML = self::transform($timeeditHTML, MODULE_DIR.'timeedit/search.xsl');
    $searchDoc = new DOMDocument();
    $searchDoc->loadXML($searchXML);

    // augment DOM document
    self::augmentSearchDOM($searchDoc, $name, $head);

    $configXML = $searchDoc->saveXML();
    $configXML = str_replace('<?xml version="1.0"?'.'>', '', $configXML);

    //echo "-->\n";
    return $configXML;
  }

  // Pre-condition: LibTimeEdit::hasObjects() must have returned true
  //
  // generate XML for displaying the calendar
  public static function generateView($name, $head)
  {
    //echo "<!--\n";
    $objects =
      (isset($_COOKIE['timeeditobjects']) && $_COOKIE['timeeditobjects'] != '')?
      explode(',', $_COOKIE['timeeditobjects']): array();

    $objStr = "wv_obj1=$objects[0]";
    $objNum = count($objects);
    for($i = 1; $i < $objNum; $i++)
    {
      $thisNum = $i+1;
      $objStr .= "&wv_obj$thisNum=$objects[$i]";
    }

    $url = "http://schema.angstrom.uu.se/4DACTION/WebShowSearchPrint/2/1?".
      "wv_text=text&$objStr";
    //echo "---$url---\n";
    $timeeditHTML = getRemoteFile($url);
    $calXML = self::transform($timeeditHTML, MODULE_DIR.'timeedit/calendar.xsl');
    $calDoc = new DOMDocument();
    $calDoc->loadXML($calXML);

    // augment DOM Document
    self::augmentViewDOM($calDoc, $name, $head);

    $viewXML = $calDoc->saveXML();
    $viewXML = str_replace('<?xml version="1.0"?'.'>', '', $viewXML);

    //echo "-->\n";
    return $viewXML;
  }

  // gather all timeedit arguments in an array
  private static function filterGETParams()
  {
    $wvArgs = array();

    // store timeedit variables from get in $wvArgs, unless they are objects,
    // which are stored in $wvObjects
    foreach($_GET as $key => $val)
    {
      // ignore objects in the request string
      if(preg_match('/wv_obj[1-9]+[0-9]*/', $key))
        continue;

      if(preg_match('/wv_[a-zA-Z0-9]+/', $key))
        $wvArgs[$key] = $val;
    }

    if(!isset($wvArgs['wv_type']))
    {
      $wvArgs['wv_type'] = '3';
    }

    return $wvArgs;
  }

  public static function hasObjects()
  {
    $wvObjects =
      (isset($_COOKIE['timeeditobjects']) && $_COOKIE['timeeditobjects'] != '')?
      explode(',', $_COOKIE['timeeditobjects']): array();

    return count($wvObjects) > 0;
  }

  private static function manageObjects(&$wvArgs)
  {
    $wvObjects =
      (isset($_COOKIE['timeeditobjects']) && $_COOKIE['timeeditobjects'] != '')?
      explode(',', $_COOKIE['timeeditobjects']): array();

    if(isset($wvArgs['wv_addObj']) && $wvArgs['wv_addObj'] != '')
    {
      if(!in_array($wvArgs['wv_addObj'], $wvObjects))
        $wvObjects[] = $wvArgs['wv_addObj'];
    }

    if(isset($wvArgs['wv_delObj']) && $wvArgs['wv_delObj'] != '')
    {
      $objIndex = array_search($wvArgs['wv_delObj'], $wvObjects);
      if($objIndex !== false)
        unset($wvObjects[$objIndex]);
    }

    $objStr = implode(',', $wvObjects);
    $_COOKIE['timeeditobjects'] = $objStr;
    setcookie('timeeditobjects', $objStr, time()+31536000);
    return $wvObjects;
  }

  private static function transform($htmlSrc, $xslPath)
  {
    $html = new DOMDocument();
    $html->loadHTML($htmlSrc);

    $xsl = new DOMDocument();
    $xsl->load($xslPath);

    $proc = new XSLTProcessor();
    $proc->importStyleSheet($xsl);
    $procXML = $proc->transformToXML($html);
    $procXML = str_replace('<?xml version="1.0"?'.'>', '', $procXML);

    return $procXML;
  }

  private static function augmentSearchDOM($searchDoc, $name, $head)
  {
    $nameElem = $searchDoc->createElement('name', $name);
    $headElem = $searchDoc->createElement('head', $head);

    $docElem = $searchDoc->getElementsByTagName('search')->item(0);
    $docElem->appendChild($nameElem);
    $docElem->appendChild($headElem);
    //$docElem->appendChild($saveElem);
    //$docElem->appendChild($saveLabelElem);

    // bring the user back to the current page if it was set when the form was
    // submitted
    if(isset($_GET['page']) && $_GET['page'] !== '')
    {
      $pageElem = $searchDoc->createElement('input');
      $pageElem->setAttribute('type', 'hidden');
      $pageElem->setAttribute('name', 'page');
      $pageElem->setAttribute('value', $_GET['page']);
      $docElem->appendChild($pageElem);
    }

    // set the view again if it was set before the form was submitted
    if(isset($_GET['view']) && $_GET['view'] !== '')
    {
      $viewElem = $searchDoc->createElement('input');
      $viewElem->setAttribute('type', 'hidden');
      $viewElem->setAttribute('name', 'view');
      $viewElem->setAttribute('value', $_GET['view']);
      $docElem->appendChild($viewElem);
    }
  }

  private static function augmentViewDOM($viewDoc, $name, $head)
  {
    $nameElem = $viewDoc->createElement('name', $name);
    $headElem = $viewDoc->createElement('head', $head);
    $docElem = $viewDoc->getElementsByTagName('view')->item(0);

    $docElem->appendChild($nameElem);
    $docElem->appendChild($headElem);

    if(isset($_GET['page']) && $_GET['page'] !== '')
    {
      $pageElem = $viewDoc->createElement('input');
      $pageElem->setAttribute('type', 'hidden');
      $pageElem->setAttribute('name', 'page');
      $pageElem->setAttribute('value', $_GET['page']);
      $docElem->appendChild($pageElem);
    }

    if(isset($_GET['view']) && $_GET['view'] !== '')
    {
      $viewElem = $viewDoc->createElement('input');
      $viewElem->setAttribute('type', 'hidden');
      $viewElem->setAttribute('name', 'view');
      $viewElem->setAttribute('value', $_GET['view']);
      $docElem->appendChild($viewElem);
    }
  }
}
?>
