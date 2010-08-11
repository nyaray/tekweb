<?php

class FacebookFeedReader {
  
  protected $g_id;
  protected $g_name;
  protected $g_pic;
  protected $g_link;
  
  protected $FeedArray;
  
  function Read_feed($url) {
    
    $json = getRemoteFile($url);
    $json_output = json_decode($json);
    
    //var_dump($json_output);
    
    foreach($json_output->data as $data) {
      //var_dump($data);
      date_default_timezone_set('Europe/Berlin');
      
      // if(isset($data->message)) {
      //         echo hej;
      //         $title = $data->message;
      //       }
      //       else {
      //         $title = "";
      //       }
      $i_array = array("type" => 1, 
                       "title" => $data->message,
                       "link" => "",
                       "description" => "",
                       "pubDate" => date(DateTime::RFC822,strtotime(substr($data->created_time,0,19))),
                       "updated" => date(DateTime::RFC822,strtotime(substr($data->updated_time,0,19))));
      array_push($this->FeedArray, $i_array);
    }
  }
  
  function Read($url) {
    
    $this->FeedArray = array();

    $json = getRemoteFile($url);
    $json_output = json_decode($json);

    $this->g_id = $json_output->id;
    $this->g_name = $json_output->name;
    $this->g_pic = $json_output->picture;
    $this->g_link = $json_output->link;

    //fill with header info
    $h_array = array("type" => 0, 
                     "title" => $this->g_name,
                     "link" => $this->g_link,
                     "description" => "",
                     "updated" => "");
    array_push($this->FeedArray, $h_array);
    
    //get feed
    $feed_url = $url."/feed";
    //read feed
    $this->Read_feed($feed_url);
   
    //var_dump($this->FeedArray);
    return $this->FeedArray; 
  }
}
?>