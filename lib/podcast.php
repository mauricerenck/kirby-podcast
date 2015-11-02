<?php

class Podcast {
	public function __construct() {
		// VOID
	}

	public function cleanCategories($categoryString) {
		$categories = explode(',', $categoryString);
		$categories = preg_replace('/\n/', '', $categories);
		$categories = preg_replace('/\t/', '', $categories);
		return $categories;
	}

	public function parseCategories($categoryList) {
		$categories	= array();
		foreach ($categoryList as $mainCategory) {
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
	}
}