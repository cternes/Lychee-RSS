<?php

use \FeedWriter\RSS2;

class RssGenerator
{
    
    public function buildRssFeedLatestPhotos($photos) {
	$feed = $this->prepareFeed('Lychee - Latest public photos');
	
	while ($photo = $photos->fetch_assoc()) {
	    $newItem = $feed->createNewItem();
	    $newItem->setTitle($photo['title']);
	    if (!empty($photo['description'])) {
		$newItem->setDescription($photo['description']);	
	    }
	    
	    # set photo link
	    $parseUrl = parse_url("http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	    $photoUrl = $parseUrl['scheme']."://".$parseUrl['host'].":".$parseUrl['port'].$parseUrl['path']."../../#".$photo['albumId']."/".$photo['photoId'];
	    $newItem->setLink($photoUrl);
	    
	    # set photo upload date
	    $date = date('d M. Y', substr($photo['photoId'], 0, -4)); #TODO: How to get real datetime here?
	    $newItem->setDate($date);
	    
	    $feed->addItem($newItem);
	}
	
	return $feed->generateFeed();
    }
    
    public function buildRssFeedForAlbum($album)
    {
	$feed = $this->prepareFeed('Lychee - Latest photos in album ' . $album);
	
	#TODO
	
	return $feed->generateFeed();
    }
    
    private function prepareFeed($title) {
	$feed = new RSS2;
	$feed->setTitle($title);
	$feed->setLink('http://TODO/lychee/plugins/rss');
	$feed->setDescription('This feed contains the latest lychee photos');
	$feed->setDate(date(DATE_RSS, time()));
	$feed->setChannelElement('pubDate', date(\DATE_RSS, strtotime('today midnight')));
	
	return $feed;
    }
}
?>
