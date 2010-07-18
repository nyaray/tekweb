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
class Calendar extends ContentModule
{

  function __construct()
  {
    parent::__construct();
  }

  protected function generateToggle()
  {
    $this->contentXML = <<< HTML
<a name="schedule"> </a> 
<h1 class="button"><a href="#schedule">Schema</a></h1> 
<div class="toggleHide"> 
  <div> 
    <span class="scheduleitemsubject">Matematisk analys</span> 
    <span class="scheduleitemtime">13:15</span> 
    <span class="scheduleitemtype">Föreläsning</span> 
    <span class="scheduleitemlocation">Pol_1211</span> 
    <span class="scheduleitemtime">15:00</span> 
  </div> 
  <hr> 
  <div> 
    <span class="scheduleitemsubject">Matematisk analys</span> 
    <span class="scheduleitemtime">13:15</span> 
    <span class="scheduleitemtype">Föreläsning</span> 
    <span class="scheduleitemlocation">Pol_1211</span> 
    <span class="scheduleitemtime">15:00</span> 
  </div> 
  <hr> 
  <div> 
    <span class="scheduleitemsubject">Matematisk analys</span> 
    <span class="scheduleitemtime">13:15</span> 
    <span class="scheduleitemtype">Föreläsning</span> 
    <span class="scheduleitemlocation">Pol_1211</span> 
    <span class="scheduleitemtime">15:00</span> 
  </div> 
  <hr> 
  <div> 
    <span class="scheduleitemsubject">Matematisk analys</span> 
    <span class="scheduleitemtime">13:15</span> 
    <span class="scheduleitemtype">Föreläsning</span> 
    <span class="scheduleitemlocation">Pol_1211</span> 
    <span class="scheduleitemtime">15:00</span> 
  </div> 
</div> 
HTML;
  }
}

  

?>