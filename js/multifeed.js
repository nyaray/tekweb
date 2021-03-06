// <PROGRAM_NAME>// Copyright (C) 2010 Markus Säfström (markus.safstrom@gmail.com)//// This program is free software: you can redistribute it and/or modify// it under the terms of the GNU General Public License as published by// the Free Software Foundation, either version 3 of the License, or// (at your option) any later version.//// This program is distributed in the hope that it will be useful,// but WITHOUT ANY WARRANTY; without even the implied warranty of// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the// GNU General Public License for more details.//// You should have received a copy of the GNU General Public License// along with this program.  If not, see <http://www.gnu.org/licenses/>.


$(function(){
	$('.feedconfig').live('click', function(){
		var toggler = $(this).parents('.toggler');
		var togglerBtn = toggler.find('.togglerbutton');
        var feedform = toggler.find('.feedform');
		var feedlist = toggler.find('#feedlist');

		if(feedform.hasClass('hidden')){
			feedform.removeClass('hidden');
			feedlist.addClass('hidden');
		} else {
			feedlist.removeClass('hidden');
			feedform.addClass('hidden');
		}

		togglerBtn.triggerHandler('click');
		togglerBtn.triggerHandler('click');		
		return false;
	});
	$('.feedfoot').live('click', function(){
		$(this).siblings(':not(".new")').toggleClass('hidden');
		var togglerBtn = $(this).parents('.toggler').find('.togglerbutton');
		togglerBtn.triggerHandler('click');
		togglerBtn.triggerHandler('click');
		var a = $(this).find('.button');
		if(a.hasClass("activeButton")){
		  a.html("Visa alla");
		} else {
		  a.html("Dölj");
		}
		a.toggleClass("activeButton");
		return false;
	});
	$('.feedform').live('submit', function(){
		var toggler = $(this).parents('.toggler');
		var modid = toggler.attr('id');
		var feedform = toggler.find('.feedform');
		var feedlist = toggler.find('#feedlist');
		var feedconfig = toggler.find('.feedconfig');
		// var togglerBtn = toggler.find(' .togglerbutton');
		var inputBtn = $(this).find('input[type=submit]');
		inputBtn.replaceWith('<img id="inputbutton" src="gfx/load.gif" />');
		
		$data = 'ajax=' + modid + '&feedcookie=Ok';
		
		feedform.find('input:checked').each(function (){
				$data += '&' + $(this).attr('name') + '=' + 'on';
		});
		
		$.post("index.php", $data,
        function(page){
            $content = $(page).find('#feedlist').html();
			
			feedform.find('#inputbutton').replaceWith('<input type="submit" value="Spara" name="feedcookie">');
			feedlist.html($content);
			feedconfig.click();
			
			// togglerBtn.triggerHandler('click');
            // togglerBtn.triggerHandler('click');
        });
	
		return false;
	});
});