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

require_once 'rss_atom-reader.php';

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
      
    $this->getFeed();
  }

  protected function getFeed() {
    switch ($this->type) {
      case 'atom':
        //same lib as rss
      case 'rss':
        $this->reader = new RssAtomReader();
        $this->feedArray = $this->reader->Universal_Reader($this->feed);
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
    $this->head .= "<link>".$this->feedArray[0]["link"]."</link>";
    $this->head .= "<desc>".$this->feedArray[0]["description"]."</desc>";
    $this->head .= "</head>";
    
    $this->body = "<body>";
    array_shift($this->feedArray);
    foreach($this->feedArray as $item) {
      $this->body .= "<item>";
      $this->body .= "<title>".$item["title"]."</title>";
      $this->body .= "<link>".$item["link"]."</link>";
      $this->body .= "<desc>".$item["description"]."</desc>";
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
    $this->contentXML = utf8_decode($this->contentXML);
  }

  protected function generateToggler()
  {
    array_shift($this->feedArray);
    $this->body = "<body>";
    foreach($this->feedArray as $item) {
      $this->body .= "<item>";
      $this->body .= "<title>".$item["title"]."</title>";
      $this->body .= "<link>".$item["link"]."</link>";
      $this->body .= "<desc>".$item["description"]."</desc>";
      $this->body .= "</item>";
    }
    $this->body .= "</body>";
    $this->contentXML = <<< XML
<toggler>
  <feed>
    $this->name
    $this->icon
    $this->head
    $this->body
    $this->foot
  </feed>
</toggler>
XML;
    $this->contentXML = utf8_decode($this->contentXML);
  }

  protected function generateTeaser()
  {
    $this->body = "<body>";
    $this->body .= "<title>".$this->feedArray[1]["title"]."</title>";
    $this->body .= "<link>".$this->feedArray[1]["link"]."</link>";
    $this->body .= "<desc>".$this->feedArray[1]["description"]."</desc>";
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
    $this->contentXML = utf8_decode($this->contentXML);
  }
}
?>