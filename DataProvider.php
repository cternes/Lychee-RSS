<?php

class DataProvider {

    private $database = null;
    private $settings = null;

    public function __construct($dbHost, $dbUser, $dbPassword, $dbName) {
	# Define
	defineTablePrefix($dbTablePrefix);
	
	# Connect
	$this->database = Database::connect($dbHost, $dbUser, $dbPassword, $dbName);
	
	# Check connection
	if ($database->connect_errno) {
	    die('Error: Could not connect to the lychee database. Is the path to lychee correct? ' . $database->connect_error);
	}

	# Load settings
	$settings = new Settings($this->database);
	$this->settings = $settings->get();
    }

    public function getPhotostream() {
	# Get latest photos
	$query = Database::prepare($this->database, 'SELECT p.id as photoId, p.title, p.description, a.id as albumId FROM ? p LEFT OUTER JOIN ? a ON p.album = a.id WHERE a.public = 1 ORDER BY p.id DESC LIMIT 50', array(LYCHEE_TABLE_PHOTOS, LYCHEE_TABLE_ALBUMS));
	$photos = $this->database->query($query);
	if (!$photos) {
	    Log::error($this->database, __METHOD__, __LINE__, $this->database->error);
	    die('Error: Could not fetch the latest photos from the database.');
	}
	return $photos;
    }

    public function getPhotosByAlbum($albumId) {
	$query = Database::prepare($this->database, 'SELECT p.id as photoId, p.title, p.description, p.album as albumId FROM ? p WHERE p.album = ? ORDER BY p.id DESC LIMIT 50', array(LYCHEE_TABLE_PHOTOS, $albumId));
	$photos = $this->database->query($query);
	if (!$photos) {
	    Log::error($this->database, __METHOD__, __LINE__, $this->database->error);
	    die('Error: Could not fetch the latest photos for album ' . $albumId . ' from the database.');
	}
	return $photos;
    }

    public function getPublicAlbums() {
	$album = new Album($this->database, null, $this->settings, null);
	return $album->getAll(true);
    }

}

?>
