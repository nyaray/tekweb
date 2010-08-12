<?php
// <PROGRAM_NAME>
// Copyright (C) 2010 Emilio Nyaray (emny1105@student.uu.se)
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

//require_once INCLUDE_DIR.'core.php';

/**
* 
*/
class TimeEdit extends ContentModule
{
  protected $view;

  function __construct($settings)
  {
    parent::__construct();
    $this->settings = $settings;

    // FIXME: failout when name is not set
    if(isset($settings['name']) && $settings['name'] != '')
    {
      $name = $settings['name'];

      $this->name = "<name>$name</name>";
    }

    // FIXME: failout or default value if head is omitted?
    $this->head = (isset($settings['head']) && $settings['head'] != '')?
      "<head>$settings[head]</head>": '';

    $this->contentXML = <<< XML
<section>
  <timeedit>
    <view>
      $this->name
      $this->head
    </view>
  </timeedit>
</section>
XML;
  }

  protected function generateDefault()
  {
    $doc = new DOMDocument();

    if(isset($_GET['view']) &&
       $_GET['view'] == 'config' &&
       $this->mode == 'default')
    {
      $this->generateSearchDoc($doc);
    }
    else
    {
      $this->generateViewDoc($doc);
    }

    $modXSL = new DOMDocument();
    $modXSL->load(MODULE_DIR."timeedit/default.xsl");
    $proc = new XSLTProcessor();
    $proc->importStyleSheet($modXSL);
    $this->contentXML = $proc->transformToXML($doc);
    $this->contentXML =
      str_replace('<?xml version="1.0"?'.'>', '', $this->contentXML);

    // print contentXML to see what's going on
    //echo "---contentXML---\n";
    //var_dump($this->contentXML);
  }

  protected function generateToggler()
  {
    $this->generateDefault();

    /*
    $doc = new DOMDocument();
    ...
     */
  }

  protected function generateTeaser()
  {
    $this->generateDefault();
  }

  protected function generateSearchDoc(&$doc)
  {
    $docXML = LibTimeEdit::generateSearch(
      $this->settings['name'], $this->settings['head']);
    $doc->loadXML($docXML);
  }

  protected function generateViewDoc($doc)
  {
    $docXML = LibTimeEdit::generateView();
    $doc->loadXML($docXML);
  }
}
?>
