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
 * Base class for modules that deliver user viewable content
 */
abstract class ContentModule
{
  protected $contentXML;
  protected $name;
  protected $mode;

  function __construct()
  {
    // FIXME: Decide on this behaviour
    // // Is this The Right Thing(TM)?
    // $this->name = 'anon';
    // $this->mode = 'default';

    $this->contentXML = <<< XML
<section>
  <name>noname</name>
  <head>head</head>
  <body>body</body>
</section>
XML;
  }

  protected function generateDefault()
  {
    // Does nothing...
  }

  // Default behaviour is just to call generateDefault()
  protected function generateToggler()
  {
    $this->generateDefault();
  }

  // Default behaviour is just to call generateDefault()
  protected function generateTeaser()
  {
    $this->generateDefault();
  }

  public function getXML()
  {
    switch ($this->mode) {
      case 'teaser':
        $this->generateTeaser(); break;

      case 'toggler':
        $this->generateToggler(); break;

      case '':
        // no-op
        break;

      default:
        $this->generateDefault();
    }

    return ($this->contentXML);
  }

  public function getName()
  {
    return $this->name;
  }

  public function setMode($mode)
  {
    $this->mode = $mode;
  }
  public function getMode()
  {
    return $this->mode;
  }
}
?>