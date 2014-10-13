<!DOCTYPE php>
<?php

	$category = $_GET["c"];
	
	$imgURL = "http://slushie.me/Logos/slushie";
	if(!$category) {$imgURL .= "home";}
	else {$imgURL .= strtolower($category);}
	$imgURL .= ".png";
	
	$imgInfo=getimagesize($imgURL);
	$imgHeight = 90; //should match height on display.php, same for all categories
	$imgWidth = 90*($imgInfo[0]/$imgInfo[1]);
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
		<meta charset="windows-1252" />
		
		<title>Slushie :: No Articles in <?php echo $category; ?></title>
		<link rel="stylesheet" type="text/css" href="slushie.css" />
		<link rel="icon" href="http://slushie.me/Logos/slushieicon.ico" type="image/x-icon" /> 
		<link rel="shortcut icon" href="http://slushie.me/Logos/slushieicon.ico" type="image/x-icon" /> 
	
			<!-- This makes the navbar float -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
		<script src="http://slushie.me/floating_thingies.js"></script>
	
	</head>
	<body class="<? echo strtolower($category); ?>">
		<div class="header"; align="left";>
			<?php 
			//displays logo at top of page
			
			//opening <a> tag
			echo"<a href=\"http://";			
			if(strtolower($category)) {echo strtolower($category).".";} //makes sure "slushie.me" is displayed instead of ".slushie.me".
			echo "slushie.me\">";
			
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
		<h1>Uh Oh! There don't seem to be any articles in this category yet.</h1>
		<h3>If you'd like to write articles for the <?php echo $category; ?> category, you can apply 
		<a href="apply.php">here</a>. You can also <a href="suggestion-box.php">suggest</a> a topic for an article,
		 or send us whatever feedback you have. 
		 Lastly, feel free to <a href="mailto:contact@slushie.me">contact us</a> with messages, comments, or complaints.
		</h3>
		
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