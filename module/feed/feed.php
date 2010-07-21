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

require_once INCLUDE_DIR.'lib_rss.php';

/**
 * Feed presenter, uses a FeedFetcher as a source of data
 */
class Feed extends ContentModule
{
  private $head;
  private $foot;
  private $body;
  
  private $xml;
  
  //non-xml vars
  private $feed;
  private $type;

  function __construct($settings)
  {
    parent::__construct();

    $this->name = "<name>$settings[name]</name>";
    $this->icon = "<icon>$settings[icon]</icon>";

    $this->head = ($settings['head'] != '')?
      "<head>$settings[head]</head>": '';

    $this->foot = ($settings['foot'] != '')?
      "<foot>$settings[foot]</foot>": '';
      
    $this->body = ($settings['feed'] != '')?
      "<body>$settings[feed]</body>": '';
    
    //non xml-vars
    
    $this->feed = ($settings['feed'] != '')?
      "$settings[feed]": '';

    $this->type = ($settings['type'] != '')?
      "$settings[type]": '';
  }

  
  // protected function getXMLFromSrc() {
  //   switch ($type) {
  //     case 'atom':
  //       //same lib as rss
  //     case 'rss':
  //       //$xml = universal_reader($src_feed);
  //       break;
  //     
  //     default:
  //       # code...
  //       break;
  //   }
  //   return $xml;
  // }

  protected function generateDefault() 
  {
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
    $this->body
    $this->foot
  </feed>
</toggler>
XML;
  }

  protected function generateTeaser()
  {
    
    
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
}
?>