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


/**
 *
 */
class UUMap extends ContentModule
{

	private $configPath;
	private $mapData;
	private $head;

  function __construct($settings)
  {
    parent::__construct();
		$this->configPath = $settings['configpath'];
    $this->name = "<name>$settings[name]</name>";
    $this->icon = (isset ($settings['icon'])) ?
            "<icon>$settings[icon]</icon>": '';
    $this->head = (isset ($settings['head'])) ?
            "<head>$settings[head]</head>": '';
  }

  protected function generateDefault()
  {
    $config = file_get_contents($this->configPath);
    $config = str_replace('<?xml version="1.0" encoding="UTF-8">',"",$config); // <?xml version="1.0" encoding="UTF-8">FRÅGETECKEN>

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
    $config = file_get_contents($this->configPath);
    $config = str_replace('<?xml version="1.0" encoding="UTF-8">',"",$config); // <?xml version="1.0" encoding="UTF-8">FRÅGETECKEN>

    $this->contentXML = <<< XML
<toggler>
  <uumap>
    $this->head
    $this->name
    $this->icon
    $config
  </uumap>
</toggler>
XML;
  }

  protected function generateTeaser()
  {
    $this->generateToggler();
  }

  // protected function transformMapXML()
  // {
  //   $mapDoc = new DOMDocument();
  //   $mapDoc->load($this->configPath);
  // 
  //   $xsl = new DOMDocument();
  //   $xsl->load('uumap.xsl');
  // 
  //   $proc = new XSLTProcessor();
  //   $proc->importStyleSheet($xsl);
  //   return $proc->transformToXML($mapDoc);
  // }
  
}
?>