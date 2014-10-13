<?php 

# category_functions.php
# Written by Raphael Kargon 2013
# (c) Copyright Slushie 2013
# --------------------------------------------------------
# This file includes various functions that can be used to
# deal with the categories of slushie articles. These functions
# use the SQL database to draw category and article information.

//This function gets the category # of a category given its name
function getCategoryID ($categoryName)
{
	if(!$categoryName){return 0;}
	$category_result=mysql_query("SELECT * FROM `wp_terms` WHERE slug='".strtolower($categoryName)."'");
	if(mysql_num_rows($category_result)){return(mysql_result($category_result, 0, "term_id"));}
	else {return (-1);}
}

//This function gets the category name of a category given its ID #
function getCategoryName($categoryID)
{
	if(!$categoryID){return "";}
	$category_result=mysql_query("SELECT * FROM `wp_terms` WHERE term_id='".$categoryID."'");
	return(mysql_result($category_result, 0, "name"));
}

//This function returns an array with all the categories of an article
function getCategories($articleID){
	//this gets all terms that match the article, and are classified as a "category" in the term_taxonomy table.
	$category_result = mysql_query("SELECT * FROM wp_term_relationships INNER JOIN wp_term_taxonomy ON wp_term_relationships.term_taxonomy_id = wp_term_taxonomy.term_taxonomy_id WHERE wp_term_relationships.object_id=".$articleID." AND wp_term_taxonomy.taxonomy='category'");
	
	$category_array = array();
	while ($row = mysql_fetch_array($category_result)) {
    	$category_array[] = $row['term_id'];
	}
	
	return $category_array;
}


//This function checks if a given article matches the given category.
function checkCategory($categoryID, $articleID){
	if(!$categoryID) return 1;
	$categorycheck_result = mysql_query("SELECT * FROM wp_term_relationships WHERE object_id=".$articleID." AND term_taxonomy_id=".$categoryID);
	return (mysql_num_rows($categorycheck_result));
}

?>