=== Mitsol tweets ===
Contributors: mitsol
Tags: tweets, tweet, twitter widget, twitter, api, oauth, social, tweet slider, tweet-hashtag, plugin, tweet display, tweet feed, REST, sidebar, post, shortcode, hashtags, favorites, page, template, keywords, user tweets, widgets, Post
Requires at least: 3.1
Tested up to: 4.1
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description == 
Mitsol tweets displays tweets vertically and also displays tweets in tweet slider(pro version) for any user-tweets, hashtag-tweets, search-tweets using Twitter v1.1 REST API. It caches tweet data from twitter into database to deal with twitter Api request limit(read doc). 

It's easy to handle and not dependant on jquery/javascript. Not only it shows tweets of a user but also tweets from any hashtag, search keyword with different result type(recent, mixed, popular). Although it uses cache to overcome twitter api request limit, you can use it to specify how long to cache data before getting new tweets from twitter.com.

You can customize all the way by showing/hiding items in a tweet and coloring, using font sizes for items(pro version). Read more features of free version below.

A complete tweet display for business or personal interest. Post ideas about the plugin in [pro version website](http://extensions.techhelpsource.com/forum "pro version website forum") & [follow me](https://twitter.com/mridulcs "follow me") on twitter  for updates.

= Features =

* Uses Twitter Api v1.1 to get tweets
* Requires only twitter application keys and tokens
* Displays tweets of a user or hashtag or custom search string
* Set to show number of tweets
* Shows mixed, recent, popular tweets of hashtag and search string 
* Show/hide each individual items of tweet
* Color settings
* View the request limit per 15 minutes 
* Responsive and loads fast
* Multiple display in any page
* Efficient way of calling to twitter to get data
* Show date according to your wp timezone settings 
* Have the ability to increase cache time
* Set fixed height & scroll
* Embed tweets directly into a page template, see faq
* More options in pro version


To show tweets in slider with different slider settings, show number of retweets & favourites, show images in tweet if exists, make url/hashtag/others in text linkable to twitter.com, include tweet replies, show header & scrolling plugin, and more settings then [upgrade to the Pro version](http://extensions.techhelpsource.com/wordpress/mitsol-tweets-pro "mitsol tweets pro"). Try out the [Pro demo](http://wordpress.techhelpsource.com/mitsol-tweets/ "mitsol tweets Demo").

== Installation ==

1. Install the plugin either via the WordPress plugin directory, or by uploading the files to your web server (in the `/wp-content/plugins/` directory).
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. Navigate to the 'mitsol tweets' settings page to configure your feed.
4. Use the shortcode `[mitsol_tweets_short_code]` in your page, post or widget to display your tweets.
5. You can display different tweets by specifying different parameters directly in the shortcode: `[mitsol_tweets_short_code tweet_user='twitter' num=20.....]`.
6. if there is any confusion in running/installing the plugin, contact immediately and view doc, problems will be solved

== Frequently Asked Questions ==

For a full documentation with FAQs and help with troubleshooting please visit the **[Documentation and FAQs](http://extensions.techhelpsource.com/mitsol_tweet_documentation_wordpress.htm)** section of the mitsol extensions website 

Furhermore, if there is any confusion in running the plugin, contact immediately and view doc, problems will be solved

= What are compulsory settings required to display tweets successfully ? =

You will need twitter app keys and tokens. Just follow the step-by-step instructions about creating twitter application and getting those keys and configuring plugin [here in the doc](http://extensions.techhelpsource.com/mitsol_tweet_documentation_wordpress.htm "Getting twitter app keys"). 

= Why isn't the tweets displaying? =

Make sure system requirements are met(look for the tab in settings page). Also make sure twitter application keys, tokens & username/hashtag/search string values are right

= Can I show photos, number of tweets & favourites, link urls/hashtags in text? =

To display these and many other things mentioned in description, all you need is to upgrade to the Pro version of the plugin. View demo of the Pro version on the [mitsol wp demo website](http://wordpress.techhelpsource.com/mitsol-tweets/ "mitsol tweets Demo"), and find out more about Pro version [here](http://extensions.techhelpsource.com/wordpress/mitsol-tweets-pro "Mitsol tweets Pro").

= What is twitter Api call rate limit & why it's so important? =

Twitter have Api request limit when call & display tweets, if plugin makes requests more than the limit specified your twitter application may be banned. Read 'important' section in [documentation](http://extensions.techhelpsource.com/mitsol_tweet_documentation_wordpress.htm "About request limit") for a detail explanation about that.

= How do I embed the custom tweet directly into a WordPress page template? =

You can embed your tweets directly into a template file by using the WordPress do_shortcode function: `do_shortcode('[mitsol_tweets_short_code]');`.

= How do i use short code and include attributes for specifying different settings for the feed display? =

Read info about it at the bottom of plugin setting page in wp dashboard. You can set all settings in shortcode, [click here](http://extensions.techhelpsource.com/mstweets_wordpress_shortcodes.htm "shortcode attributes") to see all shortcode attribute names.


== Screenshots ==

1. This pic shows how feed look like by default settings and colors
2. General settings
3. Post Layout settings
4. Colors settings
5. Rate limit status check

== Changelog ==

= 1.0 =
* Launch!

== Upgrade Notice ==

= 1.0 =
* launch! no upgrade notices right now