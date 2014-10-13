<!DOCTYPE php>
<?php

	$query = $_GET["q"];
	$xml = simplexml_load_file("rss.xml");
	
	$query = preg_quote($query);

	$i=1;
			
		foreach ($xml->xpath('/rss/channel/item') as $item) {
			if(($query=="") or preg_match("/".$query."/i", $item->title)){
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
						$display_date_sidebar=intval(($unix_today-$unix_pubdate)/3600) . " hours ago\n";
					}

					echo "\n<p class=\"nomargin\" align=right> <span class=\"timestamp\";> ".$display_date_sidebar."</span></p>\n";
					echo "</a></div><hr />\n";
					// For link if necessary &echo "Link: ".$channel_link."<br />";
					if ($i>10){break;}
			}
		}
?>