<?php
/**
 * This file has to be copied to the site/templates directory
 * Name it podcastfeed.php
 * 
 * @author Maurice Renck <hello@maurice-renck.de>
 * @see Bastian Allgeier <bastian@getkirby.com>
 */

function cleanCategories($categoryString) {
	$categories = explode(',', $categoryString);
	$categories = preg_replace('/\n/', '', $categories);
	$categories = preg_replace('/\t/', '', $categories);
	return $categories;
}

echo page('episodes')->children()->visible()->flip()->podcast(array(
	'title'				=> $page->title(),
	'description'		=> $page->description(),
	'link'				=> $page->link(),
	'itunesAuthor'		=> $page->itunesAuthor(),
	'itunesEmail'		=> $page->itunesEmail(),
	'itunesImage'		=> $page->itunesImage(),
	'itunesSubtitle'	=> $page->itunesSubtitle(),
	'itunesKeywords'	=> $page->itunesKeywords(),
	'itunesBlock'		=> $page->itunesBlock(),
	'itunesExplicit'	=> $page->itunesExplicit(),
	'itunesCategories'	=> cleanCategories($page->itunesCategories())
));
