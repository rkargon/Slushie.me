<?php

//gets ID from url
$username=$_GET["i"];
$username=preg_replace("/\//", "", $username);

//accesses SQL table
mysql_connect(localhost, "slushie1", "BatteryHorseStaple5lu5h13.");
@mysql_select_db("slushie1_wrd2") or die( "Unable to select database");

//Loads author info
$username=mysql_real_escape_string($username); //check for special characters in input
$query = "SELECT * FROM wp_users WHERE user_nicename='".$username."'"; 
$result=mysql_query($query);

if(mysql_num_rows($result)==0)
{
	header( 'Location: http://slushie.me/404.php' ) ;
	exit();
}

$user_email = mysql_result($result, 0, "user_email");
$user_registered = mysql_result($result, 0, "user_registered");
$displayname = mysql_result($result, 0, "display_name");

//use ID to get meta info from SQL
$id = mysql_result($result, 0, "ID");
$metainf_query = "SELECT * FROM wp_usermeta WHERE user_id='".$id."'";
$user_metainf_result = mysql_query($metainf_query);

//get profile picture URL, or use default image. 
if(file_exists("wp-content/uploads/userphoto/".$id.".png")){$userphoto_url="http://slushie.me/wp-content/uploads/userphoto/".$id.".png";}
else {$userphoto_url = "http://slushie.me/default_userphoto.png";}

//populate hash  or "array", as PHP calls it, with user info
$user_meta_array = array('ID' => $id);
for($i=0; $i<mysql_num_rows($user_metainf_result); $i++){
	$user_meta_array[mysql_result($user_metainf_result, $i, "meta_key")] = mysql_result($user_metainf_result, $i, "meta_value");
}

//get bio
$bio = $user_meta_array['description'];
$bio = preg_replace("/\n/", "<br />", $bio);
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
		
		<title>Slushie<?php echo " :: ".$displayname;?></title>
		<link rel="stylesheet" type="text/css" href="http://slushie.me/slushie.css" />
		<link rel="icon" href="http://slushie.me/square_favicon.ico" type="image/x-icon" /> 
		<link rel="shortcut icon" href="http://slushie.me/square_favicon.ico" type="image/x-icon" /> 
		
		<!-- This makes the sidebar float -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
		<script src="http://slushie.me/floating_thingies.js"></script>

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
			<h1> <?php echo $displayname; ?> </h1>
			<img style="float:right; margin-left:20px; width:150px;" src="<?php echo $userphoto_url; ?>" />
			<b>Email: </b><?php echo "<a href=\"mailto:".$user_email."\">".$user_email."</a>"; ?><br /><br />
			<b>Bio:</b><br /><?php echo $bio; ?><br /><br />
		</div>
		 <div class="sidebar_wrapper">
		 <div class="sidebar_title";>
			<a href="http://slushie.me/view/all/a=<?php echo $username; ?>"><b>Articles by <?php echo $displayname;?>:</b></a>
			<a href="http://slushie.me/rss.xml"><img align="right" style="margin-right:5px;" src="http://slushie.me/Logos/rss/rss_main.png" width="20px" height="20px" onmouseover="this.src='http://slushie.me/Logos/rss/rss_main_hover.png'" onmouseout="this.src='http://slushie.me/Logos/rss/rss_main.png'"/></a>
			<hr width="100%" />
		 </div>
		<div class="sidebar";>
			<?php
				$i=0;
				foreach ($xml->xpath('/rss/channel/item') as $item) {
					$ns_sidebar = $item->children("http://slushie.me/sidebar");
					if($ns_sidebar->author_user == $username){
						$i++;
						echo "<div class = \"sidebar_item\">\n";
						echo "<a href=\"".$item->link."\">"; //creates link
						echo "<img class=\"thumbnail\" src=\"".$ns_sidebar->thumbnail."\" />";
						
						echo "<p class=\"nomargin\"; align=left> ".$item->title."</p>\n"; //title
						
						//$description = mb_convert_encoding($item->description, "windows-1252", "utf-8"); //formats description
						//echo $description; //outputs description
	
						$unix_today = time();
	
						$rss_pubdate = $item->pubDate;
						$unix_pubdate=strtotime($rss_pubdate);
	
						if($rss_pubdate<$unix_today-86400){
							$display_date_sidebar=date("F d, Y", $unix_pubdate) . "\n";
						}
						else{
							$display_date_sidebar=round(($unix_today-$unix_pubdate)/3600) . " hours ago\n";
						}
	
						echo "\n<p class=\"nomargin\" align=right>";
						
						foreach ($item->category as $item_category_temp){
							if(strtolower($item_category_temp) != "uncategorized" and strtolower($item_category_temp) != "entertainment"){
								$item_category_name=$item_category_temp;
								break;
							}
						}
						if(!$item_category_name){$item_category_name=$item->category;}
						echo "<br /><a class=\"".strtolower($item_category_name)."\" href=\"http://slushie.me/".strtolower($item_category_name)."\">".$item_category_name."</a><br />";
						
						echo "<span class=\"timestamp\";> ".$display_date_sidebar."</span></p>\n";
						echo "</a></div><hr />\n";
						// For link if necessary &echo "Link: ".$channel_link."<br />";
						if ($i>10){break;}
					}
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