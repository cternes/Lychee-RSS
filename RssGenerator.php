<?php

use \FeedWriter\RSS2;

class RssGenerator
{
    private $generalTitle = null;
    private $generalDescription = null;
    private $titlePerAlbum = null;
    private $descriptionPerAlbum = null;
    private $filePhotoUrl = null;
    
    public function __construct($config) {
	if(empty($config['rssTitle'])) {
	    die('Error: rssTitle not set in config.ini');
	}
	if(empty($config['rssDescription'])) {
	    die('Error: rssDescription not set in config.ini');
	}
	if(empty($config['rssTitlePerAlbum'])) {
	    die('Error: rssTitlePerAlbum not set in config.ini');
	}
	if(empty($config['rssDescriptionPerAlbum'])) {
	    die('Error: rssDescriptionPerAlbum not set in config.ini');
	}
	
	$this->generalTitle = $config['rssTitle'];
	$this->generalDescription = $config['rssDescription'];
	$this->titlePerAlbum	= $config['rssTitlePerAlbum'];
	$this->descriptionPerAlbum = $config['rssDescriptionPerAlbum'];
	$this->filePhotoUrl = $config['rssfilePhotoUrl'];
    }
    
    public function buildRssFeedLatestPhotos($photos) {
	$feed = $this->prepareFeed($this->generalTitle, $this->generalDescription);
	
	while ($photo = $photos->fetch_assoc()) {
	    $newItem = $this->createPhotoItem($feed, $photo);
	    $feed->addItem($newItem);
	}
	
	return $feed->generateFeed();
    }
    
    public function buildRssFeedForAlbum($name, $photos)
    {
	$title = str_replace("{albumName}", $name, $this->titlePerAlbum);
	$description = str_replace("{albumName}", $name, $this->descriptionPerAlbum);
	$feed = $this->prepareFeed($title, $description);
	
	while ($photo = $photos->fetch_assoc()) {
	    $newItem = $this->createPhotoItem($feed, $photo);
	    $feed->addItem($newItem);
	}
	
	return $feed->generateFeed();
    }
    
    private function createPhotoItem($feed, $photo) {
	$newItem = $feed->createNewItem();
	$newItem->setTitle($photo['title']);
	if (!empty($photo['description'])) {
	    $newItem->setDescription($photo['description']);
	}

	# set photo link
	$photoUrl = $this->getCurrentUrl("../../#" . $photo['albumId'] . "/" . $photo['photoId']);
	$newItem->setLink($photoUrl);

	# set photo upload date
	$date = date('d M. Y H:i:s', substr($photo['photoId'], 0, -4));
	$newItem->setDate($date);
	
 	# if set, add file link to photo
	if($this->filePhotoUrl) {
		$photoFileUrl = $this->getCurrentUrl("../../uploads/big/" . $photo['photoUrl']);
		$newItem->addElement('photoURL', $photoFileUrl);
	}
	
	$aImageData = getimagesize("../../uploads/big/" . $photo['photoUrl']);
	$attributes = Array(
		'url' => $this->getCurrentUrl("../../uploads/big/" . $photo['photoUrl']),
		'width' => $aImageData[0],
		'height' => $aImageData[1],
		'type' => $aImageData['mime'],
	);
	$newItem->addElement('media:content', null, $attributes);

	return $newItem;
    }
    
    private function prepareFeed($title, $description) {
	$feed = new RSS2;
	$feed->setTitle($title);
	$feed->setLink($this->getCurrentUrl());
	$feed->setDescription($description);
	$feed->setDate(date(DATE_RSS, time()));
	$feed->setChannelElement('pubDate', date(\DATE_RSS, strtotime('today midnight')));
	$feed->addNamespace('media', 'http://search.yahoo.com/mrss/');
	
	return $feed;
    }
    
    private function getCurrentUrl($page = '') {
	$parseUrl = parse_url("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	return $parseUrl['scheme'] . "://" . $parseUrl['host'] . (isset($parseUrl['port']) ? (":" . $parseUrl['port']) : "") . ($page == '' ? $parseUrl['path'] : (dirname($parseUrl['path']) . '/' . $page));
    }
}
?>
