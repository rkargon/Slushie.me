<?php
/*
	THIS PAGE IS DEPRECATED!
------------------------------------------------------
	
	Use display.php instead. The page was renamed so that we won't have to 
	change the url of dozens of links when we finally put the test site as the main one.
*/	

$ID=$_GET["articleID"]; //For redirect

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
		<title>Slushie<?php echo " - ".$title;?></title>
		<link rel="stylesheet" type="text/css" href="Slushie CSS main.css" />
		<link rel="icon" href="square_favicon.ico" type="image/x-icon" /> 
		<link rel="shortcut icon" href="square_favicon.ico" type="image/x-icon" /> 
	</head>
	<body> 
		<div class="header"; align="center"; bgcolor=999999>
			<a href="SQLTest.php"><img src="slushie_banner.png"; width=100%; align=center;/></a> 
			<br />
			
			<hr color=999999 height=1px /> 
			<div class="navbar">
			<a class="navbar"; href="http://www.slushie.me">Home</a>
			<a class="navbar"; href="http://news.slushie.me">News</a>
			<a class="navbar"; href="http://politics.slushie.me">Politics</a>
			<a class="navbar"; href="http://tech.slushie.me">Tech</a>
			<a class="navbar"; href="http://entertainment.slushie.me">Entertainment</a>
			<a class="navbar"; href="http://www.slushie.me/about.php">Contactify</a>
			<div>
			<hr color=999999 height=1px />
			
			<br />
		</div>
		<div class="container" style="display:block;">
			<h1> Were you looking for the Slushie Test display page? Try <?php echo "<a href=\"display.php?articleID=".$ID."\">display.php</a></h1>" ?>
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