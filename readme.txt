=== Plugin Name ===
Contributors: ccb23
Tags: qype, api, widget, links, tooltip, sidebar, plugin, post, search, link, media, place, suite, geo, geotagging, geotag
Requires at least: 2.5
Tested up to: 2.7.1
Stable tag: trunk

All-in-one-Qype-Suite "Qypifys" your Blog! Add tooltip-links to Qype Places, geotag Post & pimp sidebar with 3 nice Qype Widgets


== Description ==

**New:** [Checkout screencast how to install and use the plugin](http://www.youtube.com/watch?v=454UBWGIwD8) (german only)

The "All-in-one-Qype-Suite" Plugin comes with a set of useful features to "Qype-ify" your blog and auto-geotag your postings. Now, linkage to a place page
on Qype is as easy as never before. Search for a place via the Place-Chooser from the Media-Menu and see how the qype tag with
the chosen place ID is added. On your blog, this tag is resolved to a link to the place page, including a beautiful tooltip, showing basic
place information and thumbnail. [Check screenshots to see what this plugin is about](http://wordpress.org/extend/plugins/all-in-one-qype-suite/screenshots/).

And here comes another advantage: usually all Qype Places come with coordinates, so the plugin add those to the
meta information to auto-geotag you posting. This geodata is used by other wordpress plugins to show your posting on a map or enhance
your RSS/ATOM feed with geo information. By now, [Geo Mashup](http://www.wpgeo.com/) and [WP Geo](http://code.google.com/p/wordpress-geo-mashup/) are supported.

As this is not enough, you can pimp you sidebar with three different widgets:

* Latest review of a City,
* Latest reviews by a User 
* Button with number of Reviews.
 
Add the widgets via the wordpress widget page and adjust the settings. That's damn easy - you never have to touch html code anymore!

By now, the plugin comes as *english* and *german* version. The place results are cached for 24 hours to keep up blog preformance.

**Examples:**
[www.jump-around.eu](http://www.jump-around.eu)
[www.altpeter.de](http://www.altpeter.de/2009/02/28/scheisse-zu-gold/)


All data fetching operations are powered by the Qype API: http://www.qype.co.uk/developers/api

Rock on and Enjoy!

== Installation ==

**New:** [Checkout screencast how to install and use the plugin](http://www.youtube.com/watch?v=454UBWGIwD8) (german only)

1. Upload 'all-in-one-qype-suite' to the '/wp-content/plugins/' directory
1. Activate the plugin through the 'Plugins' menu in WordPress

### Usage ###
*How to add a tooltip-link to Place:*
Click on the "Qype Media Button" on top of the posting edit form. Search for place and choose the desired one. You can paginate through the result and get a live preview by mouseover the links. To see all places you previously used, click the favorites tab.
Cicking the place link adds atag [qype id="place id"] to your post. If you want to customize the link text, write the text in between the tags e.g.: [qype id="42"]custom text[/qype]
As soon as any place link is included, the coordinates of the first place are attached as meta geodata and can be used by other plugins. See main section for supported plugins.

Choose the Qype Theme from the Global "All-in-one-Qype-Suite" Configuration page.

*How to add Widgets:*
Add the widgets via the Widget Page to you sidebar. Drag it sidebar and configure Username, City etc. (For user & button widget a qype account is needed)
 

== Frequently Asked Questions ==
= Do I need a Qype Account? =

*No* - well for the tooltip function you don't, but for the button and review version you should have one...

= Is caching supported? =

*Yes*, the place results are cached for 24 hours to keep up blog preformance.


= I don't like the tooltips colours/layout/design - is there a way to change it? =

*Yes* of course - it's all CSS, feel free to overwrite the defaults

= Why can't I auto post my wordpress content to Qype? This function is missing - please implement! =

*Well*, as of now, the Qype API only allows reading, to there's no (easy) chance to add content by now :-( 

= Does this plugin support other languages? =

*Yes*, but by now, german and english are only supported - feel free to translate it!

= Ihh, I don't like the new Qype Design, can I get the old one back? =

*Yes*, the plugin comes with both themes and can be selected via the Admin Menu.

== Screenshots ==

1. **That's All-in-one-Qype-Suite**, using english Qype Classic theme
1. **Search for Place to add with live Preview**, using german Qype New theme
1. **Favorites Chooser**, using german Qype New theme
1. **Detailed view of all three Qype Widgets: City, User, Button**
1. **Detailed view of the tooltip to a place on Qype** using english Qype Classic theme
