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
    $('.empform').live('submit', function() {
        var toggler = $(this).parents('.toggler');
        var togglerBtn = toggler.find('.togglerbutton');
        var instanceName = toggler.attr('id');
        var searchVal = toggler.find('form.empform input[type=text]').val();
        
        if (searchVal != ''){
            $.post("index.php", {
                empsearchstring : searchVal,
                ajax : instanceName
            },
            function(data){
                var content = data;
            
                toggler.find('.togglercontentbody').replaceWith(content);
                var employees = $('.empsearchmodule ul.employees li ul');
                if(employees.size() > 10)
                    employees.find('li:nth-child(n+2)').addClass('hidden');
                $('.empsearchmodule ul.employees > li > b').wrap('<a href=""/>');
                togglerBtn.trigger('click');
                togglerBtn.trigger('click');
            });
        }
        return false;
    });
    $('.empsearchmodule ul.employees > li > a').live('click', function(){
        var items = $(this).parent('li').find('li');
        var togglBtn = $(this).parents('.toggler').find('.togglerbutton');

        if (items.hasClass('hidden')){
            items.removeClass('hidden');
        } else {
            items.slice(1).addClass('hidden');
        }
        togglBtn.trigger('click');
        togglBtn.trigger('click');
        return false;
    });

});
