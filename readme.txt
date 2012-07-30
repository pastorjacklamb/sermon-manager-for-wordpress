=== Sermon Manager ===
Contributors: wpforchurch
Donate link: http://wpforchurch.com/
Tags: church, sermon, sermons, preaching, podcasting
Requires at least: 3.4
Tested up to: 3.4.1
Stable tag: 1.5

Add audio and video sermons, manage speakers, series, and more to your church website

== Description ==

Sermon Manager is designed to help churches easily publish sermons online. You can add speakers, sermon series, Bible references etc. 

Sermons can have .mp3 files, as well as pdf, doc, ppt, etc. added to them. Video embeds from sites like Vimeo are also possible.

Images can be attached to any sermon, sermon series, speaker, or sermon topic. There are many filters available for displaying the images in your theme.

It will work with any theme, but themes can be customized to display content as you like. You'll find the template files in the /views folder. You can copy these into the root of your theme folder and customize to suit your site's design. If you need assistance, just post on the forums at WP for Church.

Super flexible shortcode for displaying sermons in page content.

iTunes podcasting support is now available!

=== Available Addons ===
* [Import MP3 to Sermon Manager](http://www.wpforchurch.com/product/import-mp3-to-sermon-manager/)
* Import from Sermon Browser to Sermon Manager is coming soon! Sign up for the [newsletter](http://www.wpforchurch.com/newsletter/) to make sure you don't miss the announcement!

[DEMO](http://demo.wpforchurch.com/sermon-manager/)

You can visit the [plugin's homepage](http://www.wpforchurch.com/products/sermon-manager-for-wordpress/ "Sermon Manager homepage") to get support.

[WP for Church](http://wpforchurch.com/ "WP for Church") provides plugins, themes, and training for churches using WordPress.


== Installation ==

1. Just use the "Add New" button in Plugin section of your WordPress blog's Control panel. To find the plugin there, search for 'Sermon Manager'
1. Activate the plugin 
1. Add a sermon through the Dashboard
1. To display the sermons on the frontend of your site, just visit the http://yourdomain.com/sermons if you have permalinks enabled or http://yourdomain.com/?post_type=wpfc_sermon if not. Or you can use the shortcode [sermons] in any page.
1. Visit [WP for Church](http://wpforchurch.com/ "WP for Church") for support

== Frequently Asked Questions ==

= How do I display sermons on the frontend? =

Visit the http://yourdomain.com/sermons if you have permalinks enabled or http://yourdomain.com/?post_type=wpfc_sermon if not. Or you can use the shortcode [sermons] in any page.

= How do I create a menu link? =

Go to Appearance => Menus. In the "Custom Links" box add "http://yourdomain.com/?post_type=wpfc_sermon" as the url and "Sermons" as the label; click "Add to Menu".

= I wish Sermon Manager could... =

I'm open to suggestions to make this a great tool for churches! Submit your feedback at [WP for Church](http://wpforchurch.com/ "WP for Church") 

= More Questions? =

Visit the [plugin homepage](http://wpforchurch.com/plugins/sermon-manager/ "Sermon Manager homepage")

== Screenshots ==
none yet :-)

== Changelog ==

= 1.5 =
* Improve page navigation styles with shortcode
* Improve admin interface & added a "Sermon Notes" field
* Fixed the views count for sermons
* Update function to add images to series & preachers
* Added podcasting with iTunes
* Properly enqueueing all JavaScript and CSS
* New template tags for easier theme customization

= 1.3.3 =
* Bug fix with menu not showing in some themes 

= 1.3.1 =
* Bug fix with Service Type not saving correctly 

= 1.3 = 
* Added a settings page
* Now translation ready!
* Added styling to the Recent Sermons Widget
* Added featured image to individual sermons 
* Added images to sermon topics 
* Created new functions to render sermon archive listing and single sermons
* Added better sorting fields on archive page
* Added shortcode to insert sort fields - sermon_sort_fields

= 1.2.1 =
* Enhanced shortcode to allow for Ajax pagination
* Requires a plugin for pagination in shortcode to work: http://wordpress.org/extend/plugins/wp-pagenavi/

= 1.2 =
* Shortcode completely updated with [documentation](http://www.wpforchurch.com/882/sermon-shortcode/) 

= 1.1.4 =
* Now you can add images to sermon series and preachers! 
* Widget now includes the sermon date
* Added icons for audio and video attachments

= 1.1.3 =
* Theme developers can add support for sermon manager to their theme with `add_theme_support( 'sermon-manager' );` in functions.php. For now, this will disable the loading of the jwplayer javascript
* Bug fix to load javascript for sermon player and verse popups on single sermon pages only
* minor CSS fix to increase font size of popup Bible passages

= 1.1.2 =
* bug fixes so everything saved correctly when doing autosave, quick edit, and bulk edit
* minor CSS fix for icon to display with additional files

= 1.1.1 =
* bug fixes to templating system
* minor CSS fixes

= 1.1 =
* New much improved templating system! 
* Bug fixes related to the loading of javascript and CSS

= 1.0 =
* Fixes related to WordPress 3.3; takes advantage of new tinymce editor

= 0.9 =
* Added WYSIWYG editor to the sermon description field

= 0.8 =
* Added Widgets

= 0.7 =
* Bug Fixes

= 0.6 =
* initial public release