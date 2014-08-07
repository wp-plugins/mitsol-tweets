<?php 
/* mitsol tweets version 1.0 */
function mitsol_tweets_replace_scode($atts) {  	
	//function microtime_float() { list($usec, $sec) = explode(" ", microtime()); return ((float)$usec + (float)$sec); }	$time_start = microtime_float();			
  $mstw_options = get_option('ms_twwall_plugin_general_settings');$options_layout = get_option('ms_twwall_plugin_postlayout_settings');
  $options_color = get_option('ms_twwall_plugin_color_settings'); 				    	 
  $show_post_items = ''; 
  	  //each string should not appear in another string as sub string 
  	  if($options_layout["mstw_showauthavatar"]) $show_post_items .= 'pauthavatar,'; if($options_layout["mstw_showauthname"]) $show_post_items .= 'authname,';
  	  if($options_layout["mstw_showposttext"]) $show_post_items .= 'posttxt,';   	    	  	 
  	  if($options_layout["mstw_showdate"]) $show_post_items .= 'date,'; 
  	    	    	    	    	  
  	  //get short code attributes, if any attribute not present then right hand side default value will be set
	  $atts = shortcode_atts( array(	    
	    'consumer_key' => ''.$mstw_options['mstw_twconkey'].'',
	  	'consumer_secret' => ''.$mstw_options['mstw_twconsecret'].'',
	  	'token' => ''.$mstw_options['mstw_twtoken'].'',
	  	'token_secret' => ''.$mstw_options['mstw_twtokensecret'].'',
	  	'tweet_from' => ''.$mstw_options['mstw_twfrom'].'',	  		
	  	'tweet_user' => ''.$mstw_options['mstw_twid'].'', 	 
	  	'tweet_hashtag' => ''.$mstw_options['mstw_hashtag'].'',
	  	'tweet_search' => ''.$mstw_options['mstw_searchstr'].'',
	  	'result_type' => ''.$mstw_options['mstw_resulttype'].'', 		
		'num' => ''. $mstw_options['mstw_postnum'] .'',
	  	'width' => ''. $mstw_options['mstw_twwidth'] .'',
	  	'height' => ''. $mstw_options['mstw_twheight'] .'',  
	  	'border' => ''. ($mstw_options['mstw_showborder']) ? "true" : '', 
	  	'cache_time' => ''. $mstw_options['mstw_cache_time'].'', 
	  	'cache_unit' => ''. $mstw_options['mstw_cache_time_unit'] .'', 
	  	'post_items' => ''.$show_post_items.'',	  	
	  	 
	  	'backg_color' => ''. $options_color["mstw_backcolor"] .'', 
	  	'post_brd_color' => ''. $options_color["mstw_postbordercolor"].'', 
	  	'author_text_color' => ''. $options_color["mstw_postauthorcolor"] .'', 
	  	'post_text_color' => ''. $options_color["mstw_posttextcolor"] .'', 
	  	'date_color' => ''. $options_color["mstw_datecolor"] .'',
	  		
    ), $atts);
/////////////////////////	  
//change oath class names as got a trick by adding if class not exists condition  when same class appeard in other plugins like bean-twets	  
/////////////////////////	  
/////////////check critical errors first 
$mstw_twconkey = trim($atts['consumer_key']);
$mstw_twconsecret = trim($atts['consumer_secret']);
$mstw_twtoken = trim($atts['token']);
$mstw_twtokensecret = trim($atts['token_secret']);
$mstw_twfrom = trim($atts['tweet_from']);
$mstw_twid = trim($atts['tweet_user']);
$mstw_hashtag = trim($atts['tweet_hashtag']);
$mstw_searchstr = trim($atts['tweet_search']);	  
$twitter_id="";	  
$error_flag=false;	
if (($mstw_twconkey == '')||($mstw_twconsecret == '')||($mstw_twtoken == '')||($mstw_twtokensecret == '')) {
	
	$mstw_html_content_first.= 'consumer key or consumer secret or access token or token secret missing.<br /><br />';$error_flag=true;
}  
//Check if tweet id has been defined
$tweet_from=false; $header_lnk =""; 
if (($mstw_twfrom == 'user')) { $twitter_id = $mstw_twid; $tweet_from=true; $header_lnk="https://twitter.com/". $twitter_id.""; } else {  if (($mstw_twfrom == 'hash')) { $twitter_id = $mstw_hashtag; $tweet_from=true; $header_lnk="https://twitter.com/hashtag/".$twitter_id."?src=hash"; } else { if(($mstw_twfrom == 'search')) { $twitter_id = $mstw_searchstr; $tweet_from=true; $header_lnk="https://twitter.com/search?src=typd&q=". $twitter_id.""; } } }  
if(!$tweet_from){ $mstw_html_content_first.= "'tweet from' value entered wrong in short code<br /><br />"; $error_flag=true; }
if (($twitter_id == '')) {
	$mstw_html_content_first.= "twitter username/hashtag/search value is empty<br /><br />";
	$error_flag=true;
} 
//Check if number of posts has been defined
$mstw_postnum = trim($atts['num']);
if (count($mstw_postnum)<=0) {
	$mstw_html_content_first.= "Please enter the number of posts value in wp plugin setting page or in short code<br /><br />";
	$error_flag=true;
}
////////////
if(!$error_flag)
{
//general from short codes	
$mstw_resulttype = $atts['result_type'];
$mstw_twwidth = $atts['width'];
$mstw_twheight = $atts['height'];
$mstw_showborder = $atts['border'] =="true" ? true : false ; 
$mstw_cache_time = $atts['cache_time'];
$mstw_cache_time_unit = $atts['cache_unit'];
/******************/
//post layouts //from short codes
$post_items=$atts['post_items'];
if (stripos($post_items, 'pauthavatar') !== false ) $mstw_showauthavatar = true;
if (stripos($post_items, 'authname') !== false ) $mstw_showauthname = true;
if (stripos($post_items, 'posttxt') !== false ) $mstw_showposttext = true;
if (stripos($post_items, 'date') !== false )  $mstw_showdate = true;
 //color
	  $mstw_backcolor=$atts["backg_color"]; 
	   $mstw_postbordercolor=$atts["post_brd_color"];	 
	   $mstw_postauthorcolor=$atts["author_text_color"];	  
	   $mstw_posttextcolor=$atts["post_text_color"];	  	 
	  $mstw_datecolor=$atts["date_color"];
////////////define other variables 	
$re_facebookwidth="11"; $temp_width=(int)$mstw_twwidth; 
if(is_int($temp_width)) { if(($temp_width>90)&&($temp_width<=100)){ $re_facebookwidth="12"; } if(($temp_width>80)&&($temp_width<=90)){ $re_facebookwidth="11"; } if(($temp_width>70)&&($temp_width<=80)){ $re_facebookwidth="10"; } if(($temp_width>60)&&($temp_width<=70)){ $re_facebookwidth="9"; } if(($temp_width>50)&&($temp_width<=60)){ $re_facebookwidth="8"; } if(($temp_width>40)&&($temp_width<=50)){ $re_facebookwidth="7"; } if(($temp_width>30)&&($temp_width<=40)){ $re_facebookwidth="6"; } if(($temp_width>20)&&($temp_width<=30)){ $re_facebookwidth="5"; } if(($temp_width>10)&&($temp_width<=20)){ $re_facebookwidth="4"; } if($temp_width<=10){ $re_facebookwidth="3"; } if($temp_width>100){ $re_facebookwidth="11"; }} 
//set time zone for date calculation //check if empty function is appropriate when string is empty check done
//date_default_timezone_set($mstw_timezone);
$mstw_cache_time = trim($mstw_cache_time);
if($mstw_cache_time_unit == 'minutes') $mstw_cache_time_unit = 60;
if($mstw_cache_time_unit == 'hours') $mstw_cache_time_unit = 60*60;
if($mstw_cache_time_unit == 'days') $mstw_cache_time_unit = 60*60*24;
if(trim($mstw_cache_time_unit) == '') $mstw_cache_time_unit = 60; //if empty
$cache_in_seconds = $mstw_cache_time * $mstw_cache_time_unit;
if($cache_in_seconds<180){  $cache_in_seconds=180; } //minimum 3 minutes to overcome rate limit

if($mstw_twfrom=='user') { $transient_name='mitsoltweets_user_'.$twitter_id.'_'.$mstw_postnum; 
	$params = array(
			'count' => $mstw_postnum,
			'exclude_replies' => 'true', //for free version
			'screen_name' => $twitter_id
	);
	$url = '/statuses/user_timeline';
} else {
	if($mstw_twfrom=='hash') { $hashorsrc= '#'.$twitter_id; $transient_name='mitsoltweets_hashtag_'.$twitter_id.'_'.$mstw_postnum;  } 
	else {  $hashorsrc= ''.$twitter_id.''; $transient_name='mitsoltweets_search_'.$twitter_id.'_'.$mstw_postnum; }
	$params = array(
			'count' => $mstw_postnum,
			'q' => ''. $hashorsrc .'',
			'result_type' => ''.$mstw_resulttype.''
	);
	$url = '/search/tweets';
}  

////////////Now get data from twitter api
$call_error = false; 
if ($cache_in_seconds!=0){	
	if ( false === ( $mstw_data_objs_first = get_transient( $transient_name ) ) || $mstw_data_objs_first === null ) {
		//Get the contents of the Facebook page
		$mstwData = Mstw_Wall_Get_Twitter_API_Data($url, $params, $mstw_twconkey,$mstw_twconsecret,$mstw_twtoken,$mstw_twtokensecret);				                
        //see if errors returned for user tweets only
        if((isset($mstwData->errors))||(isset($mstwData->error))) { $call_error= true;}
        //in case of search tweets, no errors returned but statuses returned null, so checks are following-
        if(isset($mstwData->statuses)){ $len=count($mstwData->statuses); $mstwData=$mstwData->statuses;   } else { $len=count($mstwData); }        
        ////finally check if errors is true or count is 0
        if(($len<=0)||($call_error)){ $mstw_html_content_first .= "Call to twitter api failed or no records returned. Make sure App keys, tokens & username/hashtag/search string values are right. Furthermore check system requirements(look for settings tab) are met.";}
        else
        {
        	$output= json_encode($mstwData); //for error identifying had to do decode in oath classes so needed encode to save to db
        	set_transient( $transient_name, $output, $cache_in_seconds );
        } 
		//goto skip; not works php v <5.3 
	} else {
		$mstwData = get_transient( $transient_name );  $mstw_html_content_first.="<!-- getting data from cache, seconds - ".$cache_in_seconds."-->";
		//If we can't find the transient then fall back to just getting the json from the api
		if ($mstwData == false)
		{ 
			//$mstw_data_objs_first = Mstw_Wall_Get_Twitter_API_Data($url, $params, $mstw_twconkey,$mstw_twconsecret,$mstw_twtoken,$mstw_twtokensecret); $mstw_html_content_first.="<!-- transient not found -->";
			//$mstw_data_objs = $mstw_data_objs_first; $mstwData = $mstw_data_objs;
			$call_error= true;
			$mstw_html_content_first .= "Error found while getting cached tweet data from database table for the purpose of avoiding twitter api call rate limit. Contact developer if problem still persists"; $mstw_html_content_first.="<!-- transient not found -->";
		}
		else 
		{
			$mstwData=json_decode($mstwData); $len=count($mstwData);
		}
	}
} 
/////////////data extraction.Check error first
if(($len<=0)||($call_error)){  $error_flag=true; } 
else 
{		
	/* if(($mstw_pluginviewset=="T")&&($mstw_heightoption=='fixed')){ 
	wp_register_script('mitsol_tw_feed_scroll_javascript', plugins_url('js/jquery.mCustomScrollbar.concat.min.js', __FILE__), array("jquery"), '1.0', true);
	wp_enqueue_script('mitsol_tw_feed_scroll_javascript'); } */ 
			   
	$random_length = 3;
	//generate a random id encrypt it and store it in $rnd_id
	$rnd_id = crypt(uniqid(rand(),1));
	//to remove any slashes that might have come
	$rnd_id = strip_tags(stripslashes($rnd_id));
	//Removing any . or / and reversing the string
	$rnd_id = str_replace(".","",$rnd_id);
	$rnd_id = strrev(str_replace("/","",$rnd_id));
	//finally I take the first 2 characters from the $rnd_id
	$rnd_id = substr($rnd_id,0,$random_length);
//////////////////////sample test post start ////use cache instaed
////use cached data for test post   
/////////////////////testpost ends
 //no strlen in case of number variable 
 //if($mstw_pluginviewset=="S"){ $mstw_html_content_first .= '<ul id="mstw_carousel_ul" class="mstw_carousel_ul">'; } 
 $mstw_counter=0; 
 
 /** Defines constants that are not defined in WordPress v3.4.x or below. **/
 if ( ! defined( 'MINUTE_IN_SECONDS' ) ) define( 'MINUTE_IN_SECONDS', 60 );
 if ( ! defined( 'HOUR_IN_SECONDS' ) ) define( 'HOUR_IN_SECONDS',   60 * MINUTE_IN_SECONDS );
 if ( ! defined( 'DAY_IN_SECONDS' ) ) define( 'DAY_IN_SECONDS',    24 * HOUR_IN_SECONDS   );
 if ( ! defined( 'WEEK_IN_SECONDS' ) ) define( 'WEEK_IN_SECONDS',    7 * DAY_IN_SECONDS    );
 if ( ! defined( 'YEAR_IN_SECONDS' ) ) define( 'YEAR_IN_SECONDS',  365 * DAY_IN_SECONDS    );  
 $sGMTOffset = (get_option('gmt_offset') * HOUR_IN_SECONDS); //check offset in 3.1

 foreach ($mstwData as $fdata) 
 { 
	//try block level elements not child of inline elements
	$mstw_html_content_first .= ($mstw_counter==0) ? '<div class="mstw-layout mstw-wall-box mstw-wall-box-first">' : '<div class="mstw-layout mstw-wall-box">';
	/////////show avatar
	if($mstw_showauthavatar){ 
		$mstw_html_content_first .= '<div class="avatar"><a href="https://twitter.com/'. $fdata->user->screen_name .'" target="_blank">';
		$mstw_html_content_first .= '<img class="mstw-wall-avatar" src="'. $fdata->user->profile_image_url .'" />';
		$mstw_html_content_first .= '</a></div>';
	}
	$mstw_html_content_first .= '<div class="mstw-wall-data"><span class="mstw-wall-message">'; //$mstw_html_content_first .= '<span class="mstw-wall-message">';
	if($mstw_showauthname) { $mstw_html_content_first .= '<a href="https://twitter.com/'. $fdata->user->screen_name .'" class="mstw-wall-message-from" target="_blank">'. $fdata->user->screen_name .'</a> '; } 
	
	if($mstw_showdate) {  $mstw_html_content_first .= '<span class="mstw-wall-message-date">'. mstw_human_time_diff(strtotime($fdata->created_at) , current_time('timestamp') - $sGMTOffset ) .' ago</span>'; }
	
	if($mstw_showposttext) { 
		if($fdata->text)
		{
			$mstw_html_content_first .= '<span style="display:block;margin-top:4px;">'. $fdata->text .'</span>';
		}
	}
	$mstw_html_content_first .= '</span>';					
	//date, link, share, icon								
	$mstw_html_content_first .= '</div> <div style="clear:both;"></div> </div>';						
	$mstw_counter++; 
    
  }//for each ends    
  ///////////construct current plugin styles start 
  $curmod_styles="";
  $heightop="";
  $heightop='height:'.$mstw_twheight.'px;'; $overflow= 'overflow: auto;';  
  $curmod_styles.= "\r\n#mstwmain-div$rnd_id .scroll-content  { overflow: auto; margin:7px 1px 2px 0; $heightop $overflow }";
  //outer border
  $showborder="";
  if($mstw_showborder){ $showborder='border:1px solid buttonface;'; }
  $curmod_styles.="\r\n#mstwmain-div$rnd_id { background-color: $mstw_backcolor; $showborder }";  
  //wall back color
  $curmod_styles.="\r\n#mstwmain-div$rnd_id .mstw-wall { background-color: $mstw_backcolor; }";
  //post border, pic size, show avatar
  $curmod_styles.="\r\n#mstwmain-div$rnd_id .mstw-layout { border: 1px solid $mstw_postbordercolor;}";
  if( !$mstw_showauthavatar) { $curmod_styles.="\r\n#mstwmain-div$rnd_id .mstw-wall-data { margin-left:0px; }"; }
  //auth name
  if($mstw_showauthname) {
  	$curmod_styles.="\r\n#mstwmain-div$rnd_id .mstw-wall-message-from { font-size: 13px !important; color:$mstw_postauthorcolor !important; }";
  	$curmod_styles.="\r\n#mstwmain-div$rnd_id .mstw-wall-message-from:hover,#mstwmain-div$rnd_id .mstw-wall-message-from:active,#mstwmain-div$rnd_id .mstw-wall-message-from:focus
  	{ font-size: 13px !important; color: $mstw_postauthorcolor !important; }";
  }
  $curmod_styles.="\r\n#mstwmain-div$rnd_id .mstw-wall-date { font-size:12px !important; color:$mstw_datecolor;}";
  //post text
  if($mstw_showposttext) {
  	$curmod_styles.="\r\n#mstwmain-div$rnd_id .mstw-wall-message{font-size: 12px !important; color: $mstw_posttextcolor;}";
  }
  //////////////styles end  
 } //records !=0 and not error found condition ends
} //no error on fbid, token... condition ends at first  
/* If we want to create more complex html code it's easier to capture the output buffer and return it */ 
 ob_start();  
?>
<?php if(!defined('MSTW_PLUGIN_ALREADY_LOADED')) { ?><link type="text/css" rel="stylesheet" href="<?php echo site_url(); ?>/wp-content/plugins/mitsol-tweets/css/jquery.mitsol.tweets.css" /> <?php define('MSTW_PLUGIN_ALREADY_LOADED', true); } ?>

<?php if(!$error_flag){ ?>
<style type="text/css">      
 <?php echo $curmod_styles; ?>
</style>  
<?php } ?>
   
<?php if($re_facebookwidth!=""){ $fb_width=$re_facebookwidth; } ?>
<div class="mstw-wall-main"> <div class="mstw-container"> <div class="mstw-row"> <div class="span_len<?php echo $fb_width; ?>">
<div id="mstwmain-div<?php echo $rnd_id; ?>">
<div id="mstw-content-main-<?php echo $rnd_id; ?>" class="scroll-content"><div class="mstw-wall"><!-- tw plugin version1.0 --><?php echo $mstw_html_content_first; ?></div></div>  
</div> 
</div></div></div></div>

 <?php  
    /* Return the buffer contents into a variable */
    $mstw_html_content = ob_get_contents(); 
    /* Empty the buffer without displaying it. We don't want the previous html shown */
    ob_end_clean(); 
    /* The text returned will replace our shortcode matching text */       
    return $mstw_html_content;
} 
/**
 * Determines the difference between two timestamps.
 * The difference is returned in a human readable format such as "1 hour",
 * "5 mins", "5 days".
 * @since 1.0
 * @param int $from Unix timestamp from which the difference begins.
 * @param int $to Optional. Unix timestamp to end the time difference. Default becomes time() if not set.
 * @return string time difference.
 */
function mstw_human_time_diff($from, $to = '' ) {
	if ( empty( $to ) )
		$to = time();
	$diff = (int) abs( $to - $from );

	if ( $diff < HOUR_IN_SECONDS ) {
		$mins = round( $diff / MINUTE_IN_SECONDS );
		if ( $mins <= 1 )
			$mins = 1;
		/* translators: min=minute */
		$since = sprintf( _n( '%s min', '%s mins', $mins ), $mins );
	} elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {
		$hours = round( $diff / HOUR_IN_SECONDS );
		if ( $hours <= 1 )
			$hours = 1;
		$since = sprintf( _n( '%s hour', '%s hours', $hours ), $hours );
	} elseif ( $diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS ) {
		$days = round( $diff / DAY_IN_SECONDS );
		if ( $days <= 1 )
			$days = 1;
		$since = sprintf( _n( '%s day', '%s days', $days ), $days );
	} elseif ( $diff < 30 * DAY_IN_SECONDS && $diff >= WEEK_IN_SECONDS ) {
		$weeks = round( $diff / WEEK_IN_SECONDS );
		if ( $weeks <= 1 )
			$weeks = 1;
		$since = sprintf( _n( '%s week', '%s weeks', $weeks ), $weeks );
	} elseif ( $diff < YEAR_IN_SECONDS && $diff >= 30 * DAY_IN_SECONDS ) {
		$months = round( $diff / ( 30 * DAY_IN_SECONDS ) );
		if ( $months <= 1 )
			$months = 1;
		$since = sprintf( _n( '%s month', '%s months', $months ), $months );
	} elseif ( $diff >= YEAR_IN_SECONDS ) {
		$years = round( $diff / YEAR_IN_SECONDS );
		if ( $years <= 1 )
			$years = 1;
		$since = sprintf( _n( '%s year', '%s years', $years ), $years );
	}
	return $since;
}
function mstw_exists($data){
	if(!$data || $data==null || $data=='undefined') return false;
	else return true;
}
//Get JSON object of feed data
function Mstw_Wall_Get_Twitter_API_Data($url, $params, $cons_key, $cons_secret, $oauth_token, $oauth_secret){
//require_once(ABSPATH . '/wp-content/plugins/mitsol-tweets/twitteroauth/twitteroauth.php'); //change abspath
require_once(plugin_dir_path( __FILE__ ).'twitteroauth/twitteroauth.php');	
$connection = new MitsolTweets_TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_secret);
// Get Tweets
$output = $connection->get($url, $params);
return $output;
}		

function mitsol_tweets_activation()
{ 
if(!get_option('ms_twwall_plugin_general_settings')) {
	$ms_twwall_plugin_general_settings = array(
		'mstw_twconkey' => '', 'mstw_twconsecret' => '', 'mstw_twtoken' => '', 'mstw_twtokensecret' => '', 
		'mstw_twfrom' => 'user',		
		'mstw_twid' => 'twitterapi',	
		'mstw_hashtag' => '',
		'mstw_searchstr' => '',
		'mstw_resulttype' => 'mixed', 'mstw_twwidth' => '100',
		'mstw_twheight' => '550',	    		
		'mstw_postnum' => '5',               		
		'mstw_showborder' => 'enabled',      																																
		);
	add_option( 'ms_twwall_plugin_general_settings', $ms_twwall_plugin_general_settings );
}
if(!get_option('ms_twwall_plugin_postlayout_settings')) {
	$ms_twwall_plugin_postlayout_settings = array(
	'mstw_showauthavatar' => 'enabled', 'mstw_showauthname' => 'enabled','mstw_showposttext' => 'enabled','mstw_showdate' => 'enabled',          		
	);
	add_option( 'ms_twwall_plugin_postlayout_settings', $ms_twwall_plugin_postlayout_settings );
} 
if(!get_option('ms_twwall_plugin_color_settings')) {
	$ms_twwall_plugin_color_settings = array(		
		'mstw_backcolor' => '#ffffff',
		'mstw_postbordercolor' => '#F0F0F0',
		'mstw_postauthorcolor' => '#3B5998',
		'mstw_posttextcolor' => '#333333',
		'mstw_datecolor' => '#777',			
		);
	add_option( 'ms_twwall_plugin_color_settings', $ms_twwall_plugin_color_settings);
}  		
}
function mitsol_tweets_deactivation()
{
   if (!current_user_can( 'activate_plugins' ))
        return;
   delete_option( 'ms_twwall_plugin_general_settings' );
   delete_option( 'ms_twwall_plugin_postlayout_settings' );   
   delete_option( 'ms_twwall_plugin_color_settings' );   		
  // delete_option( 'ms_twwall_plugin_slide_settings' );    
}
/* function mitsol_tweets_scripts() {	
} */







