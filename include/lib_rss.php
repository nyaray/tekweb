<?php
/*
	Universal Feed Reader Library
	(c) 2007-2009 Xul.fr - Licence Mozilla 1.1.
	Written by Denis Sureau
	http://www.xul.fr/feed/
*/


$Universal_Style = "p";   // replace that by span class="" to custom
$Universal_Date_Font = "size='-1'";

$Universal_FeedArray = array();

$Universal_AtomChannelTags = array("title","link","subTitle","updated");
$Universal_AtomItemTags = array("title","link","summary","pubDate");

$Universal_RssChannelTags = array("title","link","description","lastBuildDate");
$Universal_RssItemTags = array("title","link","description","pubDate");

$Universal_Translation = array("title"=>"title", 
  "link"=>"link",
  "description"=>"description",
  "subTitle"=>"description",
  "summary"=>"description",
  "lastBuildDate"=>"updated",
  "pubDate"=>"updated");

$Universal_Doc = false;


/**
 *  Read the content of a tag
 *  Input: 
 *  - element: the node
 *  - tag: the name of the tag
 *  Ouput:
 *  - the content
 */       

function getTag($element, $tag)
{
  $x = $element->getElementsByTagName($tag);
  if($x->length == 0)
  {
    return false;
  }  
  $x = $x->item(0);
  $x = $x->firstChild->textContent;
  return $x;
}

/**
 *  Read content of tags for the given list of names
 *  and push them into an array
 *  Input:
 *  - element: a node
 *  - listOfTags: an array holding the names of tags
 *  - type: 0 = channel, 1 = item
 *  Ouput:
 *  - the array that stores the data
 */           

function getTags($element, $listOfTags, $type)
{
  global $Universal_Translation;

  $a = array("type" => $type);
 
  foreach($listOfTags as $tag)
  {
    $b = $Universal_Translation[$tag];
    $a[$b] = getTag($element, $tag);
  }
  return $a;
}

/**
 *  Extract the channel node
 *  Input: name of the tag (feed or channel)
 *  Ouput: the node
 */     

function extractChannel($tag)
{
  global $Universal_Doc;
  $channel = $Universal_Doc->getElementsByTagName($tag);
  return $channel->item(0);
}

/**
 *  Extract all items
 *  Input: the name of the tag
 *  Output: a DOMNodeList
 */    

function extractItems($dnl, $tag)
{
  global $Universal_Doc;
  $items = $Universal_Doc->getElementsByTagName($tag);
  return $items;
}

/**
 *  Default display routine
 *  Input:
 *  - size: the max number of items to display
 *  - chanflag: display the channel or not
 *  - descflag: display the description or not
 *  - dateflag: display the date or not
 *  - Universal_Style: name of the container, default is <p>
 *  - Universal_Data_Font: name of the font
 *  Output:
 *  - the formatted generated text
 */            

function Universal_Display($size = 15, $chanflag = false, $descflag = false, $dateflag = false)
{
  global $Universal_FeedArray;
  global $Universal_Style;
	global $Universal_Date_Font;

  $opened = false;
	$page = "";
	$counter = 0;

  if(count($Universal_FeedArray) == 0)
  {
    die("Error, nothing to display.");
  }

	foreach($Universal_FeedArray as $article)
	{
		$type = $article["type"];
	
		if($type == 0)
		{
			if($chanflag != true) continue;
			if($opened == true)
			{
				$page .="</ul>\n";
				$opened = false;
			}
			//$page .="<b>";
		}
		else
		{
		  if($counter++ >= $size)
      { 
        break;
      }  
			if($opened == false && $chanflag == true) 
			{
				$page .= "<ul>\n";
				$opened = true;
			}
		}
		$title = $article["title"];
		$link = $article["link"];
		$page .= "<".$Universal_Style."><a href=\"$link\">$title</a>";
		
		if($descflag != false)
		{
			$description = $article["description"];
			if($description != false)
			{
				$page .= "<br>$description";
			}
		}	
		if($dateflag != false)
		{			
			$updated = $article["updated"];
			if($updated != false)
			{
				$page .= "<br /><font $Universal_Date_Font>$updated</font>";
			}
		}	
		$page .= "</".$Universal_Style.">\n";			
		
		/*
		if($type == 0)
		{
			$page .="<br />";
		}
		*/
	}

	if($opened == true)
	{	
		$page .="</ul>\n";
	}
	return $page."\n";
}


/**
 *  Get the data out of a feed 
 *  - Input: the URL of the feed
 *  - Output: a two-dimensional array holding the data  
 */

function Universal_Reader($url)
{
  global $Universal_FeedArray;
	global $Universal_Content;
	global $Universal_Style;
	global $Universal_Date_Font;
	global $Universal_AtomChannelTags;
	global $Universal_RssChannelTags;
	global $Universal_AtomItemTags;
	global $Universal_RssItemTags;
	global $Universal_Doc;
	
	$Universal_FeedArray = array();

	$Universal_Doc  = new DOMDocument("1.0");
	$Universal_Doc->load($url);

	$Universal_Content = array();

	$channel = extractChannel("feed");
  $isAtom = ($channel != false);

  if($isAtom)
  {
    $channelArray = getTags($channel, $Universal_AtomChannelTags, 0);
    $items = extractItems($channel, "entry");
    $tagSchema = $Universal_AtomItemTags;
  }
  else
  {
    $channel = extractChannel("channel");
    $channelArray = getTags($channel, $Universal_RssChannelTags, 0);
    $items = extractItems($channel, "item");
    $tagSchema = $Universal_RssItemTags;
  }
  
  array_push($Universal_FeedArray, $channelArray);
  
  foreach($items as $item)
  {
     array_push($Universal_FeedArray, getTags($item, $tagSchema, 1));
  }  
  
 	return $Universal_FeedArray;
	
}


?>