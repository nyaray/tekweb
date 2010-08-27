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

    $('.empform .button').live('click', function() {
       $(this).parents('form').trigger('submit');
    });

    $('.empform').live('submit', function() {
        var toggler = $(this).parents('.toggler');
        var togglerBtn = toggler.find('.togglerbutton');
        var instanceName = toggler.attr('id');
        var searchVal = toggler.find('form.empform input[type=text]').val();
        var togglCBody = toggler.find('.togglercontentbody');

        if (searchVal != ''){
            var inputBtn = $(this).parent();
            inputBtn.html("<img src='gfx/load.gif' />");
            
            $.post("index.php", {
                empsearchstring : searchVal,
                ajax : instanceName
            },
            function(data){
                var content = data;
                togglCBody.replaceWith(content);
                var employees = $('.empsearchmodule ul.employees li ul');

                

                $('.empsearchmodule ul.employees > li > b')
                .wrap('<a href="" />');
                
                if(employees.size() > 10){
                    employees.each(function(){
                        var toHide = $(this).find('li:nth-child(n+2)');
                        if (toHide.size()>0){
                            toHide.addClass('hidden');
                            $(this).parent('li').find('a').eq(0)
                            .append('<span class="openclose"></span>')
                            .find('.openclose')
                            .css("background-image"
                                ,"url(gfx/module/empsearch/plus-8.png)");
                        }else {
                            $(this).parent('li').find('a b').unwrap();
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
                            $(this).parent('li').find('a b')/*.eq(0)*/.unwrap();
                        }
                    });
                }
                togglerBtn.trigger('click');
                togglerBtn.trigger('click');
            });
        }
        return false;
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
