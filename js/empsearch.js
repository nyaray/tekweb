// <PROGRAM_NAME>
// Copyright (C) 2010 Magnus Söderling (magnus.soderling@gmail.com)
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


/* 
 * This script requires jquery.
 */
$(function() {
    var togglerBtn = $('.toggler .togglerbutton[href="?page=empsearch"]');
    var tContentBody = $('div#empsearch div.togglercontentbody');
    var sectionBody = $('div#empsearch.section');
    var toggler = (tContentBody.length > 0);
    
    $('#empsearch form').live('submit', function() {
        var oldHeight = tContentBody.height();
        var searchVal = $('#empsearch form input[name=empsearchstring]').val();

        $.post("index.php", {
            empsearchstring : searchVal,
            ajax : "empsearch"
        },
        function(data){
            var content = data;
            
            if (toggler){
                $('div#empsearch div.togglercontentbody').replaceWith(content);
                togglerBtn.trigger('click');
                togglerBtn.trigger('click');
            } else{
                sectionBody.html(content);
            }
        });
        return false;
    });
});
