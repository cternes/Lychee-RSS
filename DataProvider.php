<?php

use Lychee\Modules\Database;
use Lychee\Modules\Settings;
use Lychee\Modules\Log;
use Lychee\Modules\Album;

class DataProvider {

    public function getPhotostream() {
	# Get latest photos
	$query = Database::prepare(Database::get(), 'SELECT p.id as photoId, p.url as photoUrl, p.title, p.description, a.id as albumId FROM ? p LEFT OUTER JOIN ? a ON p.album = a.id WHERE a.public = 1 ORDER BY p.id DESC LIMIT 50', array(LYCHEE_TABLE_PHOTOS, LYCHEE_TABLE_ALBUMS));
	$photos = Database::get()->query($query);
	if (!$photos) {
	    Log::error(Database::get(), __METHOD__, __LINE__, Database::get()->error);
	    die('Error: Could not fetch the latest photos from the database.');
	}
	return $photos;
    }

    public function getPhotosByAlbum($albumId) {
	$query = Database::prepare(Database::get(), 'SELECT p.id as photoId, p.url as photoUrl, p.title, p.description, p.album as albumId FROM ? p WHERE p.album = ? ORDER BY p.id DESC LIMIT 50', array(LYCHEE_TABLE_PHOTOS, $albumId));
	$photos = Database::get()->query($query);
	if (!$photos) {
	    Log::error(Database::get(), __METHOD__, __LINE__, Database::get()->error);
	    die('Error: Could not fetch the latest photos for album ' . $albumId . ' from the database.');
	}
	return $photos;
    }

	public function getAlbumIdByName($albumName){
		$query = Database::prepare(Database::get(), 'SELECT a.id AS albumId FROM ? a WHERE a.title="?" LIMIT 1', array(LYCHEE_TABLE_ALBUMS, mysqli_real_escape_string(Database::get(),$albumName)));
		$oResAlbumId = Database::get()->query($query);
		if (!$oResAlbumId) {
			Log::error(Database::get(), __METHOD__, __LINE__, Database::get()->error);
			die('Error: Could not fetch the album id from album name "' . $albumName . '" from the database.');
		}

		$lineAlbumId = $oResAlbumId->fetch_assoc();
		if(empty($lineAlbumId)){
			Log::error(Database::get(), __METHOD__, __LINE__, Database::get()->error);
			die('Error: the query result is empty for the album name "' . $albumName . '".');
		}
		
		return $lineAlbumId['albumId'];
	}

}

?>
