<?php
// <PROGRAM_NAME>
// Copyright (C) 2010 Anders Steinrud (anst7337@student.uu.se)
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

require_once '../include/rss_php.php';
/**
 * Feed presenter, uses a FeedFetcher as a source of data
 */
class Feed extends ContentModule
{
  private $head;
  private $feed;
  private $type;
  private $foot;
  
  private $json;

  function __construct($settings)
  {
    parent::__construct();

    $this->name = "<name>$settings[name]</name>";
    $this->icon = "<icon>$settings[icon]</icon>";

    $this->head = ($settings['head'] != '')?
      "<head>$settings[head]</head>": '';

    $this->feed = ($settings['feed'] != '')?
      "<feed>$settings[feed]</feed>": '';

    $this->type = ($settings['type'] != '')?
      "<type>$settings[type]</type>": '';

    $this->foot = ($settings['foot'] != '')?
      "<foot>$settings[foot]</foot>": '';
      
    }
  }
  
  protected function getJSONfromType($type) {
    switch ($type) {
        case 'json'
            echo "already JSON"
            return $this->feed;
            break;
        case 'rss':
            echo "RSS";
            $rss = new rss_php;
            $rss->load($this->feed);
            $this->json = json_encode($rss->getRSS());
            break;
        case 'atom':
            echo "unsupported feed type";
            break;
        case '':
            //no op
            break;
        default:
            echo "unrecognized feed type";
            break;
  }

  protected function generateDefault()
  {
    $json = getJSONfromType($this->type);

    // ojhugk 

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