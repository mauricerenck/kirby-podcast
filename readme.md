# Kirby Podcast Plugin

This Plugins enables you to use Kirby for podcasting. It creates an iTunes ready RSS-Feed for you. It reads your mp3-metadata to determine the length of your mp3 files.

This plugin comes with a basic download tracking and a Panel Widget to show you the latest stats. You can enable advanced tracking using Piwik or Google Analytics.


## Features

- Podcast Chapters
- New iTunes iOS 11 Specs supported
- Basic Statistics
- Advanced Statistics using Piwik or Google Analytics
- See your stats in the Panel (Widget)
- HTML5 Audioplayer Snippet
- Blueprints for your episodes and feeds
- Multiple feeds
- Kirby CLI support

## Updating from Version 1

Please be aware that this version is a complete rewrite. It's recommended to do a fresh install. Please use a test-environment to see if it is compatible with your old installation.

## Requirements

- Kirby 2.4.1
- PHP 5.6

## Installation

### Kirby CLI (recommended)

The easiest way to install this plugin, is by using the great Kirby CLI

    kirby plugin:install mauricerenck/kirby-podcast

### Clone the git repository

You can also just clone this repository

    git clone https://github.com/mauricerenck/kirby-podcast site/plugins/podcast

### Install via zip file

You can download the latest release here: <https://github.com/mauricerenck/kirby-podcast/archive/master.zip>

Unzip it and copy the `podcast` folder to the Kirby plugin folder at `site/plugins/`

### Setup

If you're using the panel, you can now use two new blueprints. There is the `podcastrss` blueprint which is used for your podcast feeds.

There is also an `episode` blueprint for… well… your episodes.

If you don't use the panel, you can find some sample markdown in the docs folder of this plugin.

### iTunes categories for your feed

You can find a list of iTunes categories in the docs folder. Categories have to be entered in a certain way, like:

    MainCategory,MainCategory/SubCategory

Example:

    Business,Business/Careers

### Setting up your content

#### RSS Feed
Your RSS feed should be in a subfolder of your podcast folder. It should be invisible (by not adding a number before its folder name). Place a markdown file called `podcastrss.md` in you rss folder.

If you're using a multi-language setup you can also add language related markdown files like `podcastrss.de.md`

Fill in all fields. You can find an example in the docs folder.

Example:

Lets say your podcast should be available at https://yoursite.com/podcast go to your content folder and add the folder `podcast` make sure it has a leading number according to your setup.
Within your podcast folder add another folder called `feed`. Add the `podcastrss.md` file. Fill in all the content. Your feed should now be available at: https://yoursite.com/podcast/feed

#### Episodes
To add an episode just create the episode folder in your podcast main folder, just the same as you would do for your blog-posts or pages. Add a markdown file, for example `episode.md`. Fill in the information. You will find a sample markdown file in the docs folder of this plugin.

Your episode will appear in the feed as soon as it's visible. To make it possible to pre produce your episodes, you can use the `date` field in your episode markdown to schedule it. Set a date in the future, set it to visible and it will appear in your feed as soon as the given date is reached. Please make sure that your theme can do this, too. You can add a filter to your query:

    $page->children()->visible()->flip()->filterBy('date', '<=', time())


## Options

To set the number if items shown in the download-stat-widget, use:

    c::set('plugin.podcast.widget.entries', 10);

To overwrite the atomLink-Tag use:

    c::set('plugin.podcast.atom.link', 'http://yourdomain.tld/path/to/file.php');

You can use that to keep your old feed-url active when moving your feed.

### Advanced Statistics

This plugin uses basic download-tracking. Every download increases the download-counter for your episode. But you can also use advanced statistics using Piwik (recommended) and Google Analytics (not yet fully tested).

#### Piwik

You can use Piwik for advanced tracking. You can chose to trigger a Piwik goal, an event or an action. To trigger a goal, you have to create it first within your piwik installation. You can read more about it here: <https://piwik.org/docs/tracking-goals-web-analytics/>

To enable piwik tracking, just set your base url and piwik id. Unless you set a goalId, event name or enable the action-tracking, nothing will happen. You can set all of this options or just one or two.

Set these options in your Kirby `config.php`

|Option|Value|Mandatory|
|---|---|---|
|plugin.podcast.piwik.Base|Your Piwik base URL|yes|
|plugin.podcast.piwik.Id|The Piwik ID of the related page|yes|
|plugin.podcast.piwik.Token|Your Piwik Access Token <https://piwik.org/faq/general/faq_114/>|yes|
|plugin.podcast.piwik.GoalId|Set the ID of your created goal to start tracking it|optional|
|plugin.podcast.piwik.EventName|Enter a name for the download Event, to start tracking it|optional|
|plugin.podcast.piwik.Action (bool)| set to `true` to enable tracking the download action|optional|

Example:

    c::set('plugin.podcast.piwik.Base', 'http://stats.mypiwik.net/');
    c::set('plugin.podcast.piwik.Id', 2);
    c::set('plugin.podcast.piwik.Token', '1234567890abcdg6');
    c::set('plugin.podcast.piwik.GoalId', 1);
    c::set('plugin.podcast.piwik.EventName', 'Download');
    c::set('plugin.podcast.piwik.Action', true);

#### Google Analytics

You can use Google Analytics for advanced tracking. Please be aware, that this feature isn't fully tested by now. Set your UA-Id to enable tracking, enter an event name and decide if you want to trigger a page view.


|Option|Value|Mandatory|
|---|---|---|
|plugin.podcast.GA.UA|UA-1234566|yes|
|plugin.podcast.GA.pageView (bool)|true|optional|
|plugin.podcast.GA.eventName|Download|optional|

Example:

    c::set('plugin.podcast.GA.UA', 'UA-1234567890');
    c::set('plugin.podcast.GA.pageView', true);
    c::set('plugin.podcast.GA.eventName', 'Episode');


## Chapters

This plugin now support Chapters. If you use the plugins blueprint you already have the possibility to add chapter marks. Just enter a timestamp a title and an optional URL. You can add as many chapters as you wish.

If you don't use the blueprint have a look at the docs folder, there is a sample markdown file inluding a chapter example.


## Player
Please be aware, currently the HTML5 audio element is used as a player. This brings very basic functionality. I am working on integrating a more functional player. To use the HTML5-Player include this snippet in your page-template:

    <?php snippet('podcastplayer'); ?>

You can use any other player, just have a look at `snippets/podcastplayer.php` inside the podcast plugin folder. You can copy and paste the php parts to use with your own player. Please make sure to also include the rewrite of the mp3-url which makes tracking of downloads possible.

## Trouble?

**The download URLs of the mp3 files are not working!**
Some server setups cannot handle file extensions. The download link will not work if you're using the `php -S` command. Test the downloads by copying the url and removing `.mp3`. The download route should then work.
If you're using apache or nginx you can use a Rewrite Rule to remove the .mp3 extension.

Please be aware that iTunes needs a file-extension in the Download-URL. If it's missing it may be possible that your files are not accessible through iTunes!

**I don't know what to write into my markdown files!**
If you're not using the blueprint-files of the plugin or are not using the panel at all, have a look at the docs folder of this plugin. You'll find some sample markdown files there.

## License

<http://www.opensource.org/licenses/mit-license.php>

## Author

Maurice Renck <https://maurice-renck.de>
