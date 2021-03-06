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
class Feed extends ContentModule
{
  private $head;
  private $foot;
  private $body;
  
  //non-xml vars
  private $feed;
  private $type;
  private $feedArray;
  
  //feed reader
  private $reader;

  function __construct($settings)
  {
    parent::__construct();

    $this->name = "<name>$settings[name]</name>";
    $this->icon = "<icon>$settings[icon]</icon>";

    $this->head = ($settings['head'] != '')?
      "<head>$settings[head]</head>": '';

    $this->foot = ($settings['foot'] != '')?
      "<foot>$settings[foot]</foot>": '';
      
    // $this->body = ($settings['feed'] != '')?
    //   "<body>$settings[feed]</body>": '';
    
    //non xml-vars
    
    $this->feed = ($settings['feed'] != '')?
      "$settings[feed]": '';

    $this->type = ($settings['type'] != '')?
      "$settings[type]": '';
      
    @$this->getFeed();
  }

  protected function getFeed() {
    switch ($this->type) {
      case 'atom':
        //same lib as rss
      case 'rss':
        $this->reader = new RssAtomReader();
        $this->feedArray = $this->reader->Universal_Reader($this->feed);
        // var_dump($this->feedArray);
        break;
      case 'facebook':
        $this->reader = new FacebookFeedReader();
        $this->feedArray = $this->reader->Read($this->feed);
        //var_dump($this->feedArray);
        break;
      default:
        # code...
        break;
    }
  }

  protected function generateDefault() 
  {
    $this->head = "<head>";
    $this->head .= "<title>".$this->feedArray[0]["title"]."</title>";
    if($this->feedArray[0]["link"] != "") {
      $this->head .= "<link>".$this->feedArray[0]["link"]."</link>";
    }
    $this->head .= "<desc>".$this->feedArray[0]["description"]."</desc>";
    $this->head .= "</head>";
    
    $this->body = "<body>";
    array_shift($this->feedArray);
    foreach($this->feedArray as $item) {
      $this->body .= "<item>";
      $this->body .= "<title>".$item["title"]."</title>";
      if($item["link"] != "") {
        $this->body .= "<link>".$item["link"]."</link>";
      }
      $this->body .= "<desc>".$item["description"]."</desc>";
      if($item["pubDate"] != "") {
        $date = explode(' ',$item["pubDate"]);
        $translateMonth = array("Jan" => '01',
                                "Feb" => '02',
                                "Mar" => '03',
                                "Apr" => '04',
                                "May" => '05',
                                "Jun" => '06',
                                "Jul" => '07',
                                "Aug" => '08',
                                "Sep" => '09',
                                "Oct" => '10',
                                "Nov" => '11',
                                "Dec" => '12');
        //var_dump($date);
        $this->body .= "<pubDate>";
        $this->body .= $date[3].'-';
        $this->body .= $translateMonth[$date[2]].'-';
        $this->body .= $date[1];
        $this->body .= "</pubDate>";
      }
      $this->body .= "</item>";
    }
    $this->body .= "</body>";
    
    $this->contentXML = <<< XML
<section>
  <feed>
    $this->name
    $this->head
    $this->body
    $this->foot
  </feed>
</section>
XML;
  }

  protected function generateToggler()
  {
    $this->contentXML = <<< XML
<toggler>
  <feed>
    $this->name
    $this->icon
    $this->head
    $this->foot
  </feed>
</toggler>
XML;
  }

  protected function generateTeaser()
  {
    $this->body = "<body>";
    $this->body .= "<title>".$this->feedArray[1]["title"]."</title>";
    if($this->feedArray[1]["link"] != "") {
      $this->body .= "<link>".$this->feedArray[1]["link"]."</link>";
    }
    $this->body .= "<desc>".$this->feedArray[1]["description"]."</desc>";
    if($this->feedArray[1]["pubDate"] != "") {
      //this should be done in the lib_files so that all feeds have the
      //same format.
      $date = explode(' ',$this->feedArray[1]["pubDate"]);
      $translateMonth = array("Jan" => '01',
                              "Feb" => '02',
                              "Mar" => '03',
                              "Apr" => '04',
                              "May" => '05',
                              "Jun" => '06',
                              "Jul" => '07',
                              "Aug" => '08',
                              "Sep" => '09',
                              "Oct" => '10',
                              "Nov" => '11',
                              "Dec" => '12');
      //var_dump($date);
      $this->body .= "<pubDate>";
      $this->body .= $date[3].'-';
      $this->body .= $translateMonth[$date[2]].'-';
      $this->body .= $date[1];
      $this->body .= "</pubDate>";
    }
    $this->body .= "</body>";
    
    $this->contentXML = <<< XML
<teaser>
  <feed>
    $this->name
    $this->head
    $this->body
    $this->foot
  </feed>
</teaser>
XML;
  }
  
  protected function generateAjax()
  {
    array_shift($this->feedArray);
    $this->body = "<body>";
    foreach($this->feedArray as $item) {
      $this->body .= "<item>";
      $this->body .= "<title>".$item["title"]."</title>";
      if(isset($item["link"]) && $item["link"] != "") {
        $this->body .= "<link>".$item["link"]."</link>";
      }
      $this->body .= "<desc>".$item["description"]."</desc>";
      if(isset($item["pubDate"]) && $item["pubDate"] != "") {
        $date = explode(' ',$item["pubDate"]);
        $translateMonth = array("Jan" => '01',
                                "Feb" => '02',
                                "Mar" => '03',
                                "Apr" => '04',
                                "May" => '05',
                                "Jun" => '06',
                                "Jul" => '07',
                                "Aug" => '08',
                                "Sep" => '09',
                                "Oct" => '10',
                                "Nov" => '11',
                                "Dec" => '12');
        //var_dump($date);
        $this->body .= "<pubDate>";
        $this->body .= $date[3].'-';
        $this->body .= $translateMonth[$date[2]].'-';
        $this->body .= $date[1];
        $this->body .= "</pubDate>";
      }
      $this->body .= "</item>";
    }
    $this->body .= "</body>";
    $this->contentXML = <<< XML
<ajax>
  <feed>
    $this->body
  </feed>
</ajax>
XML;
  }
}
?>