<!DOCTYPE php>
<?php 
include realpath("category_functions.php");

//connects to SQL database
$pw = file_get_contents("../passwd.txt")
mysql_connect("localhost", "slushie1", $pw);
mysql_select_db("slushie1_wrd2") or die( "Unable to select database");

//finds all posts with status "publish" and type "post". Basically, all valid articles.
$query="SELECT * FROM wp_posts WHERE post_status='publish' AND post_type='post' ORDER BY post_date DESC";
$posts=mysql_query($query);//stores results of query in $posts
echo "loaded sql\n";

$rssText .= "<?xml version=\"1.0\" encoding=\"windows-1252\" ?>\n";
$rssText .= "<rss version=\"2.0\"";
$rssText .= " xmlns:sidebar=\"http://slushie.me/sidebar\"";
$rssText .= " xmlns:atom=\"http://www.w3.org/2005/Atom\">\n";
$rssText .= "<channel>\n<title>Slushie.me</title>\n<link>http://www.slushie.me/display.php</link>\n";
$rssText .= "<atom:link href=\"http://slushie.me/rss.xml\" rel=\"self\" type=\"application/rss+xml\" />\n";
$rssText .= "<language>en-us</language>\n";
$rssText .= "<description>The Flavor You Will Never Forget</description>\n";

//for each article
for ($i=0; $i<mysql_num_rows($posts); $i++)
  {
   
   	$ID = mysql_result($posts, $i, "ID"); 
    $title = preg_replace("/&/", "&amp;", mysql_result($posts, $i, "post_title")); //title, w/o ampersands
    $link = "http://www.slushie.me/".mysql_result($posts, $i, "ID");//URL
    
    //get post's post date, properly formatted.
    $pubdate = mysql_result($posts, $i, "post_date"); //publication date
    $unix_postdate=strtotime($pubdate);
    $pubdate_formatted = date("r", $unix_postdate); //use RSS format for date
    
    //get post content
    $content_unformatted = mysql_result($posts, $i, "post_content");
    $content=preg_replace("/<.*?>/", "", $content_unformatted); //take out tags, we just want words
    preg_match("/^.*?([\S]+\s+){50}/s", $content, $matches); //summary is first 50 words
    $description=$matches[0] . "..."; //add ellipsis
    
    //get post image, resize to thumbnail
    
    //first try post's featured image.
    $does_thumb_exist=glob("thumbnails/".$ID."_thumb.*");
    if(empty($does_thumb_exist)){
    	$post_meta_result = mysql_query("SELECT * FROM wp_postmeta WHERE post_id = ".$ID." AND meta_key='_thumbnail_id'"); //go to meta table, look for that post's thumbnail entry
    	$thumbnail_id = mysql_result($post_meta_result, 0, "meta_value"); //meta thumbnail entry points to post ID of thumb image
    	$thumbnail = mysql_result(mysql_query("SELECT * FROM wp_posts WHERE ID = '".$thumbnail_id."'"), 0, "guid");//go to wp_posts, look for thumbnail image, get guid (which has URL)
    	$thumbnail_local_address = preg_replace("/http:\/\/www\.slushie\.me\//", "", $thumbnail);//get the local address, so that the update script on the server can look at the file (different from URL)
    	echo "featured img: ".$thumbnail_local_address."\n";
    	//if no featured image, take first image in article
    	if(!file_exists($thumbnail_local_address)){ //checks if featured image actually exists, using local address
    		preg_match("/<img (.*?) src=\"(.*?)\"(.*?)\/>/", $content_unformatted, $img_matches);
    		$thumbnail=$img_matches[2];
    		//if no images in article, use default thumb
    		if(!$thumbnail){
    			$thumbnail = "http://www.slushie.me/default_thumbnail.png"; //If no image in article, set to default thumbnail
    		}
    	}
    	### RESIZE IMAGE ###
    	try {$thumbnail_image = new Imagick($thumbnail);}
    	//in case the image cannot be opened
    	catch (ImagickException $e) {
    		echo "\n Could not get image ".$thumbnail.", using default thumbnail\n". $e->getMessage() ."\n";
    		$thumbnail = "http://www.slushie.me/default_thumbnail.png";
    		$thumbnail_image = new Imagick($thumbnail);
    	}
    	//$img->setResourceLimit(6, 1); // 6 means "limit threads to", appears to fix segfault
		$thumbnail_image -> thumbnailImage(200, 0);
		$thumbnail_image -> writeImage("thumbnails/".$ID."_thumb.".$thumbnail_image->getFormat());
	}
	else{
		$thumbs_array=glob("thumbnails/".$ID."_thumb.*");
		$thumbnail = $thumbs_array[0];
		$thumbnail = "http://slushie.me/".$thumbnail;
	}
    
    //Get author of post
    $authorID = mysql_result($posts, $i,"post_author"); //get author ID
	$author_query="SELECT * FROM wp_users WHERE ID='".$authorID."'"; // get author based on ID
	$author_result=mysql_query($author_query);
	$author_email=mysql_result($author_result, 0, "user_email");
	$author_user=mysql_result($author_result, 0, "user_nicename");
	$author_name=mysql_result($author_result, 0, "display_name");
    
    //get categories
    $category_names_array = array();
    $category_ids_array = array();
    $category_ids_array = getCategories($ID);
	for ($j=0; $j<count($category_ids_array); $j++){
		$category_names_array[$j] = getCategoryName($category_ids_array[$j]);
	}
    
    $rssText .= "<item>\n";
    $rssText .= "<title>".$title."</title>\n";    
    $rssText .= "<link>".$link."</link>\n";
    $rssText .= "<pubDate>".$pubdate_formatted."</pubDate>\n";
    $rssText .= "<description>".$description."</description>\n";
    $rssText .= "<author>".$author_email." (".$author_name.")</author>\n";
    $rssText .= "<sidebar:author_name>".$author_name."</sidebar:author_name>\n";
    $rssText .= "<sidebar:author_user>".$author_user."</sidebar:author_user>\n";
    $rssText .= "<sidebar:id>".$ID."</sidebar:id>\n";
    for ($j=0; $j<count($category_names_array); $j++){
    	$rssText .= "<category domain=\"http://www.slushie.me\">".$category_names_array[$j]."</category>\n";
    }
    $rssText .= "<sidebar:thumbnail>".$thumbnail."</sidebar:thumbnail>\n";
    $rssText .= "<guid>http://slushie.me/".$ID."</guid>\n";
    $rssText .= "</item>\n";
    
    //display results
    echo "Title: ".$title."\n";
    echo "Link: ".$link."\n";
    echo "Pubdate: ".$pubdate."";
    echo "Description: ".$description."\n";
    echo "Author email: ".$author_email." (".$author_name.")\n";
    echo "Author link: http://www.slushie.me/user/".$author_user."\n";
    foreach ($category_names_array as $category_name){
    	echo "Category: ".$category_name."\n";
    }
    echo "Thumbnail: ".$thumbnail."\n\n";
    
    
    
  }


$rssText .= "</channel>\n</rss>";

$fh = fopen("rss.xml", 'w');

fwrite($fh, $rssText);

fclose($fh);


?>
<!--

Copyright 2013 Slushie
Programmed by Raphael Kargon (rkargon@slushie.me)

-->
