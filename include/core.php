<?php

// Takes an array, containing the configuration of a module, and initialises
// a module appropriately.
function initModule($itemConfig)
{
  $module = new $itemConfig['module']($itemConfig['settings']);
  $module->setMode($itemConfig['mode']);
  return $module;
}

function hasChild($p) {
 if ($p->hasChildNodes()) {
  foreach ($p->childNodes as $c) {
   if ($c->nodeType == XML_ELEMENT_NODE)
    return true;
  }
 }
 return false;
}

// Takes a DOMNode and extracts its information
// FIXME: Make this handle DOMDocuments of arbitrary depth
function parseItem($itemNode)
{
  $children = ($itemNode->hasChildNodes())? $itemNode->childNodes: false;
  $item = array();

  if($children)
  {
    foreach($children as $child)
    {
      if($child->nodeType == XML_ELEMENT_NODE) {
        // if($child->nodeName == '#text')
        // {
        //   continue;
        // }
        if(hasChild($child)) {
          if(isset($item[$child->nodeName])) {
            if(isset($item[$child->nodeName][0])) {
              $item[$child->nodeName][] = parseItem($child);
            }
            else {
              $tmp = array();
              $tmp[] = $item[$child->nodeName];
              $tmp[] = parseItem($child);
              $item[$child->nodeName] = $tmp;
            }
          }
          else {
            $item[$child->nodeName] = parseItem($child);
          }
        }
        elseif($child->nodeName == 'settings')
        {
          $item['settings'] = parseItem($child);
        }
        else
        {
          if(isset($item[$child->nodeName])) {
            if(is_array($item[$child->nodeName])) {
              $item[$child->nodeName][] = $child->nodeValue;
            }
            else {
              $tmp = array();
              $tmp[] = $item[$child->nodeName];
              $tmp[] = $child->nodeValue;
              $item[$child->nodeName] = $tmp;
            }
          }
          else {
            $item[$child->nodeName] = $child->nodeValue;
          }
        }
      }
    }
  }

  return $item;
}

// Read remote files without fopen or cURL.
// http://www.php-mysql-tutorial.com/wikis/php-tutorial/reading-a-remote-file-using-php.aspx
function getRemoteFile($url)
{
   // get the host name and url path
   $parsedUrl = parse_url($url);
   $host = $parsedUrl['host']; 
   if (isset($parsedUrl['path'])) {
      $path = $parsedUrl['path'];
   } else {
      // the url is pointing to the host like http://www.mysite.com
      $path = '/';
   }
   if (isset($parsedUrl['query'])) {
      $path .= '?' . $parsedUrl['query'];
   } 

   if (isset($parsedUrl['port'])) {
      $port = $parsedUrl['port'];
   } else {
      // most sites use port 80
      $port = '80';
   }
   $timeout = 10;
   $response = '';
   // connect to the remote server 
   $fp = fsockopen($host, $port, $errno, $errstr, $timeout );
   if( !$fp ) { 
      echo "Cannot retrieve $url";
   } else {
      // send the necessary headers to get the file 
      fputs($fp, "GET $path HTTP/1.0\r\n" .
                 "Host: $host\r\n" .
                 "User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.3) Gecko/20060426 Firefox/1.5.0.3\r\n" .
                 "Accept: */*\r\n" .
                 "Accept-Language: en-us,en;q=0.5\r\n" .
                 "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7\r\n" .
                 "Referer: http://$host\r\n\r\n");
      // retrieve the response from the remote server 
      while ( $line = stream_socket_recvfrom( $fp, 4096 ) ) {
         $response .= $line;
      }
      fclose( $fp );
      // strip the headers
      $pos      = strpos($response, "\r\n\r\n");
      $response = substr($response, $pos + 4);
   }
   // return the file content 
   return $response;
}

function handleInstanceReq($rootDoc, $req, $name) {
  $xPath = new DOMXPath($rootDoc);
  $items = $xPath->query("item[settings/name = '$name']");
  $item = ($items->length > 0)? $items->item(0): false;
  
  if($item !== false)
  {
    // if(isset($_REQUEST['ajax']))
    // {
    //   echo "ajax!";
    //   die(); // we probably don't want to do this... but it works for now.
    // }
    // else
    // {
      $config = parseItem($item);
      $module = initModule($config);
      switch ($req) {
        case 'ajax':
          $module->setMode("ajax");
          break;
        case 'page':
          $module ->setMode("default");
          break;
        default:
          die();
          break;
        }
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
    // }
  }
  else
  {
    // FIXME: Do something to handle that there was no module with that name
  }
}

// FIXME: Implement these functions (are they necessary? not likely...)
function getRequestVars($moduleName) {}
function getGetVars($moduleName) {}
function getPostVars($moduleName) {}

?>