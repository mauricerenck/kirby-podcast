<?php
/**
 * Podcast Plugin
 * @author Maurice Renck <hello@maurice-renck.de>
 * @version 2.0.0
 */

namespace Podcast;
use yaml;

class PodcastHelper {

	private function trackingDateExists($structure, $trackingDate) {
		foreach($structure as $trackingEntry) {
			if($trackingEntry['timestamp'] == $trackingDate) {
				return true;
			}
		}

		return false;
	}

	private function addTrackingData($page, $fieldData, $trackingDate){
		$fieldData[] = array(
			'timestamp' => $trackingDate,
			'downloaded' => 1
		);

		$fieldData   = yaml::encode($fieldData);
		$page->update(array('downloads' => $fieldData));
	}

	public function increaseDownloads($page, $trackingDate){
		$fieldData   = $page->downloads()->yaml();

		if($this->trackingDateExists($fieldData, $trackingDate)) {

			for($i = 0; $i < count($fieldData); $i++) { 
				if($fieldData[$i]['timestamp'] == $trackingDate) {
					$fieldData[$i]['downloaded']++;
				}
			}
			$fieldData   = yaml::encode($fieldData);

			$page->update(array('downloads' => $fieldData));
		} else {
			$this->addTrackingData($page, $fieldData, $trackingDate);
		}
	}

}
