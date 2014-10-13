<!DOCTYPE php>
<?php
	include $_SERVER['DOCUMENT_ROOT']."/category_functions.php";
	
	$num_per_page=10;

	//gets arguments
	$page_num = $_GET["p"];
	$author = $_GET["a"];
	$category = $_GET["c"];
	if($category == "all"){$category="";}
	
	mysql_connect(localhost, "slushie1", "BatteryHorseStaple5lu5h13.");
	@mysql_select_db("slushie1_wrd2") or die( "Unable to select database");

	//Get author displayname
	$author=mysql_real_escape_string($author); //check for special characters in input
	$query = "SELECT * FROM wp_users WHERE user_nicename='".$author."'"; 
	$result=mysql_query($query);
	$author_displayname = mysql_result($result, 0, "display_name");
	
	//check for issues with page number (less than 1, or not an int)
	$page_num = intval($page_num);
	if(!($page_num>0 and is_numeric($page_num))){
		$page_num = 1;	
	}
	
	$xml = simplexml_load_file("rss.xml");
	
	//get proper logo for category
	$imgURL = "http://slushie.me/Logos/slushie";
	if(!$category) {$imgURL .= "home";}
	else {$imgURL .= strtolower($category);}
	$imgURL .= ".png";
	
	//format to 90 pixels high, and maintain aspect ratio
	$imgInfo=getimagesize($imgURL);
	$imgHeight = 90; //should match height on display.php, same for all categories
	$imgWidth = 90*($imgInfo[0]/$imgInfo[1]);
	
	//filters list items for proper category
			$filtered_list=array();
			foreach ($xml->xpath('/rss/channel/item') as $item){
				$ns_sidebar = $item->children("http://slushie.me/sidebar");
				
				//checks each category of the item to see if it matches the page category
				$item_category_match = 0;
				foreach ($item->category as $item_category_temp){
					if(strtolower($item_category_temp) == strtolower($category) or !$category){
						$item_category_match = 1;
						break;
					}
				}
				if($item_category_match) { //category check
					if(($ns_sidebar->author_user == $author) or $author==""){ //author check
						array_push($filtered_list, $item);	
					}
				}
			}
			
	//get number of pages, articles
	$num_articles=sizeof($filtered_list);
	$num_pages = intval($num_articles/$num_per_page);
	if($num_articles % $num_per_page) {$num_pages++;}
	if($page_num>$num_pages){$page_num=$num_pages;}
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
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" />
		
		<title>Slushie :: View <?php echo $category; ?> Articles</title>
		<link rel="stylesheet" type="text/css" href="http://slushie.me/slushie.css" />
		<link rel="icon" href="http://slushie.me/square_favicon.ico" type="image/x-icon" /> 
		<link rel="shortcut icon" href="http://slushie.me/square_favicon.ico" type="image/x-icon" /> 
	
			<!-- This makes the navbar float -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
		<script src="http://slushie.me/floating_thingies.js"></script>
	
	</head>
	<body class="viewpage">
		<div class="header"; align="left";>
			<?php 
			//displays logo at top of page
			
			//opening <a> tag
			echo"<a href=\"http://slushie.me/".strtolower($category)."\">";
			
			//img tag
			echo "<img class=\"logo\"; src=\"".$imgURL."\"; ";			
			echo "height=".$imgHeight."px; width=".$imgWidth."px;/></a>";
			?>
			
			<div class="navbar <?php echo strtolower($category);?>">
				<!-- dropdown menu -->
				<ul class="menubar <?php echo strtolower($category);?>";>
					<li><img class="navbar_logo" src="http://slushie.me/Logos/slushieneutral.png" height=18px width=78px/></li>
					<li><a class="nomargin" href="http://slushie.me" >Front Page</a></li>
					<li><a href="http://slushie.me/news" >News</a></li>
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
	<div class="<?php echo strtolower($category);?>" style="width:90%; margin-left:5%; margin-right:5%; margin-bottom:5%;">
		<br />
		<h1>Latest <?php echo strtolower($category);?> content from Slushie<?php if($author){echo " by ".$author_displayname;} ?></h1>
		<hr />
		<?php
			$i=1;
			
			foreach ($filtered_list as $item) {
				if($i>$num_per_page*($page_num-1)){
					echo "<div class = \"sidebar_item\">\n";
					echo "<a href=\"".$item->link."\">\n"; //creates link
					$ns_sidebar = $item->children("http://slushie.me/sidebar");
					echo "<img class=\"thumbnail\" src=\"".$ns_sidebar->thumbnail."\" />\n";
					echo "<p class=\"nomargin\"; align=left>\n";
					echo "<b> ".$item->title."</b><br />\n"; //title
					
					$description = mb_convert_encoding($item->description, "windows-1252", "utf-8"); //formats description
					echo $description; //outputs description
					echo "</a>";//closes link
					
					if(!$category){
						//if item has is in 'entertainment' or 'uncategorized', display other categories, if any.
						foreach ($item->category as $item_category_temp){
							if(strtolower($item_category_temp) != "uncategorized" and strtolower($item_category_temp) != "entertainment"){
								$item_category_name=$item_category_temp;
								break;
							}
						}
						if(!$item_category_name){$item_category_name=$item->category;}
						echo "&nbsp;in <a class=\"".strtolower($item_category_name)."\" href=\"http://slushie.me/".strtolower($item_category_name)."\">".$item_category_name."</a></p>";
					}
					
					echo "\n<p class=\"nomargin\" align=right> <span class=\"timestamp\";> <i>".$item->pubdate."</i></span></p>\n";
					echo "</div><hr color=999999 style=\"border:none;\" /><br />\n";
					// For link if necessary &echo "Link: ".$channel_link."<br />";
				
					
				}
				$i++;
				if ($i>$num_per_page*$page_num){break;}
			}
		?>
		<br />
			<div class="page_numbers" align="center">
				<?php					
					
					if($page_num<=6){
						$page_startnum=1;
						$page_endnum = 11;
					}
					elseif ($page_num<=$num_pages-5) {
						$page_startnum = $page_num-5;
						$page_endnum = $page_num+5;
					}
					else {
						$page_startnum = $num_pages-10;
						$page_endnum = $num_pages;
					}
					
					if($page_endnum>$num_pages){$page_endnum=$num_pages;}
					
					echo "<!--page_num:".$page_num."\nstart:".$page_startnum."\nend:".$page_endnum."-->\n";
					echo "Page:     ";
					if($page_startnum>1){echo "&lt; ";}
					
					for($i=$page_startnum; $i<=$page_endnum; $i++){
					
						if($i==$page_num) {echo "<b>".$i."</b>\n";}//current page
						else {
							//other page
							echo "<a href=\"http://slushie.me/view/";
							
							//is category "all"?
							if($category){echo $category;}
							else {echo "all";}
							
							//page #
							echo "/".$i;
							
							//is there author?
							if($author){echo "a=".$author;}
							
							echo "\">".$i."</a>\n";
						}
						
					}
				
					if($page_endnum<$num_pages){echo " &gt;\n";}
				
				?>
			</div>
		</div>
<br />
<footer>
				&copy;2011 - 2013 Slushie
				<br />
				<a href="http://madewithloveinbaltimore.org">Made with &hearts; in Baltimore</a>
			</footer>
</body>
</html>
<!--

Copyright 2013 Slushie
Programmed by Raphael Kargon (rkargon@slushie.me)

-->