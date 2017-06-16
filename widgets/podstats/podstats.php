<?php
$titleDate = date('M Y');

return array(
	'title' => 'Podcast Statistics ' . $titleDate,
	'html' => function() {
		$highscore        = array();
		$trackingDate     = date('Y-m');
		$lastTrackingDate = date('Y-m', strtotime(date('Y-m', strtotime($trackingDate.'-01')) . ' -1 month'));

		$pages        = panel()->site()->children()->index()->filter(function($p) {
			if($p->downloads()->exists()) return $p;
		});

		foreach($pages as $page) {
			if($page->content()->get('downloads')->isNotEmpty()) {
				$currentMonth = 0;
				$lastMonth    = 0;
				$downloads    = $page->downloads()->yaml();

				foreach ($downloads as $download) {
					if($download['timestamp'] == $trackingDate) {
						$currentMonth = $download['downloaded'];
					}

					if($download['timestamp'] == $lastTrackingDate) {
						$lastMonth = $download['downloaded'];
					}
				}

				$highscore[] = array(
					'title'        => $page->title()->html(),
					'downloads'    => $downloads,
					'url'          => $page->url(),
					'currentMonth' => $currentMonth,
					'lastMonth'    => $lastMonth
				);

				// sort by current months downloads
				usort($highscore, function ($a, $b) { return ($a['currentMonth'] < $b['currentMonth']); });
			}
		}

		return tpl::load(__DIR__ . DS . 'template.php', array(
			'pages' => $highscore
		));
	}
);