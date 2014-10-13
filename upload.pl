# upload.pl
# Written by Raphael Kargon (2012)
# ----------------------------------------------------------
# This script uploads most web files for Slushie all
# at once, which is useful for editing multiple files
# quickly.
#
# The script uploads all files by default, but you can add
# flags as arguments to only upload specific files 
# (eg. "perl upload.pl flags"):
#
# no flag - all files
# a			about/contact pages
# c			slushie.css
# d			display.php
# l			libraries, such as the JS floating sidebar file and the PHP category functions include
# m			miscelanneous pages (including ajaxperiment, author.php, and others)
# n			no_articles.php, 404.php
# u			update_rss.php
# v			view.php

use strict;
use Net::FTP;

chomp(my $flags = $ARGV[0]);

#print help if necessary
if($flags=~m/\?/)
{
	print "\n\n# upload.pl
# Written by Raphael Kargon (2012)
# ----------------------------------------------------------
# This script uploads most web files for Slushie all
# at once, which is useful for editing multiple files
# quickly.
#
# The script uploads all files by default, but you can add
# flags as arguments to only upload specific files 
# (eg. \"perl upload.pl flags\"):
#
# no flag - all files
# a			about/contact pages
# c			slushie.css
# d			display.php
# l			libraries, such as the JS floating sidebar file and the PHP category functions include
# m			miscelanneous pages (including ajaxperiment, author.php, and others)
# n			no_articles.php, 404.php
# u			update_rss.php
# v			view.php
# ?			this displays this help message, and then ends the script (without uploading anything);
\n\n";
	die;
}

$flags =~ s/[^acdlmnuv\?]//ig;
unless (length($flags)) {$flags = "acdlmnuv";}

#declare host name and account info
my $host="slushie.me";
my $user="slushie1";
my $password="BatteryHorseStaple5lu5h13.";

#connect to host
my $f = Net::FTP->new($host) or die "Cannot connect to $host\n";
print "Connected to $host.\n";

$f->login($user, $password) or die "Cannot login to $host with user $user and password $password\n";
print "Logged in to $host as $user.\n\n";

my $dir = "/public_html";
$f->cwd($dir) or die "Cannot cwd to $dir\n";

#upload files
if($flags =~ m/a/i){
	
	#about.php
	$dir = "/public_html";
	$f->cwd($dir) or die "Cannot cwd to $dir\n";	
	$f->put("about.php") or die "Can't put about.php into $dir\n";	
	print "about.php successfully uploaded to $dir.\n";
	
	$dir = "/public_html";
	$f->cwd($dir) or die "Cannot cwd to $dir\n";	
	$f->put("contact.php") or die "Can't put contact.php into $dir\n";	
	print "contact.php successfully uploaded to $dir.\n";
	
	$dir = "/public_html";
	$f->cwd($dir) or die "Cannot cwd to $dir\n";	
	$f->put("suggestion-box.php") or die "Can't put suggestion-box.php into $dir\n";	
	print "suggestion-box.php successfully uploaded to $dir.\n";
	
	$dir = "/public_html";
	$f->cwd($dir) or die "Cannot cwd to $dir\n";	
	$f->put("apply.php") or die "Can't put apply.php into $dir\n";	
	print "apply.php successfully uploaded to $dir.\n\n";

}

if($flags =~ m/c/i){
	
	$dir = "/public_html";
	$f->cwd($dir) or die "Cannot cwd to $dir\n";	
	$f->put("slushie.css") or die "Can't put slushie.css into $dir\n";	
	print "slushie.css successfully uploaded to $dir.\n\n";

}

if($flags =~ m/d/i){

	#main file	
	$f->put("display.php") or die "Can't put display.php into $dir\n";	
	print "display.php successfully uploaded to $dir.\n\n";

}

if($flags =~ m/l/i){
	
	$dir = "/public_html";
	
	$f->cwd($dir) or die "Cannot cwd to $dir\n";	
	$f->put("floating_thingies.js") or die "Can't put floating_thingies.js into $dir\n";	
	print "floating_thingies.js successfully uploaded to $dir.\n";
	
	$f->cwd($dir) or die "Cannot cwd to $dir\n";	
	$f->put("category_functions.php") or die "Can't put category_functions.php into $dir\n";	
	print "category_functions.php successfully uploaded to $dir.\n\n";

}

if($flags =~ m/m/i){
	
	$dir = "/public_html";
	
	$f->cwd($dir) or die "Cannot cwd to $dir\n";	
	$f->put("ajaxperiment.php") or die "Can't put ajaxperiment.php into $dir\n";	
	print "ajaxperiment.php successfully uploaded to $dir.\n";
	
	$f->cwd($dir) or die "Cannot cwd to $dir\n";	
	$f->put("search_articles.php") or die "Can't put search_articles.php into $dir\n";	
	print "search_articles.php successfully uploaded to $dir.\n";
	
	$f->cwd($dir) or die "Cannot cwd to $dir\n";	
	$f->put("author.php") or die "Can't put author.php into $dir\n";	
	print "author.php successfully uploaded to $dir.\n\n";
}

if($flags =~ m/n/i){

	$dir = "/public_html";
	
	$f->cwd($dir) or die "Cannot cwd to $dir\n";	
	$f->put("404.php") or die "Can't put 404.php into $dir\n";	#"Can't upload 404.php: file not found" = best error message ever
	print "404.php successfully uploaded to $dir.\n";
	
	$f->cwd($dir) or die "Cannot cwd to $dir\n";	
	$f->put("no_articles.php") or die "Can't put no_articles.php into $dir\n";
	print "no_articles.php successfully uploaded to $dir.\n\n";
}

if($flags =~ m/u/i){
	
	$dir = "/public_html";
	
	$f->cwd($dir) or die "Cannot cwd to $dir\n";	
	$f->put("update_rss.php") or die "Can't put update_rss.php into $dir\n";	
	print "update_rss.php successfully uploaded to $dir.\n\n";

}

if($flags =~ m/v/i){
	
	$dir = "/public_html";
	$f->cwd($dir) or die "Cannot cwd to $dir\n";	
	$f->put("view.php") or die "Can't put view.php into $dir\n";	
	print "view.php successfully uploaded to $dir.\n\n";

}

print "\n\nDone!";