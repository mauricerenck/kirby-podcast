<?php
	namespace Podcast;
	use c;
	use header;
	use getID3;

	require_once(__DIR__ . '/../lib/getid3/getid3.php');
	require_once(__DIR__ . '/../lib/helper.php');
	$getID3 = new getID3;
	header::type('text/xml');

	$helper       = new PodcastHelper();
	$trackingDate = date('Y-m');
	$helper->increaseDownloads($page, $trackingDate);

	$atomLink = $page->url();
	if(c::get('plugin.podcast.atom.link', false)) {
		$atomLink = c::get('plugin.podcast.atom.link');
	}
?>
<?php echo '<?xml version="1.0" encoding="utf-8"?>' . PHP_EOL; ?>
<rss xmlns:atom="http://www.w3.org/2005/Atom" xmlns:itunes="http://www.itunes.com/dtds/podcast-1.0.dtd" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:fh="http://purl.org/syndication/history/1.0" version="2.0">
	<channel>
		<title><?php echo xml($page->title()); ?></title>
		<link><?php echo xml($page->parent()->url()); ?></link>

		<description><?php echo xml($page->description()); ?></description>

		<atom:link href="<?php echo xml($atomLink) ?>" rel="self" type="application/rss+xml" title="<?php echo xml($page->title()) ?>"/>

		<lastBuildDate><?php echo date('r', $page->modified()); ?></lastBuildDate>
		<language><?php echo xml($page->language()); ?></language>
		<generator>Kirby Podcast Plugin</generator>

		<itunes:author><?php echo xml($page->itunesAuthor()); ?></itunes:author>
		<itunes:summary><?php echo xml($page->description()); ?></itunes:summary>
		<itunes:owner>
			<itunes:name><?php echo xml($page->itunesOwner()); ?></itunes:name>
			<itunes:email><?php echo xml($page->itunesEmail()); ?></itunes:email>
		</itunes:owner>

		<?php if($page->itunesImage()->exists()): ?>
		<itunes:image href="<?php echo xml($page->image($page->itunesImage())->url()); ?>"/>
		<?php endif; ?>

		<itunes:subtitle><?php echo xml($page->itunesSubtitle()); ?></itunes:subtitle>
		<itunes:keywords><?php echo xml($page->itunesKeywords()); ?></itunes:keywords>
		<itunes:block><?php echo ($page->itunesBlock()->isTrue()) ? 'yes' : 'no'; ?></itunes:block>
		<itunes:explicit><?php echo ($page->itunesExplicit()->isTrue()) ? 'yes' : 'no'; ?></itunes:explicit>
		<itunes:type><?php echo xml($page->itunesType()); ?></itunes:type>
		<?php
			foreach ($page->itunesCategories()->itunes() as $key => $mainCategory) {
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
		<?php $episodes = $page->parent()->children()->visible()->flip()->filterBy('date', '<=', time()); ?>

		<?php foreach($episodes as $episode): ?>
			<?php if($episode->hasAudio()): ?>
				<item>
					<title><?php echo xml($episode->title()); ?></title>
					<link><?php echo xml($episode->url()); ?></link>
					<guid isPermaLink="false"><?php echo xml($episode->id()); ?></guid>
					<pubDate><?php echo date('r', $episode->date()); ?></pubDate>
					<description><![CDATA[<?php echo $episode->poddescr()->kirbytext() ?>]]></description>
					<atom:link  href="<?php echo xml($episode->url()); ?>"/>

					<?php foreach($episode->audio() as $audio): ?>
						<?php
							$duration = $audio->duration();

							// check if length information is already written to meta-file
							// if not, write the information
							if($audio->duration()->empty()) {
								$path				= $audio->root();
								$mixinfo			= $getID3->analyze($path);
								$duration			= $mixinfo['playtime_string'];
								list($mins , $secs)	= explode(':' , $duration);

								$hours = 0;
								if($mins > 60) {
									$hours	= intval($mins / 60);
									$mins	= $mins - $hours*60;
								}

								$duration = sprintf("%02d:%02d:%02d" , $hours , $mins , $secs);

								// Update file info, so we don't have to determine the duration again
								$audio->update(array(
									'duration' => $duration
								));
							}

							$audioUrl = $episode->url().'/download/'.$audio->filename();
						?>
						<enclosure url="<?php echo $audioUrl ?>" length="<?php echo $audio->size() ?>" type="<?php echo $audio->mime() ?>"/>
					<?php endforeach; ?>
					<itunes:duration><?php echo $duration; ?></itunes:duration>

					<itunes:author><?php echo xml($episode->author()->or($page->itunesAuthor())); ?></itunes:author>
					<itunes:subtitle><?php echo xml($episode->podsubtitle()); ?></itunes:subtitle>
					<itunes:summary><?php echo xml($episode->podsubtitle()); ?></itunes:summary>

					<?php if($episode->podseason()->isNotEmpty()): ?><itunes:season><?php echo xml($episode->podseason()); ?></itunes:season><?php endif; ?>
					<?php if($episode->podepisode()->isNotEmpty()): ?><itunes:episode><?php echo xml($episode->podepisode()); ?></itunes:episode><?php endif; ?>
					<?php if($episode->podtitle()->isNotEmpty()): ?><itunes:title><?php echo xml($episode->podtitle()); ?></itunes:title><?php endif; ?>
					<?php if($episode->episodeType()->isNotEmpty()): ?><itunes:episodeType><?php echo xml($episode->episodeType()); ?></itunes:episodeType><?php endif; ?>
					<content:encoded><![CDATA[
						<?php echo $episode->poddescr()->kirbytext() ?>
					]]></content:encoded>

					<?php if($episode->chapters()->isNotEmpty()): ?>
						<!-- specify chapter information -->
						<psc:chapters version="1.2" xmlns:psc="http://podlove.org/simple-chapters">
						<?php foreach($episode->chapters()->yaml() as $chapter): ?>
							<psc:chapter start="<?php echo $chapter['timestamp']; ?>" title="<?php echo xml($chapter['title']); ?>" <?php if($chapter['url']): ?>href="<?php echo xml($chapter['url']); ?>"<?php endif; ?> />
						<?php endforeach; ?>
						</psc:chapters>
					<?php endif; ?>
				</item>
			<?php endif; ?>
		<?php endforeach ?>
	</channel>
</rss>
