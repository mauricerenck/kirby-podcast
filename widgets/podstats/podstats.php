<?php
$titleDate = date('M Y');

return array(
	'title' => 'Podcast Statistics ' . $titleDate,
	'html' => function() {
		$podcasts			= array();
		$lastTrackingDate	= date('Y-m', strtotime(date('Y-m', strtotime($trackingDate . '-01')) . ' -1 month'));
		$trackingDate		= date('Y-m');
		
		$pages = panel()->site()->children()->index()->filter(function($p) {
			if($p->downloads()->exists()) return $p;
		});

		foreach($pages as $page) {
			if($page->content()->get('downloads')->isNotEmpty()) {
				$currentMonth = 0;
				$lastMonth    = 0;
				$total        = 0;
				$downloads    = $page->downloads()->yaml();
				$podcastTitle = $page->parent()->title();
				$podcastSlug  = str::slug($podcastTitle);

				if($podcasts[$podcastSlug] === null) {
					$podcasts[$podcastSlug] = [
						'title' => (string) $podcastTitle,
						'highscore' => [],
						'monthSum' => ['ecurrent' => 0, 'elast' => 0, 'rsscurrent' => 0, 'rsslast' => 0]
					];
				}

				foreach ($downloads as $download) {

					$total += $download['downloaded'];

					if($download['timestamp'] == $trackingDate) {
						$currentMonth = $download['downloaded'];
					}

					if($download['timestamp'] == $lastTrackingDate) {
						$lastMonth = $download['downloaded'];
					}
				}

				if($page->intendedTemplate() != 'podcastrss') {
					$podcasts[$podcastSlug]['ecurrent'] += $currentMonth;
					$podcasts[$podcastSlug]['elast']    += $lastMonth;

					$podcasts[$podcastSlug]['highscore'][] = array(
						'title'        => $page->title()->html(),
						'downloads'    => $total,
						'url'          => $page->url(),
						'currentMonth' => $currentMonth,
						'lastMonth'    => $lastMonth
					);
				} else {
					$podcasts[$podcastSlug]['rsscurrent'] += $currentMonth;
					$podcasts[$podcastSlug]['rsslast'] += $lastMonth;
				}

				// sort by current months downloads
				usort($podcasts[$podcastSlug]['highscore'], function ($a, $b) { return ($a['currentMonth'] < $b['currentMonth']); });
			}
		}

		return tpl::load(__DIR__ . DS . 'template.php', array(
			'podcasts'   => $podcasts
		));

	}

);
