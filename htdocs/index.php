<?php
// <PROGRAM_NAME>
// Copyright (C) 2010 Emilio Nyaray (emny1105@student.uu.se)
//                    Anders Steinrud (anst7337@student.uu.se)
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
define('in_index', true);
require_once '../conf/config.php';

// THE FOLLOWING LINES DO NOT WORK DUE TO THE SYSTEM CONFIGURATION.
// --------
// set_include_path(get_include_path().PATH_SEPARATOR.
//   MODULE_DIR.PATH_SEPARATOR.INCLUDE_DIR);
// spl_autoload_register();
// --------
// END OF BROKEN-NESS

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

//require_once INCLUDE_DIR.'core.php';

ob_start();
session_start();

// ===============================================================
// = Do some black magic with the page field in the get variable =
// ===============================================================
// This is meant to affect the behaviour of the ajax block OR the code that
// follows it.
$page = "";

if (isset($_POST['page']))
{
  $page = $_GET['page'];
}

// ========
// = Ajax =
// ========
if (isset($_POST['ajax']))
{
  echo "ajax!";
  die(); // we probably don't want to do this... but it works for now.
}

// =================================
// = Generate the intermediate XML =
// =================================
$rootDoc = new DOMDocument();
$rootDoc->load(CONFIG_DIR.'root.xml');
// echo "----------------root.xml---------------\n";
// echo $rootDoc->saveXML();

// Get the top-level items, instantiate the appropriate modules and generate
// their XML
$rootItems = ($rootDoc->hasChildNodes())?
  $rootDoc->getElementsByTagName('item'): false;

if($rootItems)
{
  // foreach($rootItems as $item)
  $rootItemCount = $rootItems->length;
  // echo "rootItemCount: $rootItemCount\n";
  $i = 0;
  while($i < $rootItemCount)
  {
    $item = $rootItems->item(0);
    // echo "$i";
    // var_dump($item);
    // echo "\n";

    $i++;
    if($item == null)
    {
      continue;
    }

    $itemChildren = ($item->hasChildNodes())?
      $item->childNodes: false;

    if($itemChildren)
    {
      // Create an array to store the modules configuration
      $itemConfig = array();

      // foreach($itemChildren as $conf)
      $itemChildrenCount = $itemChildren->length;
      $j = 0;
      while($j < $itemChildrenCount)
      {
        $conf = $itemChildren->item($j);

        if($conf->nodeName == '#text')
        {
          // No-op...
        }
        elseif($conf->nodeName == 'settings')
        {
          $settings = ($conf->hasChildNodes())?
            $conf->childNodes: false;

          if($settings)
          {
            $itemSettings = array();

            // foreach($settings as $setting)
            $settingCount = $settings->length;
            $k = 0;
            while($k < $settingCount)
            {
              $setting = $settings->item($k);

              if($setting->nodeName == '#text')
              {
                // no-op
              }
              else
              {
                $itemSettings[$setting->nodeName] = $setting->nodeValue;
              }

              $k++;
            }

            $itemConfig['settings'] = $itemSettings;
          }
        }
        else
        {
          $itemConfig[$conf->nodeName] = $conf->nodeValue;
        }

        $j++;
      }

      if($page != '' && $itemConfig['settings']['name'] == $page)
      {
        $module = new $itemConfig['module']($itemConfig['settings']);
        $module->setMode('default');
        $moduleXML = "<?xml version=\"1.0\"?>\n<root>\n".
          $module->getXML().
          "\n</root>";

        $rootDoc = new DOMDocument();
        $rootDoc->loadXML($moduleXML);

        break;
      }
      elseif($page != '' && $itemConfig['settings']['name'] != $page)
      {
        continue;
      }

      $module = new $itemConfig['module']($itemConfig['settings']);
      $module->setMode($itemConfig['mode']);
      $moduleXML = "<?xml version=\"1.0\"?>\n" . $module->getXML();
      // echo "-----------module XML----------\n$moduleXML\n";

      $moduleDoc = new DOMDocument();
      /* var_dump */($moduleDoc->loadXML($moduleXML));
      // echo "-------------moduleDoc XML-------------\n";
      // echo $moduleDoc->saveXML();

      $moduleElem = $moduleDoc->documentElement;
      $moduleNode = $rootDoc->importNode($moduleElem, true);
      
      $parent = $item->parentNode;
      $parent->removeChild($item);
      $parent->appendChild($moduleNode);
    }
  }

  // echo "----------------altered xml---------------\n";
  // echo $rootDoc->saveXML();

  $rootStylesheet = new DOMDocument();
  $rootStylesheet->load(CONFIG_DIR.'root.xsl');

  $transformer = new XSLTProcessor();
  $transformer->importStylesheet($rootStylesheet);
  echo $transformer->transformToXML($rootDoc);
}
else
{
  // Handle the lack of root-level items
}

ob_end_flush();
flush();
?>