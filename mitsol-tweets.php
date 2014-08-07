<?php
/*
Plugin Name: mitsol tweets
Plugin URI: http://extensions.techhelpsource.com/wordpress/mitsol-tweets-wordpress/
Description:Shows user tweets, hashtag tweets and matched search string tweets in slider and vertical display(free version)
Author: mitsol	
Version: 1.0
Author URI: http://extensions.techhelpsource.com/ 
License: GPLv2 or later 
*/ 
/* 
Copyright 2013 mitsol (email : mridulcs2012@gmail.com)
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.
This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

//for paid version - 

include_once dirname(__FILE__) . '/mitsol-tweets-functions.php';
include_once dirname(__FILE__) . '/admin/mitsol-tweets-admin.php';

register_activation_hook(__FILE__, 'mitsol_tweets_activation'); // code to be run after activate of plugin
register_deactivation_hook(__FILE__, 'mitsol_tweets_deactivation'); 
//add_action( 'wp_head', 'msfb_wall_settings_styles' ); //settings styles
//add_action('wp_enqueue_scripts', 'mitsol_tweets_styles');// sdd styles
//admin style 
add_action('admin_print_styles', 'mitsol_tweets_admin_css_all_page');	
add_action( 'admin_menu', 'mitsol_tweets_plugin_settings' ); //add  setings menu item in dashboard menu

add_shortcode("mitsol_tweets_short_code", 'mitsol_tweets_replace_scode'); 
//add_action( 'widgets_init', create_function('', 'return register_widget("facebook_wall_and_social_integration");') ); //add a widget at right of wp site
add_filter('widget_text', 'shortcode_unautop'); // enabling short code in default text widget also see echo do_shortcode($var); in function
add_filter('widget_text', 'do_shortcode',11); 
//add_action('wp_enqueue_scripts', 'mitsol_tweets_scripts'); //add scripts at last to load page fast
//add_action( 'wp_footer', 'mitsol_tweets_uniquejs' );
error_reporting(0); 




