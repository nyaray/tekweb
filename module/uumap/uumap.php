<?php
// <PROGRAM_NAME>
// Copyright (C) 2010 Filip Gottfridsson (figo5633@student.uu.se)
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


define('UUMAP_XML_TO_REPLACE', '<?xml version="1.0" encoding="UTF-8"?>');


/**
*
*/
class UUMap extends ContentModule
{

  private $configPath;
  private $config;
  private $mapData;
  private $head;

  function __construct($settings)
  {
    parent::__construct();
    $this->configPath = '../module/uumap/uumap.xml';
    $this->name = "<name>$settings[name]</name>";
    $this->icon = (isset ($settings['icon'])) ?
      "<icon>$settings[icon]</icon>": '';
    $this->head = (isset ($settings['head'])) ?
      "<head>$settings[head]</head>": '';
  }

  protected function generateDefault()
  {
    try
    {
      if(!@$config = file_get_contents($this->configPath))
      {
        throw new Exception("Unable to read uumap-configfile");
      }
    $config = str_replace(UUMAP_XML_TO_REPLACE, '', $config);
    } catch(Exception $e){ print $e->getMessage();}

    $this->contentXML = <<< XML
<section>
  <uumap>
    $this->head
    $this->name
    $this->icon
    $config
  </uumap>
</section>
XML;
  }
  protected function generateToggler()
  {

//    try
//    {
//      if(!@$config = file_get_contents($this->configPath))
//      {
//        throw new Exception("Unable to read uumap-configfile");
//      }
//    $config = str_replace(UUMAP_XML_TO_REPLACE, '', $config);
//    } catch(Exception $e){ print $e->getMessage();}

  $this->contentXML = <<< XML
<toggler>
<uumap>
  $this->head
  $this->name
  $this->icon
</uumap>
</toggler>
XML;
// var_dump($this->contentXML);
  }

  protected function generateTeaser()
  {
    generateToggler();
  }
  protected function generateAjax()
  {
    try
    {
      if(!@$config = file_get_contents($this->configPath))
      {
        throw new Exception("Unable to read uumap-configfile");
      }
    $config = str_replace(UUMAP_XML_TO_REPLACE, '', $config);
    } catch(Exception $e){ print $e->getMessage();}
    $this->contentXML = <<< XML
<ajax>
  <uumap>
    $config
  </uumap>
</ajax>
XML;
  }
}
?>