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
    //var theForm = $('#empsearch form');
    
 
    //theForm
    $('#empsearch form').live('submit', function() {
        var searchField = $('#empsearch form input[type=text][name=search]');
        var searchVal = searchField.val();
        $.get("index.php", {
            search : searchVal,
            page : "empsearch"
        },
        function(data){
            var args = $(data).find('div#empsearch').html();
            $('div#empsearch div.togglercontent').html(args);


            var height = $('div#empsearch div.togglercontent').height();
//            if (height != 35)
//                height += 10;
//            alert(height);
            $('div#empsearch > div').height(height);
        });
        return false;
    });

});

