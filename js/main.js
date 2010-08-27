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

$(document).ready(function() {
  var active;
  var ajaxLoaded = {};
  var currScreenWidth = screen.width;

  // Detect whether device supports orientationchange event, otherwise fall back to
  // the resize event.
  var supportsOrientationChange = "onorientationchange" in window,
  orientationEvent = supportsOrientationChange ? "orientationchange" : "resize";

  window.addEventListener(orientationEvent, function() {
    if((screen.width != currScreenWidth) && active) {
      currScreenWidth = screen.width;
      //alert('HOLY ROTATING SCREENS BATMAN:' + window.orientation + " " + screen.width + "\n"+orientationEvent);
      active.prev().click(); // closes the active toggler
      alert("nu ska den ha gömts");
      active.prev().click(); // opens it again (recalculating it's position)
      alert("nu ska den visas igen");
    }
  }, false);

  $('.toggler .togglerbutton').click(function() {
    var p = $(this).next();
    var id = $(this).parent().attr('id');
    
    if(p.hasClass('hidden')) {      // if p is hidden
      if(active) {                    // if there is an active toggler 
        active.addClass('hidden');      // Hide the active toggler
        active.prev().children(':first').removeClass('active');
      }
      active = p;
      p.removeClass('hidden');        // show the clicked toggler
      p.prev().children(':first').addClass('active');
      
      if(jQuery.data(ajaxLoaded, id)) {
        var left = $(this).parent().position().left;
        p.children(':first').css('margin-left',-left);
        var h = p.children(':first').height() +20;
        p.height(h);
      } 
      else {
        p.children(':first').html("<img src='gfx/load.gif' />");
        
        var t = $(this);
        var left = $(this).parent().position().left;
        p.children(':first').css('margin-left',-left);
        var h = p.children(':first').height() +20;
        p.height(h);
        
        $.ajax({url: "index.php", 
        data: {ajax: id}, 
        dataType: "html", 
        success: function(data) {
          p.children(':first').replaceWith(data);
          
          left = t.parent().position().left;
          p.children(':first').css('margin-left',-left);
          h = p.children(':first').height() +20;
          p.height(h);
          left = t.parent().position().left;
          p.children(':first').css('margin-left',-left);
          h = p.children(':first').height() +20;
          p.height(h);
          
          jQuery.data(ajaxLoaded, id, true);
        }
      });
      }                     // set active to the clicked
    } 
    else {                        // if p is already visible
      p.addClass('hidden');           // hide it
      p.prev().children(':first').removeClass('active');
      active = false;
    }
    return false
  });
});

function loadScript(url)
{
  alert("go");
  alert(url);
  var s = document.createElement('script');
  s.type='text/javascript';
  s.src= url;
  document.getElementsByTagName('head')[0].appendChild(s);
}
