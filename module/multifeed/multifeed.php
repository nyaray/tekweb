<?php
// <PROGRAM_NAME>
// Copyright (C) 2010 Emilio Nyaray (emny1105@student.uu.se) &
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

require_once INCLUDE_DIR.'lib_rss-atom.php';
require_once INCLUDE_DIR.'lib_facebookfeed.php';

/**
 * Feed presenter, uses a FeedFetcher as a source of data
 */
class MultiFeed extends ContentModule
{
  private $head;
  private $foot;
  private $body;

  private $type;
  
  private $feeds;
  
  //feed reader
  private $reader;
  
  //
  private $resultArray = array();

  function __construct($settings)
  {
    parent::__construct();

    $this->name = "<name>$settings[name]</name>";
    $this->icon = "<icon>$settings[icon]</icon>";

    $this->head = ($settings['head'] != '')?
      "<head>$settings[head]</head>": '';

    $this->foot = ($settings['foot'] != '')?
      "<foot>$settings[foot]</foot>": '';
    
    //non xml-vars
    
    $this->feeds = ($settings['feeds'] != '')?
      $settings['feeds']: '';
      
    $this->getFeeds();
  }

  protected function getFeeds() {
    $count = 0;
    foreach($this->feeds['feed'] as $src) {
      switch ($src['type']) {
        case 'atom':
          //same lib as rss
        case 'rss':
          $this->reader = new RssAtomReader();

          $this->resultArray[$count] = array(
              "author" => $src['author'],
              "feed" => $this->reader->Universal_Reader($src['src'])
              );
          // var_dump($this->feedArray);
          break;
        case 'facebook':
          $this->reader = new FacebookFeedReader();
          
          $this->resultArray[$count] = array(
              "author" => $src['author'],
              "feed" => $this->reader->Read($src['src'])
              );
          //var_dump($this->feedArray);
          break;
        default:
          # code...
          break;
      }
      $count++;
    }
  }

  protected function generateDefault() 
  {
    $this->head = "<head>";
    $i = 0;
    while($i < count($this->resultArray)) {
      $this->head .= "<item>";
      $this->head .= "<title>".$this->resultArray[$i]['feed'][0]["title"]."</title>";
      if($this->resultArray[$i]['feed'][0]["link"] != "") {
        $this->head .= "<link>".$this->resultArray[$i]['feed'][0]["link"]."</link>";
      }
      $this->head .= "<desc>".$this->resultArray[$i]['feed'][0]["description"]."</desc>";
      $this->head .= "</item>";
      $i++;
    }
    $this->head .= "</head>";
    
    $this->body = "<body>";
    $i = 0;
    while($i < count($this->resultArray)) {
      $auth = $this->resultArray[$i]['author'];
      array_shift($this->resultArray[$i]['feed']);
      foreach($this->resultArray[$i]['feed'] as $item) {
        $this->body .= "<item>";
        $this->body .= "<author>".$auth."</author>";
        $this->body .= "<title>".$item["title"]."</title>";
        if($item["link"] != "") {
          $this->body .= "<link>".$item["link"]."</link>";
        }
        if($item["description"] != "") {
          $this->body .= "<desc>".$item["description"]."</desc>";
        }
        $this->body .= "</item>";
      }
      $i++;
    }
    $this->body .= "</body>";
    $this->contentXML = <<< XML
<section>
  <multifeed>
    $this->name
    $this->head
    $this->body
    $this->foot
  </multifeed>
</section>
XML;
  }

  protected function generateToggler()
  {
    $this->body = "<body>";
    $i = 0;
    while($i < count($this->resultArray)) {
      array_shift($this->resultArray[$i]);
      foreach($this->resultArray[$i] as $item) {
        $this->body .= "<item>";
        $this->body .= "<title>".$item["title"]."</title>";
        if($item["link"] != "") {
          $this->body .= "<link>".$item["link"]."</link>";
        }
        $this->body .= "<desc>".$item["description"]."</desc>";
        $this->body .= "</item>";
      }
      $i++;
    }
    $this->body .= "</body>";
    $this->contentXML = <<< XML
<toggler>
  <multifeed>
    $this->name
    $this->icon
    $this->head
    $this->body
    $this->foot
  </multifeed>
</toggler>
XML;
  }

  protected function generateTeaser()
  {
    $this->body = "<body>";
    
    $i = 0;
    while($i < count($this->resultArray)) {
      $this->body = "<item>";
      $this->body .= "<title>".$this->resultArray[$i][1]["title"]."</title>";
      if($this->feedArray[1]["link"] != "") {
        $this->body .= "<link>".$this->resultArray[$i][1]["link"]."</link>";
      }
      $this->body .= "<desc>".$this->resultArray[$i][1]["description"]."</desc>";
      $this->body = "</item>";
      $i++;
    }
    $this->body .= "</body>";
    
    $this->contentXML = <<< XML
<teaser>
  <multifeed>
    $this->name
    $this->head
    $this->body
    $this->foot
  </multifeed>
</teaser>
XML;
  }
}
?>