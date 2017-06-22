<?php
	if($page->hasAudio()) {
		$audioFiles = array();
		foreach($page->audio() as $audio) {
			$audioFiles[] = $page->url() . '/download/' . str_replace('.mp3', '', $audio->filename());
		}
?>
<div class="podcast-player">
	<audio controls>
		<?php foreach ($audioFiles as $audioFile) : ?>
		<source src="<?php echo $audioFile; ?>" type="audio/mpeg">
		<?php endforeach; ?>
		Your browser does not support the audio element.
	</audio>
</div>
<?php } ?>