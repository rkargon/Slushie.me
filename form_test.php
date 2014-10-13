<!DOCTYPE php>
<?php ?>
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
		<meta http-equiv="Content-Type" content="text/html; charset=windows-1252" /> 
		
		<title>Slushie :: Forms</title>
		<link rel="stylesheet" type="text/css" href="slushie.css" />
		<link rel="icon" href="square_favicon.ico" type="image/x-icon" /> 
		<link rel="shortcut icon" href="square_favicon.ico" type="image/x-icon" /> 
		
					<!-- This makes the navbar float -->
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.js"></script>
		<script src="http://slushie.me/floating_thingies.js"></script>
		
	</head>
	<body> 

		<div class="header"; align="left";>
			<a href="http://slushie.me"><img class="logo"; src="http://slushie.me/Logos/slushiehome.png"; height=90px; width=391px;/></a> 
			
			<div class="navbar">
				<!-- dropdown menu -->
				<ul class="menubar";>
					<li><img class="navbar_logo" src="http://slushie.me/Logos/slushieneutral.png" height=18px width=78px/></li>
					<li><a class="nomargin" href="http://www.slushie.me" >Front Page</a></li>
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
		
		<div style="width:90%; margin-left:5%; margin-right:5%; margin-bottom:5%;">
			<br />
			<h1>Slushie Forms</h1>
			
			<form action="http://www.bluehost.com/bluemail" enctype="multipart/form-data" method="POST" name="test_form">
				Name:<br>
				<input type="text" name="name" value="your name"><br>
				
				E-mail:<br>
				<input type="text" name="mail" value="your email"><br>
				
				Comment:<br>
				<input type="text" name="comment" value="your comment" size="50"><br><br>
				
				<input type="submit" value="Send">
				<input type="reset" value="Reset">
				
				<input type="hidden" name="sendtoemail" value="rkargon@slushie.me">
			</form>
			
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