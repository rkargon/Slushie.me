<!DOCTYPE php>
<?php

/* Last edited: Raphael Kargon 6:37 PM 9/14/12. -- added specific logo height, so IE doesn't flip its shit--  [Change this so we know who edited last, and don't accidentally undo edits.]*/ 

//gets ID from url
$ID=$_GET["articleID"];

//accesses SQL table
mysql_connect(localhost, "slushie1", "BatteryHorseStaple5lu5h13.");
@mysql_select_db("slushie1_wrd2") or die( "Unable to select database");

//Gets correct article
$ID=mysql_real_escape_string($ID); //check for special characters in input
$query="SELECT * FROM wp_posts WHERE ID='".$ID."' AND post_status='publish' AND post_type='post'";
$result=mysql_query($query);
$num_results = mysql_num_rows($result); 

if($num_results == 0){
	$query="SELECT * FROM wp_posts WHERE post_status='publish' AND post_type='post' ORDER BY post_date DESC";
}

$result=mysql_query($query);

//loads date, title, user ID, and content
$title=mysql_result($result,0,"post_title");
$authorID=mysql_result($result,0,"post_author");
$content=mysql_result($result,0,"post_content");

//loads author name
$author_query="SELECT * FROM wp_users WHERE ID='".$authorID."'";
$author_result=mysql_query($author_query);
$author=mysql_result($author_result, 0, "display_name");
$author_link="http://www.slushie.me/author/".mysql_result($author_result, 0, "user_nicename");

// Format content if necessary
$content_formatted=preg_replace("/\n/", "<br />\n", $content); //turn newlines into actual <br>s

// Replace Youtube URLs with embed code (unless it's already embedded!)
$search = '#http[s]?:\/\/(?:[^\.]+\.)*youtu\.be\/([\w\-\_]+)#ixs';
$replace = '<center><iframe width="560" height="315" src="http://www.youtube.com/embed/$1?wmode=transparent" frameborder="0" allowfullscreen></iframe></center>';
$content_formatted = preg_replace($search, $replace, $content_formatted);

$postdate=mysql_result($result,0,"post_date");
$unix_postdate=strtotime($postdate);
$unix_today=time();

if($unix_postdate<$unix_today-86400){
	$display_date=" on " . date("F d, Y", $unix_postdate) . " at " . date("g:i a", $unix_postdate) . "\n";
}
else{
	$display_date=round(($unix_today-$unix_postdate)/3600) . " hours ago\n";
}
//loads rss feed
$xml = simplexml_load_file("rss.xml");


?>

<html>
<head>
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
		<!-- text encoding -->
		<meta charset="windows-1252" /> 
		
		<title>Slushie<?php echo " :: ".$title;?></title>
		<link rel="stylesheet" type="text/css" href="slushie.css" />
		<link rel="icon" href="http://slushie.me/Logos/slushieicon.ico" type="image/x-icon" /> 
		<link rel="shortcut icon" href="http://slushie.me/Logos/slushieicon.ico" type="image/x-icon" /> 
		
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
		<script src="http://slushie.me/floating_thingies.js"></script> <!-- This makes the sidebar float -->
		<script>
		function showArticles(query) {
			$.ajax({
				url:"search_articles.php?q=" + query + "&t=" + Math.random()}).done(function(data){$("#sdbr").html(data)})
		}
		
		</script>

	<!--	<script type="text/javascript">

		var _gaq = _gaq || [];
	 	_gaq.push(['_setAccount', 'UA-34074554-1']);
 		_gaq.push(['_setDomainName', 'slushie.me']);
		_gaq.push(['_trackPageview']);

  		(function() {
  	 	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
   		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  		})();

		</script>  -->
		
	</head>
	<body> 

		<div class="header"; align="left";>
			<a href="http://slushie.me"><img class="logo"; src="http://slushie.me/Logos/slushiehome.png"; height=90px; width=391px;/></a> 
			
			<div class="navbar">
				<!-- dropdown menu -->
				<ul class="menubar";>
					<li><img class="navbar_logo" src="http://slushie.me/Logos/slushieneutral.png" height=18px width=78px/></li>
					<li><a class="nomargin" href="http://slushie.me/news" >News</a></li>
					<li><a href="http://slushie.me/politics">Politics</a></li>
					<li><a href="http://slushie.me/tech" >Tech</a></li>
					<li>
						<span style="margin-left:40px; font-size:120%; color:#ffffff;">Entertainment</span>
						<ul>
							<li><a href="http://slushie.me/movies">Movies</a></li>
							<li><a href="http://slushie.me/music" >Music</a></li>
							<li><a href="http://slushie.me/gaming">Gaming</a></li>
							<li><a href="http://slushie.me/sports/">Sports</a></li>
							 <li><a href="http://slushie.me/tv/">TV</a></li>
						</ul>
					</li>
					<li>
						
					</li>

				</ul>
				
			</div>

		<br />
		</div>
		<div class="container" style="display:block;">
		
		<!-- actual article -->
		<div class="content";>
			<h1> <?php echo $title; ?> </h1>
			<span class="postinfo";> by <?php echo "<a href=\"".$author_link."\">".$author."</a>";?> <?php echo $display_date; ?></span>
			<p><?php echo $content_formatted . "<br /><br />"; ?></p>
			</div>
		

		 <div class="sidebar_wrapper">
		 <div class="sidebar_title";>
			<a href="view.php"><b>The Latest:</b></a>
			<form style="display:inline;">
				<input type="text" style="display:inline;" onkeyup="showArticles(this.value)"/>
			</form>
			<hr width="100%" />
		 </div>
		<div class="sidebar" id="sdbr">
			<?php
				$i=0;
				foreach ($xml->xpath('/rss/channel/item') as $item) {
					$i++;
					echo "<div class = \"sidebar_item\">\n";
					echo "<a href=\"".$item->link."\">"; //creates link
					$ns_sidebar = $item->children("http://slushie.me/sidebar");
					echo "<img class=\"thumbnail\" src=\"".$ns_sidebar->thumbnail."\" />";
					
					echo "<p class=\"nomargin\"; align=left> ".$item->title."</p>\n"; //title
					
					//$description = mb_convert_encoding($item->description, "windows-1252", "utf-8"); //formats description
					//echo $description; //outputs description

					$rss_pubdate = $item->pubDate;
					$unix_pubdate=strtotime($rss_pubdate);

					if($rss_pubdate<$unix_today-86400){
						$display_date_sidebar=date("F d, Y", $unix_pubdate) . "\n";
					}
					else{
						$display_date_sidebar=round(($unix_today-$unix_pubdate)/3600) . " hours ago\n";
					}

					echo "\n<p class=\"nomargin\" align=right> <span class=\"timestamp\";> ".$display_date_sidebar."</span></p>\n";
					echo "</a></div><hr />\n";
					// For link if necessary &echo "Link: ".$channel_link."<br />";
					if ($i>10){break;}
				}
			?>
		</div>
		</div>
		</div>
<br />
<footer>
				&copy;2011 - 2013 Slushie
<br />
<a href="http://slushie.me/about.php">About</a> | <a href="http://slushie.me/apply.php" >Apply</a> | <a href="http://slushie.me/suggestion-box.php" >Suggestion Box</a>
<br /><br />

				<a href="http://madewithloveinbaltimore.org">Made with &hearts; in Baltimore</a>
			</footer>
</body>
</html>
<!--

Copyright 2013 Slushie
Programmed by Raphael Kargon (rkargon@slushie.me)

-->