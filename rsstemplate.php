<?php
	/*
		TODO:	Calculate the duration and size of the audiofiles once,
				not every time the feed is loaded, to decrease script-runtime
				especially when there are a lot of audiofiles and subscribers
 	*/
	$pluginPath = dirname(__FILE__);
	require_once($pluginPath.'/getID3/getid3/getid3.php');
	$getID3 = new getID3;


	function parseCategories($categoryList) {
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
?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:psc="http://podlove.org/simple-chapters" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:fh="http://purl.org/syndication/history/1.0" version="2.0">
	<channel>
		<title><?php echo xml($title); ?></title>
		<link><?php echo xml($link); ?></link>

		<?php if(!empty($description)): ?>
		<description><?php echo xml($description); ?></description>
		<?php endif ?>

		<atom:link href="<?php echo xml($url) ?>" rel="self" type="application/rss+xml" title="<?php echo xml($title) ?>"/>
		<lastBuildDate><?php echo date('r', $modified); ?></lastBuildDate>
		<language><?php echo xml($language); ?></language>
		<generator><?php echo c::get('feed.generator', 'Kirby') ?></generator>

		<itunes:author><?php echo xml($itunesAuthor); ?></itunes:author>
		<itunes:summary>
			<?php echo xml($description); ?>
		</itunes:summary>
		<?php
			$categories = parseCategories($itunesCategories);
			foreach ($categories as $key => $mainCategory) {

				if(is_array($mainCategory)) {
					echo '<itunes:category text="'.$key.'">';
					foreach ($mainCategory as $subCategory) {
						echo '<itunes:category text="'.$subCategory.'"/>';
					}
					echo '</itunes:category>';
				} else {
					echo '<itunes:category text="'.$mainCategory.'"/>';
				}
			}
		?>
		<itunes:owner>
			<itunes:name><?php echo xml($itunesAuthor); ?></itunes:name>
			<itunes:email><?php echo xml($itunesEmail); ?></itunes:email>
		</itunes:owner>
		<itunes:image href="<?php echo xml($itunesImage); ?>"/>
		<itunes:subtitle><?php echo xml($itunesSubtitle); ?></itunes:subtitle>
		<itunes:keywords><?php echo xml($itunesKeywords); ?></itunes:keywords>
		<itunes:block><?php echo xml($itunesBlock); ?></itunes:block>
		<itunes:explicit><?php echo xml($itunesExplicit); ?></itunes:explicit>

		<?php foreach($items as $item): ?>
		<item>
			<title><?php echo xml($item->title()); ?></title>
			<link><?php echo xml($item->url()); ?></link>
			<guid isPermaLink="false"><?php echo xml($item->id()); ?></guid>
			<pubDate><?php echo ($datefield == 'modified') ? $item->modified('r') : $item->date('r', $datefield); ?></pubDate>
			<description><![CDATA[<?php echo $item->{$textfield}()->kirbytext() ?>]]></description>

			<atom:link rel="http://podlove.org/deep-link" href="<?php echo xml($item->url()); ?>"/>
			<?php foreach($item->audio() as $audio): ?>
				<?php 
					$duration = $audio->duration();

					// check if length information is already written to meta-file
					// if not, write the information
					if($audio->duration()->empty()) {
						$path				= $audio->root();
						$mixinfo			= $getID3->analyze($path);
						$duration			= $mixinfo['playtime_string'];
						list($mins , $secs)	= explode(':' , $duration);

						if($mins > 60) {
							$hours	= intval($mins / 60);
							$mins	= $mins - $hours*60;
						}

						$duration = sprintf("%02d:%02d:%02d" , $hours , $mins , $secs);

						$audio->update(array(
							'duration' => $duration
						));
					}
				?>
				<enclosure url="<?php echo $audio->url() ?>" length="<?php echo $audio->size() ?>" type="<?php echo $audio->mime() ?>"/>
			<?php endforeach; ?>
			<itunes:duration><?php echo $duration; ?></itunes:duration>
			<itunes:author><?php echo xml($item->author()); ?></itunes:author>
			<itunes:subtitle><?php echo xml($item->subtitle()); ?></itunes:subtitle>
			<itunes:summary>
			<?php echo $item->{$textfield}()->text() ?>
			</itunes:summary>
		</item>
		<?php endforeach ?>

	</channel>
</rss>