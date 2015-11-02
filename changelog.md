# 1.0.2
* simplified the template. 
    *  You now don't need to flip the child-pages
    *  Give in the current $page as a parameter
    *  You don't have to hand over the page-params like iTunesAuthor

### Setting your post-basedir (deprecated)
The plugins needs to know, where your episodes are located. By default it will look into `content/episodes`. You can change that location by setting `c::set('podcast.basedir', 'YOUREPISODE_DIR_IN_CONTENT');` in your `config.php`