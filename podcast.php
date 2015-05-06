<?php

/**
 * Podcast Plugin
 * @author Maurice Renck <hello@maurice-renck.de>
 * @see Bastian Allgeier <bastian@getkirby.com>
 * @version 0.0.1
 */
Pages::$methods['podcast'] = function($pages, $params = array()) {

	// set all default values
	$defaults = array(
		'url'			=> url('podcast'), // direct url to the atom-feed
		'title'			=> 'Podcasts',
		'description'	=> '',
		'link'			=> url(),
		'datefield'		=> 'date',
		'textfield'		=> 'text',
		'modified'		=> time(),
		'excerpt'		=> false,
		'generator'		=> kirby()->option('feed.generator', 'Kirby'),
		'header'		=> true,
		'snippet'		=> false,
		'language'		=> 'de-DE'
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
		$options['modified'] = $items->first()->date(false, $options['datefield']);
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
