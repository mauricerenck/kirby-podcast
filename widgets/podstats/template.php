<?php
	$maxEntries = c::get('plugin.podcast.widget.entries', 20);
?>
<?php foreach($podcasts as $slug => $podcast) : ?>
	<a href="#" class="change-podcast" data-podcast="<?php echo $slug; ?>"><?php echo $podcast['title']; ?></a>
<?php endforeach; ?>

<?php foreach($podcasts as $slug => $podcast) : ?>
	<div class="podcast podcast_<?php echo $slug; ?>">
		<table class="podcast-list">
		<tr><th>Type</th><th class="align-right">This Month</th><th class="align-right">Last Month</th></tr>
		<tr><td>Episodes</td><td class="align-right"><?php echo $podcast['ecurrent']; ?></td><td class="align-right"><?php echo $podcast['elast']; ?></td></tr>
		<tr><td>RSS Feed</td><td class="align-right"><?php echo $podcast['rsscurrent']; ?></td><td class="align-right"><?php echo $podcast['rsslast']; ?></td></tr>
		</table>
		<table class="podcast-list">
		<tr><th>Episode</th><th colspan="2" class="align-center">Downloads</th><th>Total</th></tr>
		<?php
			$currentEntry = 0;
			foreach($podcast['highscore'] as $page) {
				if($currentEntry < $maxEntries) {
					$arrow = '<span style="color: green">⬆</span>';

					if($page['currentMonth'] < $page['lastMonth']) {
						$arrow = '<span style="color: red">⬇</>';
					} else if($page['currentMonth'] == $page['lastMonth']) {
						$arrow = '&mdash;';
					}

					echo '<tr><td><a href="'.$page['url'].'">' . $page['title'] . '</a></td><td class="align-right">' . $page['currentMonth'] . '</td><td>' . $arrow .'<span class="light" title="Prev. month">' . $page['lastMonth'] . '</span></td><td>' . $page['downloads'] . '</td></tr>';
				}

				$currentEntry++;
			}
		?>
		</table>
	</div>
<?php endforeach; ?>

<style>
	.change-podcast {
		display: inline-block;
		border: 1px solid #ddd;
		border-bottom: 0;
		padding: 5px 10px;
		margin-top: 20px;
	}

	.podcast {
		display: none;
	}

	.podcast:first-of-type {
		display: block;
	}

	.podcast-list {
		width: 100%;
		margin-bottom: 2em;
	}

	.podcast-list th {
		text-align: left;
		border-top: 1px solid #ddd;
		border-bottom: 1px solid #ddd;
		margin: 0.5em 0;
		padding: 0.5em 0;
	}

	.podcast-list .align-right {
		text-align: right;
	}

	.podcast-list .align-center {
		text-align: center;
	}

	.podcast-list .light {
		color: #aaa;
	}
</style>

<script>
	jQuery(document).ready(function($) {
		$('.change-podcast').on('click', function() {
			var podcast = $(this).data('podcast');
			$('.podcast').hide();
			$('.podcast_' + podcast).show();
		});
	});
</script>