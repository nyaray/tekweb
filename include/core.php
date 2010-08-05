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

// FIXME: Implement these functions (are they necessary? not likely...)
function getRequestVars($moduleName) {}
function getGetVars($moduleName) {}
function getPostVars($moduleName) {}

?>