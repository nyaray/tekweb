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

require_once INCLUDE_DIR.'core.php';

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
      if(isset($_GET['page']) && $_GET['page'] == $name)
      {
        
      }
    }

    // FIXME: failout or default value if head is omitted?
    $this->head = (isset($settings['head']) && $settings['head'] != '')?
      "<head>$settings[head]</head>": '';

    $this->contentXML = <<< XML
<section>
  <timeedit>
    $this->name
    $this->head
  </timeedit>
</section>
XML;
  }

  public function getXML()
  {
    $doc = new DOMDocument();

    if($this->mode == 'toggler' ||
       !isset($_GET['view']) ||
       $_GET['view'] != 'config')
    {
      $this->generateViewXML($doc);
    }
    else
    {
      $this->generateConfigXML($doc);
    }

    // echo "---timeedit generated XML---\n";
    // var_dump($doc->saveXML());

    $template = ($this->mode == 'toggler')? 'toggler': 'default';
    $modXSL = new DOMDocument();
    $modXSL->load(MODULE_DIR."timeedit/$template.xsl");
    $proc = new XSLTProcessor();
    $proc->importStyleSheet($modXSL);
    $this->contentXML = $proc->transformToXML($doc);
    // $this->contentXML = $doc->saveXML();

    // var_dump($doc->documentElement->nodeName);
    // var_dump($this->contentXML);

    // echo "---timeedit transformed XML ($template)---\n";
    // var_dump($this->contentXML);

    // the ... 1.0"'.'>' ... is because the line otherwise messes up the
    // syntax highlighting in some text editors
    $this->contentXML =
      str_replace('<?xml version="1.0"?'.'>', '', $this->contentXML);
    // echo "---timeedit returning---\n";
    // var_dump($this->contentXML);
    return ($this->contentXML);
  }

  protected function generateConfigXML($doc)
  {
    $doc->loadXML(LibTimeEdit::generateConfigForm($this->settings['head']));
    // echo "---LibTimeEdit::generateConfigForm()---\n";
    // var_dump($doc->saveXML());

    // FIXME: Check that this is what we want to look for
    if(isset($_GET['action']) /*&& $_GET['action'] == 'save'*/)
    {
      $this->saveObjects($doc);
    }

    // add "save button"
    $saveElem = $doc->createElement('input', '');
    $saveElem->setAttribute('type', 'submit');
    $saveElem->setAttribute('name', 'wv_bSearch');
    $saveElem->setAttribute('value', 'Spara');

    $docElem = $doc->getElementsByTagName('search')->item(0);
    $docElem->appendChild($saveElem);
    // echo "---LibTimeEdit::generateConfigXML()---\n";
    // var_dump($doc->saveXML());
  }

  protected function generateViewXML($doc)
  {
    if(isset($_COOKIE['timeedit.objects']) &&
      $_COOKIE['timeedit.objects'] != '')
    {
      $objects = json_decode($_COOKIE['timeedit.objects']);
      $calXML = LibTimeEdit::generateCalendar($objects);
      $doc->loadXML($calXML);
    }
    else
    {
      $doc->loadXML('<calendar></calendar>');
    }

    // echo "---generateViewXML()---\n";
    // var_dump($doc->saveXML());

    // inject conf-link
    $name = (isset($this->settings['name']))?
      $this->settings['name']: '';
    $confElem = $doc->createElement('conf', "?page=$name&amp;view=config");

    $viewList = $doc->getElementsByTagName('view')->item(0);
    if($viewList != null && $viewList->length > 0)
    {
      $docElem = $viewList->item(0);
      $docElem->appendChild($confElem);
    }
    else
    {
      $docElem = $doc->documentElement;
      $docElem->appendChild($confElem);
    }
    // echo "---generateViewXML()---\n";
    // var_dump($doc->saveXML());
  }

  protected function saveObjects($doc)
  {
    // echo "IMMA CHARGIN' MA SAVINS!!\n";

    $objects = array();
    foreach($_GET as $key => $val)
    {
      if(preg_match('/wv_obj[0-9]+/', $key))
      {
        $objects[$key] = $val;
      }
    }

    // echo "IMMA SAVING THE SETTINGS\n";
    setcookie('timeedit.objects', json_encode($objects));
    $noticeElem = $doc->createElement('notice', 'Sparade kurserna');
    $docElem = $doc->documentElement;
    $docElem->appendChild($noticeElem);

  }
}
?>
