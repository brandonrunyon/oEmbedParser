This gets and parses oEmbed data from youtube and vimeo oembed providers in PHP, it will return in XML and JSON formats.

Use:
include 'oEmbedParser.class.php';
		
$oe = new oEmbedParser('http://vimeo.com/7100569'); //optional parameter for url...

//or set/override after the fact... $oe->setURL('http://vimeo.com/7100569');

//you can set max height and width here if you want...
$oe->setDimensions(100, 100);

//supports method chaining
$oe->setFormat('json')->->setProvider('vimeo')->execute(); //set format and provider and then execute

//simple get() interface returns in set format
print $oe->get();

/*
returns following json:
{
	"type":"video",
	"version":"1.0",
	"provider_name":"Vimeo",
	"provider_url":"http:\/\/vimeo.com\/",
	"title":"Brad!",
	"author_name":"Casey Donahue",
	"author_url":"http:\/\/vimeo.com\/caseydonahue",
	"is_plus":"0",
	"html":"<iframe src=\"http:\/\/player.vimeo.com\/video\/7100569\" width=\"1280\" height=\"720\" frameborder=\"0\" webkitAllowFullScreen mozallowfullscreen allowFullScreen><\/iframe>",
	"width":1280,
	"height":720,
	"duration":118,
	"description":"Brad finally gets the attention he deserves.",
	"thumbnail_url":"http:\/\/b.vimeocdn.com\/ts\/294\/128\/29412830_1280.jpg",
	"thumbnail_width":1280,
	"thumbnail_height":720
	,"video_id":7100569
}

*/