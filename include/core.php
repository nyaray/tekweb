<?php

// Takes an array, containing the configuration of a module, and initialises
// a module appropriately.
function initModule($itemConfig)
{
  $module = new $itemConfig['module']($itemConfig['settings']);
  $module->setMode($itemConfig['mode']);
  return $module;
}

// Takes a DOMNode and extracts its information
function parseItem($itemNode)
{
  $children = ($itemNode->hasChildNodes())? $itemNode->childNodes: false;
  $item = array();

  if($children)
  {
    foreach($children as $child)
    {
      if($child->nodeName == '#text')
      {
        continue;
      }
      elseif($child->nodeName == 'settings')
      {
        $item['settings'] = parseItem($child);
      }
      else
      {
        $item[$child->nodeName] = $child->nodeValue;
      }
    }
  }

  return $item;
}

// FIXME: Implement these functions (are they necessary? not likely...)
function getRequestVars($moduleName) {}
function getGetVars($moduleName) {}
function getPostVars($moduleName) {}

?>