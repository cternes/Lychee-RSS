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

## License

[Apache License](./LICENSE)