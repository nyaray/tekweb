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
class StaticText extends ContentModule
{
  private $head;
  private $body;
  private $foot;

  function __construct($settings)
  {
    parent::__construct();

    $this->name = (isset ($settings['name'])) ?
      "<name>$settings[name]</name>": '';

    $this->icon = (isset ($settings['icon'])) ?
      "<icon>$settings[icon]</icon>": '';

    $this->head = (isset ($settings['head'])) ?
      "<head>$settings[head]</head>": '';

    $this->body = (isset ($settings['body'])) ?
      "<body>$settings[body]</body>": '';

    $this->foot = (isset ($settings['foot'])) ?
      "<foot>$settings[foot]</foot>": '';
  }

  protected function generateDefault()
  {
    $this->contentXML = <<< XML
<section>
  <statictext>
    $this->name
    $this->head
    $this->body
    $this->foot
  </statictext>
</section>
XML;
  }

  protected function generateToggler()
  {
    $this->contentXML = <<< XML
<toggler>
  <statictext>
    $this->name
    $this->icon
    $this->head
    $this->body
    $this->foot
  </statictext>
</toggler>
XML;
  }

  protected function generateTeaser()
  {
    $this->contentXML = <<< XML
<teaser>
  <statictext>
    $this->name
    $this->head
    $this->body
    $this->foot
  </statictext>
</teaser>
XML;
  }
}
?>