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

// require_once INCLUDE_DIR.'lib_timeedit';
require_once INCLUDE_DIR.'core.php';

/**
* 
*/
class TimeEdit extends ContentModule
{
  private $calendarData;

  function __construct($settings)
  {
    parent::__construct();
    $this->settings = $settings;

    $this->name = (isset($settings['name']) && $settings['name'] != '')?
      "<name>$settings[name]</name>": '';

    $this->head = (isset($settings['head']) && $settings['head'] != '')?
      "<head>$settings[head]</head>": '';

    $this->calendarData = <<< XML
<section>
  <timeedit />
</section>
XML;
  }

  protected function generateDefault()
  {
    $this->generateCalendarXML();
    $doc = $this->transformCalendarData(
      file_get_contents(MODULE_DIR.'timeedit/default.xsl'));

    $headNode = $doc->getElementsByTagName("head")->item(0);
    // var_dump($headNode->nodeName);
    ($headNode->nodeValue = $this->settings['head']);

    $this->contentXML = $doc->saveXML();
    $this->contentXML =
      str_replace('<?xml version="1.0"?>', '', $this->contentXML);
  }

  protected function generateToggler()
  {
    $this->generateDefault();
  }

  protected function generateTeaser()
  {
    $this->generateDefault();
  }

  protected function transformCalendarData($template)
  {
    $calendarDoc = new DOMDocument();
    $calendarDoc->loadXML($this->calendarData);

    $xsl = new DOMDocument();
    $xsl->loadXML($template);

    $proc = new XSLTProcessor();
    $proc->importStyleSheet($xsl);
    return $proc->transformToDoc($calendarDoc);
  }

  protected function generateCalendarXML()
  {
    $objects = array(73744000, 18962000);
    $startWeek = "1010";
    $stopWeek = "1050";

    $this->calendarData =
      LibTimeEdit::generateCalendar($objects, $startWeek, $stopWeek);
  }
}
?>