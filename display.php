<?php

 include $_SERVER['DOCUMENT_ROOT']."/category_functions.php";

 function display_slushie_article_sidebar ($article_node, $page_category_id, $page_category)
 {
	 $title = $article_node->title;

	 $publication_date = $article_node->pubDate;
	 $unix_pubdate=strtotime($publication_date);
	 $unix_today=time();
	 if($publication_date<$unix_today-86400){$display_date_sidebar=date("F d, Y", $unix_pubdate) . "\n";}
	 else{$display_date_sidebar=round(($unix_today-$unix_pubdate)/3600) . " hours ago\n";}

	 $link = $article_node->link;
	 $image_link = $article_node->children("http://slushie.me/sidebar")->thumbnail;
	 $id = $article_node->children("http://slushie.me/sidebar")->id;
	 echo "<a href=\"http://slushie.me". ($page_category_id!=0 ? "/".$page_category : "")."/".$id."\">\n"; //creates link
	 echo "<img class=\"thumbnail\" src=\"".$image_link."\" alt=\"[Thumbnail]\"/>\n";
	 echo "<p class=\"nomargin\"; align=left> ".$article_node->title."</p>\n"; //title
	 echo "</a>";
	 echo "\n<p class=\"nomargin\" align=right>";
	 if(!$page_category_id){
		 foreach ($article_node->category as $item_category_temp){
			 if(strtolower($item_category_temp) != "uncategorized"){
				 $item_category_name=$item_category_temp;
				 break;
			 }
		 }
		 if(!$item_category_name){$item_category_name=$article_node->category;}
		 echo "<br /><a class=\"".strtolower($item_category_name)."\" href=\"http://slushie.me/".strtolower($item_category_name)."\">".$item_category_name."</a><br />";

	 }
	 echo " <span class=\"timestamp\";> ".$display_date_sidebar."</span></p>\n";

 }

 function display_gamerreport_sidebar ($article_node)
 {
	 $title = $article_node->title;

	 $publication_date = $article_node->published;
	 $unix_pubdate=strtotime($publication_date);
	 $unix_today=time();
	 if($publication_date<$unix_today-86400){$display_date_sidebar=date("F d, Y", $unix_pubdate) . "\n";}
	 else{$display_date_sidebar=round(($unix_today-$unix_pubdate)/3600) . " hours ago\n";}

	 $links = $article_node->link;
	 foreach($links as $link_indiv){
	   if ($link_indiv->attributes()->rel = "self"){
	     $gr_link = $link_indiv->attributes()->href;
	   }
	 }
	 $image_link = $article_node->children('http://search.yahoo.com/mrss/')->thumbnail->attributes()->url;

	 echo "<a href=\"".$gr_link."\">\n";
	 echo "\t<img class=\"thumbnail\" src=\"".$image_link."\"/>\n";
	 echo "\t<p class=\"nomargin\"; align=left> ".$title."</p>\n"; 
	 echo "</a>";
	 echo "\n<br /><p class=\"nomargin\" align=right>\n\t <a class=\"grlink\" href=\"http://gamerreport.net\">Gamer Report Exclusive!</a><br />\n";
	 echo " \n\t<span class=\"timestamp\";> ".$display_date_sidebar."</span>\n</p>\n";
 }

 function EvalShortCodes($content)
 {

	 //get rid of [caption]stuff[/caption], apply appropriate formatting
	 $caption_search = "/\[caption(.*?)\](.*?)([^<>]*)\[\/caption\]/is";
	 $caption_replace = "<span class=\"caption\">$2<br />$3</span>";
	 $content = preg_replace($caption_search, $caption_replace, $content);

	 return $content;
 }

 //accesses SQL database
 mysql_connect("localhost", "slushie1", "BatteryHorseStaple5lu5h13.");
 @mysql_select_db("slushie1_wrd2") or die( "Unable to select database");

 //gets ID from url
 $ID=$_GET["id"];
 $ID=mysql_real_escape_string($ID); //check for special characters in input
 $page_category = $_GET["c"];
 $page_category_id = getCategoryID($page_category);
 if($page_category_id<0){
	 header('Location: http://slushie.me/404.php');
	 exit();
 }


 //check if article matches the category. If there is a category (not the main page) and the 
 // article doesn't match, redirect to that article on the main page.
 if($ID != FALSE){
	 if(!checkCategory($page_category_id, $ID) and $page_category_id!=0){
		 header( 'Location: http://slushie.me/'.$ID);
		 exit();
	 }
 }

 //Checks for article with corresponding ID in database
 $query="SELECT * FROM wp_posts WHERE ID='".$ID."' AND post_status='publish' AND post_type='post'";
 $result=mysql_query($query);
 $num_results = mysql_num_rows($result); 
 $article_exists = 1;

 //if id points to nowhere, post most recent article in category
 if($num_results == 0){
	 $article_exists=0;
	 $query="SELECT * FROM wp_posts WHERE post_status='publish' AND post_type='post' ORDER BY post_date DESC";
	 $result=mysql_query($query);
	 for($i=0; $i<mysql_num_rows($result)-1; $i++){
		 $ID = mysql_result($result, $i, "ID");
		 if(checkCategory($page_category_id, $ID) or $page_category_id==0){
			 $article_exists=1;
			 break;
		 }
	 }
 }

 //if no articles in this category
 if($article_exists==0)
 {
	 header( 'Location: http://slushie.me/no_articles.php?c='.$page_category ) ;
	 exit();
 }

 //After correct article has been chosen, load array for category IDs for that article and 
 //find corresponding names. This is mainly for the label at the top of the article, "posted in [cat1], [cat2], etc..."
 $category_ids_array = getCategories($ID);
 for ($j=0; $j<count($category_ids_array); $j++){
	 $category_names_array[$j] = getCategoryName($category_ids_array[$j]);
 }

 //loads date, title, user ID, and content
 $title=mysql_result($result,$i,"post_title");
 $authorID=mysql_result($result,$i,"post_author");
 $content=mysql_result($result,$i,"post_content");

 //loads author name
 $author_query="SELECT * FROM wp_users WHERE ID='".$authorID."'";
 $author_result=mysql_query($author_query);
 $author=mysql_result($author_result, 0, "display_name");
 $author_link="http://www.slushie.me/author/".mysql_result($author_result, 0, "user_nicename");

 // Format content if necessary
 $content_formatted=preg_replace("/\n/", "<br />\n", $content); //turn newlines into actual <br>s

 // Replace Youtube URLs with embed code (unless it's already embedded!)
 $search = '/http[s]?:\/\/(?:[^\.]+\.)*youtu\.be\/([\w\-\_]+)/ixs';
 $replace = '<iframe width="560" height="315" src="http://www.youtube.com/embed/$1?wmode=transparent" frameborder="0" allowfullscreen="true"></iframe>';
 $content_formatted = preg_replace($search, $replace, $content_formatted);

// add encapsulating div (separate from first regex so it can detect existing iframe tags.) Also fixes <p> tag issue, divs can't nest inside <p>
 $search = '/(<iframe.*?<\/iframe>)/ixs';
 $replace = '</p><div style="width:560px; height:315px; margin:auto;">$1</div><p>';
 $content_formatted = preg_replace($search, $replace, $content_formatted);

 //get rid of shortcodes
 $content_formatted = EvalShortCodes($content_formatted);

 $postdate=mysql_result($result,$i,"post_date");
 $unix_postdate=strtotime($postdate);
 $unix_today=time();

 if($unix_postdate<$unix_today-86400){
	 $display_date=" on " . date("F d, Y", $unix_postdate) . " at " . date("g:i a", $unix_postdate) . "\n";
 } else{
	 $display_date=round(($unix_today-$unix_postdate)/3600) . " hours ago\n";
 }

 //get proper logo for category
 $imgURL = "http://slushie.me/Logos/";
 if(!$page_category_id) {$imgURL .= "default";}
 else {$imgURL .= strtolower($page_category);}
 $imgURL .= ".png";

 //format to 100 pixels high, and maintain aspect ratio
 $imgInfo=getimagesize($imgURL);
 $imgHeight = 100; //should match height on display.php, same for all categories
 $imgWidth = 100*($imgInfo[0]/$imgInfo[1]);

 //loads rss feed
 $xml = simplexml_load_file("http://slushie.me/rss.xml");
 $gamerreport_rss = simplexml_load_file('http://www.gamerreport.net/feeds/posts/default');

 ?>
 
 <!DOCTYPE php>
 <html>

<head>
	
		 <meta http-equiv="Content-Type" content="text/html; charset=windows-1252" /> 

		 <title>Slushie<?php echo " ".ucfirst($page_category)." :: ".$title;?></title>
		 <link rel="stylesheet" type="text/css" href="http://www.slushie.me/slushie.css" />
		 <link rel="icon" href="http://slushie.me/Logos/slushieicon.ico" type="image/x-icon" /> 
		 <link rel="shortcut icon" href="http://slushie.me/square_favicon.ico" type="image/x-icon" /> 

		 <script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
		 <script src="http://slushie.me/floating_thingies.js"></script>

	 <script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-34074554-1']);
		_gaq.push(['_trackPageview']);
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
		

	 </head>
	 <body class="<?php echo strtolower($page_category); ?>"> 

		 <div class="header <?php echo strtolower($page_category); ?>" align="left">
			 <?php 
			 //displays logo at top of page
			 //opening <a> tag
			 echo"<a href=\"http://slushie.me/";			
			 if(strtolower($page_category)) {echo strtolower($page_category);}
			 echo "\">";
				 //img tag
				 echo "<img class=\"logo\"; src=\"".$imgURL."\"; ";			
				 echo "height=\"".$imgHeight."px;\" width=\"".$imgWidth."px;\"";
				 echo " alt=\"Slushie.me ".$page_category."\"/>";
			 echo "</a>";
			 ?> 

		<!-- HEADER AD (missing at the moment)--> 
		
			 <div class="navbar <?php echo strtolower($page_category); ?>">
				 <!-- dropdown <?php echo strtolower($page_category); ?> -->
				 <ul class="menubar <?php echo strtolower($page_category); ?>";>
					 <li>
					 	<?php
					 	if($page_category_id){echo "<span style=\"font-family:HelveticaNeue-medium, Helvetica Neue, Helvetica; font-size:120%; color:#ffffff; float:left; margin-left: 10px;\">".strtoupper($page_category)	."</span>";}
					 	else {echo '<img class="navbar_logo" src="http://slushie.me/Logos/slushieneutral.png" height=18px width=78px alt="Slushie"/>';}
					 	?>
					 </li>
					 <?php if($page_category_id){echo "<li><a class=\"nomargin\" href=\"http://www.slushie.me\" >Front Page</a></li>"; }?>
					 <li><a <?php if(!$page_category_id){echo "class=\"nomargin\"";}?> href="http://slushie.me/news/">News</a></li>
					 <li><a href="http://slushie.me/politics/">Politics</a></li>
					 <li><a href="http://slushie.me/tech/">Tech</a></li>
					 <li>
						 <span style="margin-left:40px; font-size:120%; color:#ffffff;">Entertainment</span>
						 <ul>
							 <li><a href="http://slushie.me/movies/">Movies</a></li>
							 <li><a href="http://slushie.me/music/">Music</a></li>
							 <li><a href="http://slushie.me/gaming">Gaming</a></li>
							 <li><a href="http://slushie.me/sports/">Sports</a></li>
							 <li><a href="http://slushie.me/tv/">TV</a></li>
						 </ul>
					 </li>
				 </ul>

			 </div>

		 <br />
		 </div>
		 <div class="container" style="display:block;">

		 <!-- actual article -->
		 <div class="content">
			 <h1> <?php echo $title; ?> </h1>

			 <span class="postinfo"> by 
			 <?php // "By [AUTHOR] on [DATE] in [CATEGORY] 
			echo "<a href=\"".$author_link."\">".$author."</a> ";
			echo $display_date;
			echo "<br />\n";
			for( $j=0; $j<count($category_names_array); $j++){
				if ($j>0){echo " | ";}
				echo "<a href=\"http://slushie.me/".strtolower($category_names_array[$j])."\">".$category_names_array[$j]."</a>";
			}
			?>
			</span>
			
			<!-- article content -->
			<p><?php echo $content_formatted . "<br /><br />"; ?></p>
			
			<span class="adtitle">Advertisement:</span>
			<hr />
			<div class="footerad_468">
				<script type="text/javascript"><!--
				google_ad_client = "ca-pub-1780827839732556";
				/* Smaller footer ad */
				google_ad_slot = "4220357848";
				google_ad_width = 468;
				google_ad_height = 60;
				//-->
				</script>
				<script type="text/javascript"
				src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
				</script>
			</div>
			<hr />
			<br />
			<footer>
				&copy;2011 - 2013 Slushie
				<br />
				<a href="http://slushie.me/about.php">About</a> | <a href="http://slushie.me/apply.php" >Apply</a> | <a href="http://slushie.me/suggestion-box.php" >Suggestion Box</a>
				<br /><br />
				<a href="http://madewithloveinbaltimore.org">Made with &hearts; in Baltimore</a>
			</footer>
			</div>
		

		 <div class="sidebar_wrapper <?php echo strtolower($page_category); ?>">
		 	<div class="sidebar_title">
				<a class="<?php echo ($page_category_id!=0 ? $page_category : "sidebar_gray");?>" href="http://www.slushie.me/view/<?php echo ($page_category_id!=0 ? $page_category : "all"); ?>/"><b>The Latest<?php echo ($page_category_id ? " in ".ucfirst($page_category) : ""); ?>:</b></a>
				<a href="http://slushie.me/rss.xml"><img align="right" style="margin-right:5px;" src="http://slushie.me/Logos/rss/rss_<?php echo ($page_category_id ? strtolower($page_category) : "main"); ?>.png" width="20px" height="20px" alt="RSS" onmouseover="this.src='http://slushie.me/Logos/rss/rss_<?php echo ($page_category_id ? strtolower($page_category) : "main"); ?>_hover.png'" onmouseout="this.src='http://slushie.me/Logos/rss/rss_<?php echo ($page_category_id ? strtolower($page_category) : "main"); ?>.png'"/></a>
				<hr height="1px" width="100%" />
			 </div>
			<div class="sidebar">
			<?php
				$i=0;
				$slushie_array = $xml->xpath('/rss/channel/item');
				$gamerreport_array = $gamerreport_rss->entry;
				$gr_index=0;
				$slushie_index = 0;
				while(($i<= 10) and ($slushie_index<sizeof($slushie_array) or $gr_index<sizeof($gamerreport_array))){
					if(strtotime($slushie_array[$slushie_index]->pubDate) < strtotime($gamerreport_array[$gr_index]->published)){
						if($page_category_id==0 or $page_category_id == 12 ){
							echo "<div class = \"sidebar_item\">\n";
							display_gamerreport_sidebar($gamerreport_array[$gr_index]);
							echo "</div><hr />\n";
							$i++;
						}
						$gr_index++;
					}
					else{
						$item_category_match = 0;
						foreach ($slushie_array[$slushie_index]->category as $item_category_temp){
							if(strtolower($item_category_temp) == strtolower($page_category) or !$page_category){
								$item_category_match = 1;
								break;
							}
						}
						if($item_category_match){
							echo "<div class = \"sidebar_item\">\n";
							display_slushie_article_sidebar($slushie_array[$slushie_index], $page_category_id, $page_category);
							echo "</div><hr />\n";
							$i++;
						}
						$slushie_index++;
					}
				
				}
				if($i==11){
					echo "<a class=\"".($page_category_id!=0 ? strtolower($page_category) : "sidebar_gray")."\" href=\"http://www.slushie.me/view/".($page_category_id!=0 ? $page_category : "all"). "/\"><b>More...</b></a>";
				}
			?>
		</div>
		</div>
		</div>

</body>
</html>
<!--

Copyright 2013 Slushie
Programmers:
 - Raphael Kargon (rkargon@slushie.me)
 - Evan Smith (esmith@slushie.me)

-->