<?php

###
# @author		cternes
# @copyright		2014 by cternes
# @version		1.0.0
# @description		
###

# Config
if (file_exists('config.ini')) {
    $config = parse_ini_file('config.ini');
}
else {
    exit('Error: config.ini not found');
}

# Include
require($config['lychee'] . 'php/define.php');
require($config['lychee'] . 'php/autoload.php');
require($config['lychee'] . 'data/config.php');
require('RssGenerator.php');
require('DataProvider.php');
require('vendor/autoload.php');

# Define
defineTablePrefix($dbTablePrefix);

# Set Mime Type
header('Content-type: application/rss+xml');

# Get latest photos
$dataProvider = new DataProvider($dbHost, $dbUser, $dbPassword, $dbName);
$photos = $dataProvider->getPhotostream();

# Generate RSS
$rssGenerator = new RssGenerator();
echo $rssGenerator->buildRssFeedLatestPhotos($photos);
?>