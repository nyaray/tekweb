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

/**
* 
*/
class TimeEdit extends ContentModule
{
  protected $view, $name, $icon, $head, $foot;

  function __construct($settings)
  {
    parent::__construct();
    $this->settings = $settings;

    // FIXME: failout when a setting is not set
    $this->name = (isset($settings['name']) && $settings['name'] != '')?
      "<name>$settings[name]</name>": '';
    $this->icon = (isset($settings['icon']) && $settings['icon'] != '')?
      "<icon>$settings[icon]</icon>": '';
    $this->head = (isset($settings['head']) && $settings['head'] != '')?
      "<head>$settings[head]</head>": '';

    $this->contentXML = <<< XML
<section>
  <timeedit>
    <view>
      $this->name
      $this->icon
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
       $_GET['view'] == 'config')
    {
      //echo "<!-- timeedit in search -->\n";
      $this->generateSearchDoc($doc);
    }
    elseif(LibTimeEdit::hasObjects())
    {
      //echo "<!-- timeedit in view -->\n";
      $this->generateViewDoc($doc);
    }
    else
    {
      $doc->loadXML('<calendar></calendar>');
    }

    if(!isset($_GET['view']) ||
       $_GET['view'] != 'config')
    {
      $name = (isset($this->settings['name']))? $this->settings['name']: 'noname';
      $href = "?page=$name&view=config";
      $confLink = $doc->createElement('a', 'Välj kurser');
      $confLink->setAttribute('href', $href);
      $confElem = $doc->createElement('conf');
      $confElem->appendChild($confLink);
      $doc->documentElement->appendChild($confElem);
    }

    //echo "<!--\n";
    //echo "---doc XML---\n";
    //var_dump($doc->saveXML());
    //echo "-->";

    $modXSL = new DOMDocument();
    $modXSL->load(MODULE_DIR."timeedit/default.xsl");
    $proc = new XSLTProcessor();
    $proc->importStyleSheet($modXSL);
    $this->contentXML = $proc->transformToXML($doc);
    $this->contentXML =
      str_replace('<?xml version="1.0"?'.'>', '', $this->contentXML);

    // print contentXML to see what's going on
    //echo "<!--\n";
    //echo "---contentXML---\n";
    //var_dump($this->contentXML);
    //echo "-->\n";
  }

  protected function generateToggler()
  {
    $this->contentXML = <<< XML
<toggler>
  <timeedit>
    $this->name
    $this->icon
    $this->head
  </timeedit>
</toggler>
XML;
  }

  protected function generateAjax()
  {
    $doc = new DOMDocument();

    if(LibTimeEdit::hasObjects())
    {
      $this->generateViewDoc($doc);
    }
    else
    {
      $doc->loadXML('<calendar></calendar>');
      $name = (isset($this->settings['name']))? $this->settings['name']: 'noname';
      $href = "?page=$name&view=config";
      $confLink = $doc->createElement('a', 'Välj kurser');
      $confLink->setAttribute('href', $href);
      $confElem = $doc->createElement('conf');
      $confElem->appendChild($confLink);
      $doc->documentElement->appendChild($confElem);
    }

    $modXSL = new DOMDocument();
    $modXSL->load(MODULE_DIR."timeedit/ajax.xsl");
    $proc = new XSLTProcessor();
    $proc->importStyleSheet($modXSL);
    $this->contentXML = $proc->transformToXML($doc);
    $this->contentXML =
      str_replace('<?xml version="1.0"?'.'>', '', $this->contentXML);

    echo "<!--\n";
    echo "---contentXML---\n";
    var_dump($this->contentXML);
    echo "-->\n";

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

  protected function generateViewDoc(&$doc)
  {
    $docXML = LibTimeEdit::generateView(
      $this->settings['name'], $this->settings['head']);
    $doc->loadXML($docXML);
  }
}
?>
