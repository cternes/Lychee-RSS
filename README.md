Lychee-RSS
==========

Lychee-RSS is a plugin for [Lychee](https://github.com/electerious/Lychee) that will create an RSS-Feed out of your latest photos.

## Installation

This extension requires a working [Lychee](https://github.com/electerious/Lychee) v2.6 (or newer) on your server.

Navigate to the plugin-folder (`plugins/`) of your Lychee and run the following command:

	git clone https://github.com/cternes/Lychee-RSS.git rss
	
## Usage

The Lychee-RSS plugin allows you to create an RSS-Feed.

#### 1. All public photos

For a feed across your latest 50 public photos navigate your browser to your lychee installation and append the following url `/plugins/rss`.

For example if your lychee url is 

    http://myserver/lychee

You can access the RSS-Feed at the url

    http://myserver/lychee/plugins/rss

#### 2. Public photos of an album

If you want to create a feed for a specific album, you can pass the album name to the RSS-Generator

    http://myserver/lychee/plugins/rss?album=yourAlbumName

## (Optional) Configuration

If you want you can configure the title and description of the RSS-Feeds by changing variables in the `config.ini` file.

| Name | Description |
|:-----------|:------------|
| rssTitle | The title of the global RSS-Feed |
| rssDescription | The description of the global RSS-Feed |
| rssTitlePerAlbum | The title of the RSS-Feed for an album. The variable `{albumName}` will be replaced with the album name |
| rssDescriptionPerAlbum | The description of the RSS-Feed for an album. The variable `{albumName}` will be replaced with the album name |
| rssfilePhotoUrl | If set, a element with the file url to the photo is add to the item |

## (Optional) JSON Output

If you want to have the RSS-Feed in json representation, you can pass the output format to the RSS-Generator (_Note:_ RSS-Readers will not understand this format)

	http://myserver/lychee/plugins/rss?format=json

## License

[Apache License](./LICENSE)