<?php
/**
 * Podcast Plugin
 * @author Maurice Renck <hello@maurice-renck.de>
 * @version 2.0.0
 */

namespace Podcast;
use c;
use field;
use PiwikTracker;
use ssga;
use yaml;


require_once(__DIR__ . '/lib/helper.php');

if(c::get('plugin.podcast.piwik.Base', false)) {
	require_once(__DIR__ . '/lib/PiwikTracker.php');
}

if(c::get('plugin.podcast.GA.UA', false)) {
	require_once(__DIR__ . '/lib/ss-ga.class.php');
}

class Podcast {

	private $site;
	private $kirby;
	private $page;

	public function __construct() {

		$this->kirby = kirby();
		$this->site  = site();
		$this->page  = page();

		$this->kirby->set('template', 'podcastrss', __DIR__ . '/templates/rss.php');
		$this->kirby->set('widget', 'podstats', __DIR__ . '/widgets/podstats');
		$this->kirby->set('blueprint', 'podcastrss', __DIR__ . '/blueprints/podcastrss.yml');
		$this->kirby->set('blueprint', 'episode', __DIR__ . '/blueprints/episode.yml');
		$this->kirby->set('snippet', 'podcastplayer', __DIR__ . '/snippets/podcastplayer.php');

		$this->parseCategories();
		$this->setRoutes();
	}

	private function parseCategories() {
		$categoryList = $this->page->itunesCategories();

		field::$methods['itunes'] = function($categoryList) {

			$categories	= array();
			foreach ($categoryList->split(',') as $mainCategory) {
				// split category in main and sub category
				$currentCategory = explode('/', $mainCategory);

				if(!isset($categories[$currentCategory[0]])) {
					$categories[$currentCategory[0]] = array();
					if(isset($currentCategory[1])) {
						$categories[$currentCategory[0]][] = $currentCategory[1];
					}
				} else {
					if(isset($currentCategory[1])) {
						$categories[$currentCategory[0]][] = $currentCategory[1];
					}
				}
			}
			return $categories;
		};
	}

	private function setRoutes() {
		$language = ($this->site->languages() !== null) ? $this->site->language()->path() .'/': '';

		kirby()->routes(array(
			array(
				'pattern' => $language. '(:all)/download/(:any)',
				'action'  => function($episode, $filename) {

					// basic tracking
					$helper       = new PodcastHelper();
					$trackingDate = date('Y-m');
					$page         = page($episode);
					$helper->increaseDownloads($page, $trackingDate);

					// advanced tracking with piwik
					if(c::get('plugin.podcast.piwik.Base', false)) {

						// Get Title of Podcast via related rss-feed
						// We know the template-name, so get the rss feed by this name
						$podcastRssTemplateName = c::get('plugin.podcast.template', 'podcastrss');
						$podcastRelatedRss      = $page->parent()->children()->filterBy('intendedTemplate', $podcastRssTemplateName);
						$podcastTitle           = page($podcastRelatedRss)->title()->raw();
						$episodeTitle           = $page->title()->raw();

						// PiwikTracker::$URL = c::get('plugin.podcast.piwikBase');
						$piwikTracker = new PiwikTracker(c::get('plugin.podcast.piwik.Id'), c::get('plugin.podcast.piwik.Base'));

						$piwikTracker->setTokenAuth(c::get('plugin.podcast.piwik.Token'));
						$piwikTracker->disableSendImageResponse();
						$piwikTracker->disableCookieSupport();
						$piwikTracker->setUrl($page->url());
						$piwikTracker->setIp($_SERVER['REMOTE_ADDR']);

						if(c::get('plugin.podcast.piwik.GoalId', false)) {
							$piwikTracker->doTrackGoal(c::get('plugin.podcast.piwik.GoalId'), 1);
						}

						if(c::get('plugin.podcast.piwik.EventName', false)) {
							$piwikTracker->doTrackEvent($podcastTitle, c::get('plugin.podcast.piwikEventName', 'download'), $episodeTitle);
						}

						if(c::get('plugin.podcast.piwik.Action', false)) {
							$piwikTracker->doTrackAction($page->url(), 'download');
						}
					}

					if(c::get('plugin.podcast.GA.UA', false)) {
						$ssga = new ssga(c::get('plugin.podcast.GA.UA', null), site()->url());

						if(c::get('plugin.podcast.GA.pageView', false)) {
							// Set a pageview
							$ssga->set_page( $page->url() );
							$ssga->set_page_title( $episodeTitle );
						}

						if(c::get('plugin.podcast.GA.eventName', false)) {
							// Set an event
							$ssga->set_event( $podcastTitle, c::get('plugin.podcast.GA.eventName', 'Episode'), $episodeTitle, 1 );
						}

						// Send
						$ssga->send();
						$ssga->reset();
					}

					$filename = str_replace('.mp3', '', $filename);
					$file     = page($episode)
								->files()
								->filterBy('filename', '==', $filename.'.mp3')
								->last();

					header('Location: ' . $file->url());
				}
			)
		));
	}
}

$podcast = new Podcast();
