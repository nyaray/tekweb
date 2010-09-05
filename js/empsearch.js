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

    $('.empform .button a').live('click', function() {
        $(this).parents('form').trigger('submit');
        return false;
    });

    $('.empform').live('submit', function() {
        var toggler = $(this).parents('.toggler');
        var togglerBtn = toggler.find('.togglerbutton');
        var instanceName = toggler.attr('id');
        var searchVal = toggler.find('form.empform input[type=text]').val();
        var togglCBody = toggler.find('.togglercontentbody');
        
        // && - Not on a page(no togglCBody on a page)
        if ((searchVal != '') && (togglCBody.size()==1)){
            var inputBtn = $(this).parent();
            inputBtn.html("<img src='gfx/load.gif' />");

            //For statistics, make sure there is a file named like the
            //module is named in root.xml + "-actualsearch.html"
            //in root of webpage. (Ignoring request result)
            $.get(instanceName + "-actualsearch.html");

            //For testing statistics, will not work in opera!
            //            $.get(instanceName + "-actualsearch.html", function(data) {
            //                console.log(data);
            //            });

            $.post("index.php", {
                empsearchstring : searchVal,
                ajax : instanceName
            },
            function(data){
                var content = data;
                togglCBody.replaceWith(content);
                var employees = $('#' +instanceName+ ' ul.employees li ul');

                $('#' +instanceName+ ' ul.employees > li > b')
                .wrap('<a href="" />');
                
                if(employees.size() > 10){
                    employees.each(function(){
                        var toHide = $(this).find('li:nth-child(n+2)');
                        if (toHide.size()>0){
                            toHide.addClass('hidden');

                            $(this).prev('a')
                            .append('<span class="openclose"></span>')
                            .find('.openclose')
                            .css("background-image"
                                ,"url(gfx/module/empsearch/plus-8.png)");
                        }else {
                            $(this).parent('li').find('a b').eq(0).unwrap();
                        }
                    });
                } else {
                    employees.each(function(){
                        var toHide = $(this).find('li:nth-child(n+2)');
                        if (toHide.size()>0){
                            $(this).parent('li').find('a').eq(0)
                            .append('<span class="openclose"></span>')
                            .find('.openclose')
                            .css("background-image"
                                ,"url(gfx/module/empsearch/minus-8.png)");
                        } else {
                    $(this).parent('li').find('a b').eq(0).unwrap();
                    }
                    });
                }
                var winWidth = $(window).width();
                //alert("WIN" + winWidth);
                //-4 for borders set in css
                $(".empsearchmodule .togglercontentbody").width(winWidth-24);
                togglerBtn.trigger('click');
                togglerBtn.trigger('click');
            });
        }

        if (togglCBody.size()==1){
            return false
        }
        //When run from a "page" with js on.
        return true;
    });

    $('.empsearchmodule ul.employees > li > a').live('click', function(){
        var items = $(this).parent('li').find('li');
        var togglBtn = $(this).parents('.toggler').find('.togglerbutton');
        var trg = $(this).find('span.openclose');
        if (items.hasClass('hidden')){            
            items.removeClass('hidden');
            trg.css("background-image","url(gfx/module/empsearch/minus-8.png)");

        } else {
            items.slice(1).addClass('hidden');
            trg.css("background-image","url(gfx/module/empsearch/plus-8.png)");
        }
        togglBtn.trigger('click');
        togglBtn.trigger('click');
        return false;
    });
});
