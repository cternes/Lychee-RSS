<?php

class JsonGenerator
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
    
    public function buildJsonFeedLatestPhotos($photos) {
	$jsonObject = $this->prepareFeed($this->generalTitle, $this->generalDescription);
	
	$items = array();
	while ($photo = $photos->fetch_assoc()) {
	    $items[] = $this->createPhotoItem($photo);
	}
	$jsonObject['items'] = $items;
	
	echo json_encode($jsonObject);
    }
    
    public function buildJsonFeedForAlbum($name, $photos)
    {
	$title = str_replace("{albumName}", $name, $this->titlePerAlbum);
	$description = str_replace("{albumName}", $name, $this->descriptionPerAlbum);
	$jsonObject = $this->prepareFeed($title, $description);
	
	$items = array();
	while ($photo = $photos->fetch_assoc()) {
	    $items[] = $this->createPhotoItem($photo);
	}
	$jsonObject['items'] = $items;
	
	echo json_encode($jsonObject);
    }
    
    private function createPhotoItem($photo) {
	$newItem = array();

	$newItem['title'] = $photo['title'];
	if (!empty($photo['description'])) {
	    $newItem['description'] = $photo['description'];
	}

	# set photo link
	$newItem['photoUrl'] = $this->getCurrentUrl() . "../../#" . $photo['albumId'] . "/" . $photo['photoId'];

	# set photo upload date
	$newItem['pubDate'] = date('d M. Y H:i:s', substr($photo['photoId'], 0, -4));
 
 	# if set, add file link to photo
	if ($this->filePhotoUrl) {
		$newItem['photoFileUrl'] = $this->getCurrentUrl() . "../../uploads/big/" . $photo['photoUrl'];
	}

	return $newItem;
    }
    
    private function prepareFeed($title, $description) {
	$jsonObject = array();
	$jsonObject['title'] = $title;
	$jsonObject['link'] = $this->getCurrentUrl();
	$jsonObject['description'] = $description;
	$jsonObject['date'] = date(DATE_RSS, time());
	$jsonObject['pubDate'] = date(\DATE_RSS, strtotime('today midnight'));
	
	return $jsonObject;
    }
    
    private function getCurrentUrl() {
	$parseUrl = parse_url("http://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
	return $parseUrl['scheme'] . "://" . $parseUrl['host'] . ":" . $parseUrl['port'] . $parseUrl['path'];
    }
}
?>
