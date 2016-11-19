# Podcast Plugin

## Update on its way!
I am working on a big update of this Plugin. Let me know what you're missing or if you have problems with the current version, so I can keep those points in mind for the next release.


## What is it?
The Podcast plugin will generate a RSS-feed optimized for podcasts. This includes all rss-fields needed for submitting your podcast to iTunes.
This plugin also analyzes your mp3-files and automaticly adds time- and size-information to the feed. 

## Installation
1. Go to the plugin-folder `/site/plugins`
2. get the plugin using 
    - Git: `git submodule add https://github.com/mauricerenck/kirby-podcast.git podcast`
    - Download: Download it from GitHub, unzip to plugin-folder, make sure to name the directory `podcast`
3. In your content-directory create a new directory without a leading number (for example `podcast`) to make the feed available under that URL.
4. Copy the `podcastfeed.sample.md` to that directory and rename it to `podcastfeed.md`
5. Copy the file `podcastfeed.sample.template.php` to your templates-directory in `/site/templates` and rename it to podcastfeed.php
6. Edit the file to your needs

## Configuration

### Enable GetID3 for duration detection
You can use the GetID3-lib to let the plugin determine the length of your audio files. By default this is deactivated, you can enable this feature by setting `c::set('podcast.useID3', true);` in your `config.php`.

Next you need to get the GetID3-Lib. Go into the pluginfolder in your terminal (`sites/plugins/podcast/`) and just type in: `git submodule update --init`. This will automaticly fetch the GetID3-Lib and you're ready to use it.


## How to use it?
### Setting up basic feed information
To set up the basic information for your feed, open the file `podcastfeed.md` you copied to a subdirectory in your content-directory.

#### Fields
##### title
The title of your podcast (not your episodes)

##### description
A short description of the content of your podcast

##### link
Just enter the content-folder of your feed. According to the sample above, this is 'podcast'. The Plugin will then create a valid feed-url out of this.

##### itunesAuthor
The name of the podcaster

##### itunesEmail
The (who might have guessed) e-mail-address of the podcaster

##### itunesImage
A full URL to the iTunes-Cover-Art image
##### itunesSubtitle
A short subtitle of your podcast

##### itunesKeywords
A comma-separated list of keywords

##### itunesBlock
Type in `yes` or `no` to block your podcast on iTunes

##### itunesExplicit
Type in `yes` or `no` to mark your podcast as explicit (or not)

##### itunesCategories
Enter a comma separated list of iTunes categories and subcategories. Subcategories have to be added after a / for example:
`Technology,Technology/Podcasting`
You can find a list of all iTunes-Categories in `iTunesCategories.json`

### Add new episodes
To add new episodes, you have to create a content-directory for all your episodes, for example `/content/01-episodes/` Add your single episodes as you would do with normal pages.

Name your markdownfile `episode.md` or `episode.txt`. You can use the `episode.sample.md` file in the plugin directory as a reference. You can now put your MP3 files in your episodes' folder, they will be automaticly detected and their information will be parsed.

## Author
Maurice Renck [www.maurice-renck.de](https://www.maurice-renck.de/x/3j297c)
