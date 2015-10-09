<?php

/**
 * Podcast Plugin
 * @author Maurice Renck <hello@maurice-renck.de>
 * @see Bastian Allgeier <bastian@getkirby.com>
 * @version 0.0.1
 */

require 'lib/podcast.php';

Pages::$methods['podcast'] = function($pages, $page, $params = array()) {

	$podcast = new Podcast();

	// set all default values
	$defaults = array(
		'datefield'		=> 'date',
		'textfield'		=> 'text',
		'modified'		=> time(),
		'excerpt'		=> false,
		'generator'		=> kirby()->option('feed.generator', 'Kirby'),
		'header'		=> true,
		'snippet'		=> false,
		'language'		=> 'de-DE',

		'url'				=> $page->url(),
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
		'itunesCategories'	=> $podcast->cleanCategories($page->itunesCategories())
	);

	// merge them with the user input
	$options = array_merge($defaults, $params);

	// sort by date
	$items = $pages->sortBy($options['datefield'], 'desc');

	// add the items
	$options['items'] = $items;
	$options['link']  = url($options['link']);

	// fetch the modification date
	if($options['datefield'] == 'modified') {
		$options['modified'] = $items->first()->modified();
	} else {
		$options['modified'] = $items->first()->date();
	}

	// send the xml header
	if($options['header']) header::type('text/xml');

	// echo the doctype
	$html  = '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL;

	// custom snippet
	if($options['snippet']) {
		$html .= snippet($options['snippet'], $options, true);
	} else {
		$html .= tpl::load(__DIR__ . DS . 'rsstemplate.php', $options);
	}

	return $html;

};