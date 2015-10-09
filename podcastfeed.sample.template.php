<?php
/**
 * This file has to be copied to the site/templates directory
 * Name it podcastfeed.php
 * 
 * @author Maurice Renck <hello@maurice-renck.de>
 * @see Bastian Allgeier <bastian@getkirby.com>
 */

/**
*	These are the default value
*	If You don't need to change them, just remove this array and 
*	remove $options from podcast() below;
*/
$options = array(
	'datefield' => 'date',
	'textfield' => 'text',
	'excerpt' => false,
	'header' => true,
	'snippet' => false
)

// Change 'episodes' to your needs, insert the parent content-folder
// where all your episodes live in
echo page('episodes')->children()->visible()->podcast($page, $options);