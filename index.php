<?php

###
# @author		cternes
# @copyright		2014 by cternes
# @version		1.0.0
# @description		This plugin generates an RSS feed out of your latest public photos
###

# Config
if (is_readable('config.ini')) {
    $config = parse_ini_file('config.ini');
}
else {
    die('Error: config.ini not found');
}

# Include

require($config['lychee'] . 'php/define.php');
require($config['lychee'] . 'php/autoload.php');
require($config['lychee'] . 'data/config.php');


require('RssGenerator.php');
require('JsonGenerator.php');
require('DataProvider.php');
require('vendor/autoload.php');

# Set Mime Type
if (!empty($_GET['format']) && $_GET['format'] === "json") {
    header('Content-Type: application/json');
} else {
    header('Content-type: application/rss+xml');
}

$rssGenerator = new RssGenerator($config);
$jsonGenerator = new JsonGenerator($config);
$dataProvider = new DataProvider();
# If a album name is provided, we'll create a feed only for this album
if(!empty($_GET['album'])) {
    $albumId = $dataProvider->getAlbumIdByName($_GET['album']);
    
    if(empty($albumId)) {
	die('Could not find a public album with title: ' .$_GET['album'] . '. Please make sure that this album exists and that it is public!');
    }
    
    $photos = $dataProvider->getPhotosByAlbum($albumId);

    if (!empty($_GET['format']) && $_GET['format'] === "json") {
        # Generate RSS as JSON
        echo $jsonGenerator->buildJsonFeedForAlbum($_GET['album'], $photos);
    } else {
        # Generate RSS
        echo $rssGenerator->buildRssFeedForAlbum($_GET['album'], $photos);
    }
}
# If no album name is provided, we'll create a feed with all public photos
else {
    # Get latest photos
    $photos = $dataProvider->getPhotostream();

    if (!empty($_GET['format']) && $_GET['format'] === "json") {
        # Generate RSS as JSON
        echo $jsonGenerator->buildJsonFeedLatestPhotos($photos);
    } else {
        # Generate RSS
        echo $rssGenerator->buildRssFeedLatestPhotos($photos);
    }
}

?>
