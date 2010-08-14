<?php
// <PROGRAM_NAME>
// Copyright (C) 2010 Emilio Nyaray (emny1105@student.uu.se)
//                    Anders Steinrud (anst7337@student.uu.se)
//                    Magnus Söderling (magnus.soderling@gmail.com)
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


// ========
// = Init =
// ========
ob_start();
session_start();

define('in_index', true);
require_once '../conf/config.php';

// THE FOLLOWING LINES DO NOT WORK DUE TO THE SYSTEM CONFIGURATION.
// --------
// set_include_path(get_include_path().PATH_SEPARATOR.
//   MODULE_DIR.PATH_SEPARATOR.INCLUDE_DIR);
// spl_autoload_register();
// --------
// END OF BROKEN-NESS

// We do this instead of using the nice functions offered by PHP...
function __autoload($name)
{
  $name = strtolower($name);

  if(is_file(INCLUDE_DIR . $name . '.php'))
  {
    require_once INCLUDE_DIR . $name . '.php';
  }
  elseif(is_file(MODULE_DIR . "$name/" . $name . '.php'))
  {
    require_once MODULE_DIR . "$name/" . $name . '.php';
  }
  else
  {
    // FIXME: Log this error properly
    echo "could not find definition of class $name<br>\n";
  }
}

// Require core functionality
require_once INCLUDE_DIR.'core.php';

$rootDoc = new DOMDocument();

// FIXME: What if we can't load the document at the given location?
$rootDoc->load(CONFIG_DIR.'root.xml');
// echo "----------------root.xml---------------\n";
// echo $rootDoc->saveXML();

// ===============================================================
// = Do some black magic with the page field in the get variable =
// ===============================================================
$page = "";

if (isset($_REQUEST['page']))
{
  $page = $_REQUEST['page'];

  $xPath = new DOMXPath($rootDoc);
  $items = $xPath->query("item[settings/name = '$page']");
  $item = ($items->length > 0)? $items->item(0): false;

  if($item !== false)
  {
    if(isset($_REQUEST['ajax']))
    {
      echo "ajax!";
      die(); // we probably don't want to do this... but it works for now.
    }
    else
    {
      $config = parseItem($item);
      $module = initModule($config);
      $module->setMode("default");
      $moduleXML = '<?xml version="1.0" encoding="utf-8" ?>' . "\n".
        $module->getXML();

      // echo "---1---\n";
      // var_dump($config);
      // echo "---2---\n";
      // var_dump($module);
      // echo "---3---\n";
      // var_dump($moduleXML);
      // echo "---END---\n";

      $moduleDoc = new DOMDocument();
      $moduleDoc->loadXML($moduleXML);
      $moduleElem = $moduleDoc->documentElement;

      $outDoc = new DOMDocument();
      $outDoc->loadXML('<?xml version="1.0" encoding="utf-8"?><root />');

      $title = $xPath->query("/root/title")->item(0);
      $titleNode = $outDoc->importNode($title, true);
      $outDoc->documentElement->appendChild($titleNode);

      $moduleNode = $outDoc->importNode($moduleElem, true);
      $outDoc->documentElement->appendChild($moduleNode);
      // echo "---outDoc XML---\n";
      // echo $outDoc->saveXML()."\n";

      $rootStylesheet = new DOMDocument();
      $rootStylesheet->load(CONFIG_DIR.'root.xsl');
      $transformer = new XSLTProcessor();
      $transformer->importStylesheet($rootStylesheet);
      echo $transformer->transformToXML($outDoc);

      ob_end_flush();
      flush();
      die();
    }
  }
  else
  {
    // FIXME: Do something to handle that there was no module with that name
  }
}

// =================
// = The main loop =
// =================
// Get the top-level items, instantiate the appropriate modules and generate
// their XML
$rootItems = ($rootDoc->hasChildNodes())?
  $rootDoc->getElementsByTagName('item'): false;

if($rootItems)
{

  // We have to do this in an ugly way since DOMNodeLists that are returned
  // from DOMDocument::getElementsByTagName() seem to differ from those
  // returned from DOMNode->childNodes. Magic.
  $itemCount = $rootItems->length;
  $i = 0;
  while($i < $itemCount)
  {
    // This SHOULDN'T work, but it seems that item(0) moves some internal
    // pointer one step forward. This issue has 1-3 Crazy Hours
    $rootItem = $rootItems->item(0);
    $i++;

    // echo "inspecting $rootItem->nodeName: $rootItem->nodeValue\n";
    // The node is a text node, skip it.
    // Do The Right Thing™ with the node.
    $itemConfig = parseItem($rootItem);
    $module = initModule($itemConfig);

    $moduleXML = '<?xml version="1.0" encoding="utf-8"?>' . "\n".
      $module->getXML();
    // echo "---module XML---\n";
    // echo "$moduleXML\n";

    $moduleDoc = new DOMDocument();
    $moduleDoc->loadXML($moduleXML);
    // echo "---moduleDoc XML---\n";
    // echo $moduleDoc->saveXML()."\n";

    $moduleElem = $moduleDoc->documentElement;
    $moduleNode = $rootDoc->importNode($moduleElem, true);
    $parent = $rootItem->parentNode;
    $parent->removeChild($rootItem);
    $parent->appendChild($moduleNode);
  }

  // echo "---altered rootDoc xml---\n";
  // echo $rootDoc->saveXML();

  $rootStylesheet = new DOMDocument();
  $rootStylesheet->load(CONFIG_DIR.'root.xsl');

  $transformer = new XSLTProcessor();
  $transformer->importStylesheet($rootStylesheet);
  echo $transformer->transformToXML($rootDoc);
}
else
{
  // FIXME: Is it really a problem that there are no root elements?
}

ob_end_flush();
flush();
?>
