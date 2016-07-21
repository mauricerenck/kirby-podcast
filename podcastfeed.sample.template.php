<?php
/**
 * This file has to be copied to the site/templates directory
 * Name it podcastfeed.php
 *
 * @author Maurice Renck <hello@maurice-renck.de>
 * @see Bastian Allgeier <bastian@getkirby.com>
 */

// Change 'episodes' to your needs, insert the parent content-folder
// where all your episodes live in
echo page('episodes')->children()->visible()->podcast($page, array(
	'textfield'      => 'intro'
));
