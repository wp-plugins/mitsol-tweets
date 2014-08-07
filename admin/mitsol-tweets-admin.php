<?php  
function mitsol_tweets_admin_css_all_page() {	
    wp_enqueue_script('jquery');	
    wp_register_style($handle = 'mitsol_feed_bootstrap', $src = plugins_url('css/bootstrap.css', __FILE__), $deps = array(), $ver = '1.0.0', $media = 'all');
    wp_enqueue_style('mitsol_feed_bootstrap'); 
} 
/* admin functions */
function mitsol_tweets_plugin_settings() {
   add_menu_page('mitsol tweets settings', 'mitsol tweets', 'administrator', 'mitsol_tweets_settings', 'mitsol_tweets_display_settings');
} 
function getConnectionWithToken($cons_key, $cons_secret, $oauth_token, $oauth_secret)
{
	$connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_secret);	 
	return $connection;
}
function mitsol_tweets_display_settings () {
    if(!current_user_can( 'manage_options' )){
        wp_die( __( 
        'You do not have sufficient permissions to access this page.' 
        ));
    }  
	$options = get_option('ms_twwall_plugin_general_settings');
	$options_layout = get_option('ms_twwall_plugin_postlayout_settings');
	$options_color = get_option('ms_twwall_plugin_color_settings');
			
    if((isset($_REQUEST["mstw_active_tab"]))&&($_REQUEST["mstw_active_tab"] == "1"))
	{ 	   
	   $mstw_twconkey=$_REQUEST["mstw_twconkey"]; $options['mstw_twconkey']= $mstw_twconkey;
	   $mstw_twconsecret=$_REQUEST["mstw_twconsecret"]; $options['mstw_twconsecret']= $mstw_twconsecret;
	   $mstw_twtoken=$_REQUEST["mstw_twtoken"]; $options['mstw_twtoken']= $mstw_twtoken;
	   $mstw_twtokensecret=$_REQUEST["mstw_twtokensecret"]; $options['mstw_twtokensecret']= $mstw_twtokensecret;
	   
	   $mstw_twfrom=$_REQUEST["mstw_twfrom"]; $options['mstw_twfrom']= $mstw_twfrom;
	   $mstw_twid=$_REQUEST["mstw_twid"]; $options['mstw_twid']= $mstw_twid;
	   $mstw_hashtag=$_REQUEST["mstw_hashtag"]; $options['mstw_hashtag']= $mstw_hashtag;
	   $mstw_searchstr=$_REQUEST["mstw_searchstr"]; $options['mstw_searchstr']= $mstw_searchstr;
	   $mstw_resulttype=$_REQUEST["mstw_resulttype"]; $options['mstw_resulttype']= $mstw_resulttype;	  
	   
   	   $mstw_twwidth=$_REQUEST["mstw_twwidth"]; $options['mstw_twwidth']= $mstw_twwidth;   	   
   	   $mstw_twheight=$_REQUEST["mstw_twheight"]; $options['mstw_twheight']= $mstw_twheight;  	   	  
       $mstw_postnum=$_REQUEST["mstw_postnum"]; $options['mstw_postnum']= $mstw_postnum; 	
 	   
	   $mstw_showborder=$_REQUEST["mstw_showborder"]; $options['mstw_showborder']= $mstw_showborder;	   
	   	   
	   update_option( 'ms_twwall_plugin_general_settings', $options ); 	   	   
	} 
	//layout
	if((isset($_REQUEST["mstw_active_tab"]))&&($_REQUEST["mstw_active_tab"] == "2"))
	{
	   $mstw_showauthavatar = $_REQUEST["mstw_showauthavatar"]; $options_layout['mstw_showauthavatar']= $mstw_showauthavatar;
	   $mstw_showauthname = $_REQUEST["mstw_showauthname"]; $options_layout['mstw_showauthname']= $mstw_showauthname;
	   $mstw_showposttext = $_REQUEST["mstw_showposttext"]; $options_layout['mstw_showposttext']= $mstw_showposttext;	   	   	   	   
	   $mstw_showdate=$_REQUEST["mstw_showdate"]; $options_layout['mstw_showdate']= $mstw_showdate;
	   
   	   update_option( 'ms_twwall_plugin_postlayout_settings', $options_layout );
	}
	//color
    if((isset($_REQUEST["mstw_active_tab"]))&&($_REQUEST["mstw_active_tab"] == "3"))
	{ 
	   $mstw_backcolor=$_REQUEST["mstw_backcolor"]; $options_color['mstw_backcolor']= $mstw_backcolor;
	   $mstw_postbordercolor=$_REQUEST["mstw_postbordercolor"]; $options_color['mstw_postbordercolor']= $mstw_postbordercolor;	   
	   $mstw_postauthorcolor=$_REQUEST["mstw_postauthorcolor"]; $options_color['mstw_postauthorcolor']= $mstw_postauthorcolor;	   
	   $mstw_posttextcolor=$_REQUEST["mstw_posttextcolor"]; $options_color['mstw_posttextcolor']= $mstw_posttextcolor;	   
	   $mstw_datecolor=$_REQUEST["mstw_datecolor"]; $options_color['mstw_datecolor']= $mstw_datecolor;	   	     	 
 	   	   	   	   	   	   	   	      	  		   	   	   	   	   	   	   	   	   
	  update_option( 'ms_twwall_plugin_color_settings', $options_color );	   	   
	}
	
	if((isset($_REQUEST["mstw_active_tab"]))&&($_REQUEST["mstw_active_tab"] == "5"))
	{
	   $mstw_twconkey22=$_REQUEST["mstw_twconkey2"];
	   $mstw_twconsecret22=$_REQUEST["mstw_twconsecret2"];
	   $mstw_twtoken22=$_REQUEST["mstw_twtoken2"];
	   $mstw_twtokensecret22=$_REQUEST["mstw_twtokensecret2"];	   	  
	   
	  $rate_limit_errors="";
	  if (($mstw_twconkey22 == '')||($mstw_twconsecret22 == '')||($mstw_twtoken22 == '')||($mstw_twtokensecret22 == '')) {
		
			$rate_limit_errors.= 'consumer key or consumer secret or access token or token secret is empty.<br /><br />';
	  }
	  else {
	  	
	  	$transient_name='mitsoltweets_request_status'; $error_set=false;

	  	if ( false === ( $data = get_transient( $transient_name ) ) || $data === null ) {
	  		require_once(plugin_dir_path( __FILE__ ).'twitteroauth/twitteroauth.php');
	  		//require_once(ABSPATH . '/wp-content/plugins/mitsol-tweets/twitteroauth/twitteroauth.php');
	  		// Connect
	  		$connection = new MitsolTweets_TwitterOAuth($mstw_twconkey22, $mstw_twconsecret22, $mstw_twtoken22, $mstw_twtokensecret22);
	  		$params=array();
	  		$params = array('resources'=>'statuses,search' );
	  		$url= '/application/rate_limit_status';
	  		 
	  		$data = $connection->get($url, $params); $error_message=""; 
	  		if((isset($data->errors))||(isset($data->error))){ if(isset($data->errors[0]->message)) { $error_message = "error message - ".$data->errors[0]->message; } }
	  		if((isset($data->errors))||(isset($data->error))||(count($data)<=0)) 
	  		{ 
	  			$error_set=true; $rate_limit_errors.= "<div style='background-color:aliceblue;padding: 10px 5px; font-weight:bold; font-size:18px; margin:5px 0 5px 0;'>Call to twitter Api failed  or no records returned. Make sure system requirements are met(check tab), app keys and tokens are correct.<br/>
	  		".$error_message."</div>"; 
	  		} 	  			  		
	  		else
	  		{
	  			$output = json_encode($data);	  			
	  			set_transient( $transient_name, $output, 30);
	  		}
	  	} else {
	  		$data= get_transient( $transient_name );
	  		if ($data == false)
	  		{
	  			$error_set = true;
	  			$rate_limit_errors.="Error found while getting cached request limit data from database table for the purpose of avoiding twitter api call rate limit on getting request limit status. Contact developer if problem persists";
	  		} else {  $data=json_decode($data); } 
	  		
	  	}
	  		  		  		  
	  if(!$error_set) { 
	  $offset_seconds=(get_option('gmt_offset') * (60*60));
	  $prop_search='/search/tweets'; $prop_status='/statuses/user_timeline';
	  $search=$data->resources->search->$prop_search; $status=$data->resources->statuses->$prop_status;	  
	  $value="<div><b>Following are request limit status per above keys and token combination. 'Request type' column shows, for what purpose requests are made. 'Total requests remaining' column shows how many requests are remaining from request limit per 15 minutes. 
          Last column shows when request limit will be reset after current 15 minutes time expires. Remaining requests value should never exceeds request limit value.</b></div>
	  		
	  <table border='1' align='left' style='border:1px solid gray !important; background-color:aliceblue !important;padding: 10px 5px !important;font:14px Verdana,Arial,sans-serif !important; margin:5px 0 5px 0;display:block;'>
	  
	  <thead><tr style='background-color: buttonface !important;'><td style='padding:8px;'>Request type</td><td style='padding:8px;'>Total requests remaining</td><td style='padding:8px;'>Request limit will be reset at</td></tr></thead>
	  
	  <tbody>
	  <tr><td style='padding:8px;'>tweets from user </td><td style='padding:8px;'>".$status->remaining." out of ".$status->limit."</td><td style='padding:8px;'>". date( "F j, Y, g:i a" , $status->reset + $offset_seconds)."</td></tr>
	  <tr><td style='padding:8px;'>tweets from hashtag/search string</td><td style='padding:8px;'>".$search->remaining." out of ".$search->limit."</td><td style='padding:8px;'>".date( "F j, Y, g:i a" , $search->reset + $offset_seconds)."</td></tr>
	  </tbody>
	  
	  </table>"; } 
	  //$tweets= json_encode($tweets);
	  }
	}
	//general 	
	$mstw_twconkey = ($options['mstw_twconkey'] != '') ? $options['mstw_twconkey'] : '';
	$mstw_twconsecret = ($options['mstw_twconsecret'] != '') ? $options['mstw_twconsecret'] : '';
	$mstw_twtoken = ($options['mstw_twtoken'] != '') ? $options['mstw_twtoken'] : '';
	$mstw_twtokensecret = ($options['mstw_twtokensecret'] != '') ? $options['mstw_twtokensecret'] : '';	
	$mstw_twfrom = ($options['mstw_twfrom'] != '') ? $options['mstw_twfrom'] : 'user';
	$mstw_twid = ($options['mstw_twid'] != '') ? $options['mstw_twid'] : '';
	$mstw_hashtag = ($options['mstw_hashtag'] != '') ? $options['mstw_hashtag'] : '';
	$mstw_searchstr = ($options['mstw_searchstr'] != '') ? $options['mstw_searchstr'] : '';
	$mstw_resulttype = ($options['mstw_resulttype'] != '') ? $options['mstw_resulttype'] : 'mixed';	
	$mstw_twwidth = ($options['mstw_twwidth'] != '') ? $options['mstw_twwidth'] : '550';
	$mstw_twheight = ($options['mstw_twheight'] != '') ? $options['mstw_twheight'] : '550';		         	
    $mstw_postnum = ($options['mstw_postnum'] != '') ? $options['mstw_postnum'] : '10';    		       
    $mstw_showborder = ($options['mstw_showborder'] == 'enabled') ? 'checked' : '' ;     
    //post layout
    $mstw_showauthavatar =  ($options_layout['mstw_showauthavatar'] == 'enabled') ? 'checked' : '' ;
    $mstw_showauthname =   ($options_layout['mstw_showauthname'] == 'enabled') ? 'checked' : '' ;
    $mstw_showposttext =  ($options_layout['mstw_showposttext'] == 'enabled') ? 'checked' : '' ;                       
    $mstw_showdate = ($options_layout['mstw_showdate'] == 'enabled') ? 'checked' : '' ;            
	//color	 	
	$mstw_backcolor = ($options_color['mstw_backcolor'] != '') ? $options_color['mstw_backcolor'] : '#ffffff';
	$mstw_postbordercolor = ($options_color['mstw_postbordercolor'] != '') ? $options_color['mstw_postbordercolor'] : '#F0F0F0';
	$mstw_postauthorcolor=($options_color['mstw_postauthorcolor'] != '') ? $options_color['mstw_postauthorcolor'] : '#3B5998';
	$mstw_posttextcolor=($options_color['mstw_posttextcolor'] != '') ? $options_color['mstw_posttextcolor'] : '#333333';		
	$mstw_datecolor=($options_color['mstw_datecolor'] != '') ? $options_color['mstw_datecolor'] : '#777';	 			
	//rate limit box pre-populate 
	$mstw_twconkey2 = ($_REQUEST["mstw_twconkey2"]!='') ? $_REQUEST["mstw_twconkey2"] : $options["mstw_twconkey"];
	$mstw_twconsecret2 = ($_REQUEST["mstw_twconsecret2"] != '') ? $_REQUEST["mstw_twconsecret2"] : $options["mstw_twconsecret"];
	$mstw_twtoken2 = ($_REQUEST["mstw_twtoken2"] != '') ? $_REQUEST["mstw_twtoken2"] : $options["mstw_twtoken"];
	$mstw_twtokensecret2 = ($_REQUEST["mstw_twtokensecret2"] != '') ? $_REQUEST["mstw_twtokensecret2"] : $options["mstw_twtokensecret"];
	
   if(isset($_REQUEST["mstw_active_tab"])) {  
   if($_REQUEST["mstw_active_tab"] == "1"){ $setting_section="General"; }    if($_REQUEST["mstw_active_tab"] == "2"){ $setting_section="Post layout"; }    if($_REQUEST["mstw_active_tab"] == "3"){ $setting_section="Design"; } 
	    $mstw_success_error='<div class="alert alert-success">  
        <a class="close" data-dismiss="alert">x</a>  
        '. $setting_section .' settings saved successfully 
        </div>';
	    
	    if($_REQUEST["mstw_active_tab"] == "5"){ //for rate limit check nothing to show
	        $mstw_success_error='';
	    }
	} 	 
	
	
   (!isset($_REQUEST["mstw_active_tab"])) ? $mstw_active_tab="1": $mstw_active_tab = $_REQUEST["mstw_active_tab"];

    if($mstw_active_tab =="1"){ $active=""; $active2='style="display:none;"';$active3='style="display:none;"'; $active4='style="display:none;"'; $active5='style="display:none;"'; $active6='style="display:none;"'; $activetab='class="active"'; $activetab2='';  $activetab3=''; $activetab4=''; $activetab5=''; $activetab6=''; }
    if($mstw_active_tab =="2"){ $active2=""; $active='style="display:none;"'; $active3='style="display:none;"'; $active4='style="display:none;"'; $active5='style="display:none;"'; $active6='style="display:none;"'; $activetab2='class="active"'; $activetab='';  $activetab3=''; $activetab4=''; $activetab5=''; $activetab6=''; }
	if($mstw_active_tab =="3"){ $active3=""; $active='style="display:none;"';  $active2='style="display:none;"';  $active4='style="display:none;"'; $active5='style="display:none;"'; $active6='style="display:none;"';  $activetab3='class="active"'; $activetab='';  $activetab2=''; $activetab4=''; $activetab5=''; $activetab6=''; }	
	if($mstw_active_tab =="5"){ $active5=""; $active3='style="display:none;"'; $active='style="display:none;"'; $active2='style="display:none;"'; $active4='style="display:none;"'; $active6='style="display:none;"'; $activetab5='class="active"'; $activetab3=''; $activetab='';  $activetab2=''; $activetab4=''; $activetab6=''; }

?>
<div class="msmain_container" style="margin-top:10px;">	
<script type="text/javascript">	
var ms_js = jQuery.noConflict();  	
ms_js(function(){		 	
 ms_js("#ms_1st_tablink").click(function() {
     ms_js("#ms_1st_tab").show();  ms_js("#ms_2nd_tab").hide();  ms_js("#ms_third_tab").hide();	 ms_js("#ms_fourth_tab").hide();  	
	 ms_js("#ms_fifth_tab").hide(); ms_js("#ms_sixth_tab").hide();
	  
  	 ms_js("#ms_1st_list").addClass("active"); ms_js("#ms_2nd_list").removeClass("active"); ms_js("#ms_third_list").removeClass("active");
	 ms_js("#ms_fourth_list").removeClass("active"); ms_js("#ms_fifth_list").removeClass("active"); ms_js("#ms_sixth_list").removeClass("active"); 	 
  }); 
  ms_js("#ms_2nd_tablink").click(function() {
     ms_js("#ms_2nd_tab").show(); 
	 ms_js("#ms_1st_tab").hide(); 
	 ms_js("#ms_third_tab").hide();	
	 ms_js("#ms_fourth_tab").hide();
	 ms_js("#ms_fifth_tab").hide(); 
	 ms_js("#ms_sixth_tab").hide();
	  
  	 ms_js("#ms_2nd_list").addClass("active"); 	 
	 ms_js("#ms_1st_list").removeClass("active");
  	 ms_js("#ms_third_list").removeClass("active"); 
	 ms_js("#ms_fourth_list").removeClass("active"); 
	 ms_js("#ms_fifth_list").removeClass("active");ms_js("#ms_sixth_list").removeClass("active"); 
  });
   ms_js("#ms_third_tablink").click(function() {
     ms_js("#ms_1st_tab").hide(); 
	 ms_js("#ms_2nd_tab").hide(); 
	 ms_js("#ms_sixth_tab").hide();
	 ms_js("#ms_third_tab").show();	 
 	 ms_js("#ms_fourth_tab").hide();
 	ms_js("#ms_fifth_tab").hide(); ms_js("#ms_sixth_tab").hide();	
	  
  	 ms_js("#ms_1st_list").removeClass("active"); 	 
	 ms_js("#ms_2nd_list").removeClass("active");
  	 ms_js("#ms_third_list").addClass("active"); 
	 ms_js("#ms_fourth_list").removeClass("active"); 
	 ms_js("#ms_fifth_list").removeClass("active"); ms_js("#ms_sixth_list").removeClass("active"); 
  });

   ms_js("#ms_fifth_tablink").click(function() {
	     ms_js("#ms_1st_tab").hide(); 
		 ms_js("#ms_2nd_tab").hide(); 
		 ms_js("#ms_third_tab").hide();
		 ms_js("#ms_sixth_tab").hide();
		 ms_js("#ms_fourth_tab").hide();
		 ms_js("#ms_fifth_tab").show(); 	ms_js("#ms_sixth_tab").hide(); 	
		  
	     ms_js("#ms_fourth_list").removeClass("active");
	  	 ms_js("#ms_1st_list").removeClass("active"); 	 
		 ms_js("#ms_2nd_list").removeClass("active");
	  	 ms_js("#ms_third_list").removeClass("active"); 
	  	 ms_js("#ms_fifth_list").addClass("active"); ms_js("#ms_sixth_list").removeClass("active"); 
});
  ms_js("#ms_sixth_tablink").click(function() {
	     ms_js("#ms_1st_tab").hide(); 
		 ms_js("#ms_2nd_tab").hide(); 
		 ms_js("#ms_third_tab").hide();
		 ms_js("#ms_fourth_tab").hide(); 
		 ms_js("#ms_fifth_tab").hide(); 
		 ms_js("#ms_sixth_tab").show(); 	 	
		  
	     ms_js("#ms_fourth_list").removeClass("active");
	  	 ms_js("#ms_1st_list").removeClass("active"); 	 
		 ms_js("#ms_2nd_list").removeClass("active");
	  	 ms_js("#ms_third_list").removeClass("active");
	  	 
	  	ms_js("#ms_fourth_list").removeClass("active");  ms_js("#ms_fifth_list").removeClass("active");
	  	ms_js("#ms_sixth_list").addClass("active"); 
  });
  ms_js(".msmain_container .close").click(function() {
    ms_js(this).parent("div").hide();
  });
});	 
//jQuery('.cff-colorpicker').wpColorPicker();
</script>

<style type="text/css"> 
.msmain_container select,.msmain_container 
textarea,.msmain_container 
input[type="text"],.msmain_container 
input[type="password"],.msmain_container 
input[type="datetime"],.msmain_container 
input[type="datetime-local"],.msmain_container 
input[type="date"],.msmain_container 
input[type="month"],.msmain_container 
input[type="time"],.msmain_container 
input[type="week"],.msmain_container 
input[type="number"],.msmain_container 
input[type="email"],.msmain_container 
input[type="url"],.msmain_container 
input[type="search"],.msmain_container 
input[type="tel"],.msmain_container 
input[type="color"],.msmain_container 
.uneditable-input {
  height: 28px; 
}
</style>

<div class="container-fluid" style="margin-top:30px; padding-top:20px; background-color:white">  
<div class="row-fluid">  
<div class="span12"> <?php echo $mstw_success_error; ?> 
<ul class="nav nav-tabs">  		 
<li  id="ms_1st_list" style="cursor:pointer; cursor:hand" <?php echo $activetab; ?> ><a id="ms_1st_tablink">General</a></li>  
<li id="ms_2nd_list" style="cursor:pointer; cursor:hand" <?php echo $activetab2; ?>><a id="ms_2nd_tablink">Post layout</a></li>  
<li id="ms_third_list" style="cursor:pointer; cursor:hand"  <?php echo $activetab3; ?>><a id="ms_third_tablink">Design</a></li>
<li id="ms_fifth_list" style="cursor:pointer; cursor:hand"  <?php echo $activetab5; ?>><a id="ms_fifth_tablink">Check rate limit</a></li>
<li id="ms_sixth_list" style="cursor:pointer; cursor:hand" <?php echo $activetab6 ; ?>><a id="ms_sixth_tablink">System requirements</a></li>
  
</ul>
<div  id="ms_1st_tab" <?php echo $active; ?>> 
<form method="post" name="general_options" action="" class="form-horizontal">  
        <fieldset>  
          <legend>General settings</legend>
          <div class="control-group">  
            <label class="control-label" for="mstw_twconkey">Consumer key</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_twconkey" value="<?php echo esc_attr_e($mstw_twconkey); ?>" id="mstw_twconkey" />
			<p class="help-block">Read <a target="_blank" href="http://extensions.techhelpsource.com/mitsol_tweet_documentation_wordpress.htm">documentation</a> about how to create twitter app and get these keys</p>			 
            </div>  
          </div>
          <div class="control-group">  
            <label class="control-label" for="mstw_twconsecret">Consumer secret</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_twconsecret" value="<?php echo esc_attr_e($mstw_twconsecret); ?>" id="mstw_twconsecret" />
			
            </div>  
          </div> 
           <div class="control-group">  
            <label class="control-label" for="mstw_twtoken">Access token</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_twtoken" value="<?php echo esc_attr_e($mstw_twtoken); ?>" id="mstw_twtoken" />			
            </div>  
          </div> 
          <div class="control-group">  
            <label class="control-label" for="mstw_twtokensecret">Access token secret</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_twtokensecret" value="<?php echo esc_attr_e($mstw_twtokensecret); ?>" id="mstw_twtokensecret" />			 
            </div>  
          </div> 
          <div class="control-group">  
            <label class="control-label" for="mstw_twfrom">Get tweets from</label>  
            <div class="controls">              
            <input type="radio" name="mstw_twfrom" value="user" <?php if($mstw_twfrom=="user"){ echo "checked"; }  ?>>User name             
            <input style="margin-left: 10px;" type="radio" name="mstw_twfrom" value="hash" <?php if($mstw_twfrom=="hash"){ echo "checked"; }  ?>>Hashtag
            <input style="margin-left: 10px;" type="radio" name="mstw_twfrom" value="search" <?php if($mstw_twfrom=="search"){ echo "checked"; }  ?>>Search string<BR>
            <p class="help-block">select whether tweets will be shown of a user or hashtag or custom search string</p>                    
            </div>  
          </div>  
          <div class="control-group">  
            <label class="control-label" for="mstw_twid">Twitter user name</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_twid" value="<?php echo esc_attr_e($mstw_twid); ?>" id="mstw_twid" />			 
            </div>  
          </div> 
                    <div class="control-group">  
            <label class="control-label" for="mstw_hashtag">Hashtag(excluding #)</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_hashtag" value="<?php echo esc_attr_e($mstw_hashtag); ?>" id="mstw_hashtag" />				 
            </div>  
          </div> 
                    <div class="control-group">  
            <label class="control-label" for="mstw_searchstr">Search string</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_searchstr" value="<?php echo esc_attr_e($mstw_searchstr); ?>" id="mstw_searchstr" />			 
            </div>  
          </div> 
          <div class="control-group"> 
          <label class="control-label" for="mstw_resulttype">Result type(for hashtag/search string)</label>                       
            <div class="controls">                
               <select name="mstw_resulttype" style="width: 150px;">
                   <option value="mixed" <?php if($mstw_resulttype== "mixed") echo 'selected' ?> >mixed</option>
                   <option value="recent" <?php if($mstw_resulttype == "recent") echo 'selected' ?> >recent</option>
                   <option value="popular" <?php if($mstw_resulttype == "popular") echo 'selected' ?> >popular</option>                                                        
              </select>              
            </div>  
          </div>
 
          <div class="control-group">  
            <label class="control-label" for="mstw_postnum">Show number of posts</label>  
            <div class="controls">  
			<input type="text"  class="input-xlarge" name="mstw_postnum" value="<?php echo esc_attr_e($mstw_postnum); ?>" id="mstw_postnum" />
            </div>  
          </div>    		   		 
		   <div class="control-group">  
            <label class="control-label" for="mstw_twwidth">Width</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_twwidth" value="<?php echo esc_attr_e($mstw_twwidth); ?>" id="mstw_twwidth" />
			<p class="help-block">width value in %. Ex - 100,80,50..</p> 
            </div>  
          </div>  
			 		  		        
		  <div class="control-group">  
            <label class="control-label" for="mstw_twheight">Height(fixed)</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_twheight" value="<?php echo esc_attr_e($mstw_twheight); ?>" id="mstw_twheight" />                        
            </div>  
          </div>                  		 		  
                                                 
          <div class="control-group"> 
          <label class="control-label" for="msfb_cache_time_unit">Check for new tweets directly from twitter in every</label>                       
            <div class="controls">  
               <input name="msfb_cache_time" style="width: 100px;" id="msfb_cache_time" type="text" value="<?php echo esc_attr_e( $msfb_cache_time); ?>" size="4" />
               <select name="msfb_cache_time_unit" style="width: 150px;">
                   <option value="minutes" <?php if($msfb_cache_time_unit== "minutes") echo 'selected' ?> >minutes</option>
                   <option value="hours" <?php if($msfb_cache_time_unit == "hours") echo 'selected' ?> >hours</option>
                   <option value="days" <?php if($msfb_cache_time_unit == "days") echo 'selected' ?> >days</option>                                                        
              </select>
              <p class="help-block">Minimum 3 minutes cache time is always set per unique plugin to overcome twitter Api request limit. If you want to cache tweets more than 3 minutes in database then enter values in textbox so that on next page load, tweets will be shown from cached data until specified time expired.</p>
            </div>  
          </div>                     		    		  						
          <div class="control-group">  
            <label class="control-label" for="mstw_showborder">Show outer border</label>  
            <div class="controls">  
              <label class="checkbox">  
                <input type="checkbox" <?php echo esc_attr_e($mstw_showborder); ?> name="mstw_showborder" id="mstw_showborder" value="enabled" />  
              </label>  
            </div>  
          </div> 	
                    <div class="control-group">              
            <div class="controls">  
            <b>More settings available in pro version</b>  
            </div>  
          </div>		               
          <div class="form-actions"> 
  		   <input type="hidden" name="mstw_active_tab" value="1" /> 
            <input type="submit" name="submit" class="btn btn-primary" value="Update"/>   
          </div>  
        </fieldset>  
</form>  
  
</div> 
<div id="ms_2nd_tab" <?php echo $active2; ?>>  
<form method="post" name="color_options" action="" class="form-horizontal">  
        <fieldset>  
          <legend>Post Layout Settings</legend>
          
           <div class="control-group">  
            <label class="control-label" for="mstw_showauthavatar">Show tweet author avatar</label>  
            <div class="controls">  
              <label class="checkbox">  
                <input type="checkbox" <?php echo esc_attr_e($mstw_showauthavatar); ?> name="mstw_showauthavatar" id="mstw_showauthavatar" value="enabled" />  
              </label>  
            </div>  
          </div> 
          
         <div class="control-group">  
            <label class="control-label" for="mstw_showauthname">Show tweet author name</label>  
            <div class="controls">  
              <label class="checkbox">  
                <input type="checkbox" <?php echo esc_attr_e($mstw_showauthname); ?> name="mstw_showauthname" id="mstw_showauthname" value="enabled" />  
              </label>  
            </div>  
          </div> 
          
         <div class="control-group">  
            <label class="control-label" for="mstw_showposttext">Show tweet text</label>  
            <div class="controls">  
              <label class="checkbox">  
                <input type="checkbox" <?php echo esc_attr_e($mstw_showposttext); ?> name="mstw_showposttext" id="mstw_showposttext" value="enabled" />  
              </label>  
            </div>  
          </div> 
          <div class="control-group">  
            <label class="control-label" for="mstw_showdate">Show Date</label>  
            <div class="controls">  
              <label class="checkbox">  
                <input type="checkbox" <?php echo esc_attr_e($mstw_showdate); ?> name="mstw_showdate" id="mstw_showdate" value="enabled" />  
              </label>  
            </div>  
          </div>       	 
		  			          <div class="control-group">              
            <div class="controls">  
            <b>More settings available in pro version</b>  
            </div>  
          </div>						               
          <div class="form-actions"> 
  		   <input type="hidden" name="mstw_active_tab" value="2" />
            <input type="submit" name="submit" class="btn btn-primary" value="Update"/>  
          </div>  
        </fieldset>  
</form>  
</div> 
 
<div id="ms_third_tab" <?php echo $active3; ?>>  
<form method="post" name="color_options" action="" class="form-horizontal">  
        <fieldset>  
          <legend>Font and color Settings</legend> 
          <div class="control-group">  
            <label class="control-label" for="mstw_backcolor">Background color (#ffffff...)</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_backcolor" value="<?php echo esc_attr_e($mstw_backcolor); ?>" id="mstw_backcolor" />
						 <p class="help-block"><a href="http://www.colorpicker.com/" target="_blank">Ex. #EG9A10 color picker</a></p>  
            </div>  
          </div> 	
          <div class="control-group">  
            <label class="control-label" for="mstw_postbordercolor">Tweet border color(#E6E8E8...)</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_postbordercolor" value="<?php echo esc_attr_e($mstw_postbordercolor); ?>" id="mstw_postbordercolor" />			 
			<p class="help-block"><a href="http://www.colorpicker.com/" target="_blank">Ex. #EG9A10 color picker</a></p>  
            </div>  
          </div> 

          <div class="control-group">  
            <label class="control-label" for="mstw_postauthorcolor">Tweet author name color(#3B5998...)</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_postauthorcolor" value="<?php echo esc_attr_e($mstw_postauthorcolor); ?>" id="mstw_postauthorcolor" />			 
			<p class="help-block"><a href="http://www.colorpicker.com/" target="_blank">Ex. #3B5998 color picker</a></p>  
            </div>  
          </div>

          <div class="control-group">  
            <label class="control-label" for="mstw_posttextcolor">Tweet text color(#333333...)</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_posttextcolor" value="<?php echo esc_attr_e($mstw_posttextcolor); ?>" id="mstw_posttextcolor" />			 
			<p class="help-block"><a href="http://www.colorpicker.com/" target="_blank">Ex. #333333 color picker</a></p>  
            </div>  
          </div>           
          <div class="control-group">  
            <label class="control-label" for="mstw_datecolor">Date text color(#777...)</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_datecolor" value="<?php echo esc_attr_e($mstw_datecolor); ?>" id="mstw_datecolor" />			 
			<p class="help-block"><a href="http://www.colorpicker.com/" target="_blank">Ex. #777 color picker</a></p>  
            </div>  
          </div>           		 		  
		  			          <div class="control-group">              
            <div class="controls">  
            <b>More settings available in pro version</b>  
            </div>  
          </div>						               
          <div class="form-actions"> 
  		   <input type="hidden" name="mstw_active_tab" value="3" />
            <input type="submit" name="submit" class="btn btn-primary" value="Update"/>  
          </div>  
        </fieldset>  
</form>  
</div>
  
<div id="ms_fifth_tab" <?php echo $active5; ?>>
<form method="post" name="rate_limit_options" action="" class="form-horizontal">  
        <fieldset>  
          <legend>Request limit status</legend>
          <div>Enter your twitter application consumer key, consumer secret, access token , token secret and press submit to see twitter api request limit at bottom. Read <a target="_blank" href="http://extensions.techhelpsource.com/mitsol_tweet_documentation_wordpress.htm">documentation</a> for all info.
          by default following keys populated from general settings page. This request is also cached, so wait half a minute before seeing updated request limit status.</div><br/>
          <div class="control-group">  
            <label class="control-label" for="mstw_twconkey2">Consumer key</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_twconkey2" value="<?php echo esc_attr_e($mstw_twconkey2); ?>" id="mstw_twconkey2" />		 
            </div>  
          </div>
          <div class="control-group">  
            <label class="control-label" for="mstw_twconsecret2">Consumer secret</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_twconsecret2" value="<?php echo esc_attr_e($mstw_twconsecret2); ?>" id="mstw_twconsecret2" />
			
            </div>  
          </div> 
           <div class="control-group">  
            <label class="control-label" for="mstw_twtoken2">Access token</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_twtoken2" value="<?php echo esc_attr_e($mstw_twtoken2); ?>" id="mstw_twtoken2" />			
            </div>  
          </div> 
          <div class="control-group">  
            <label class="control-label" for="mstw_twtokensecret2">Access token secret</label>  
            <div class="controls">  
			<input type="text" class="input-xlarge" name="mstw_twtokensecret2" value="<?php echo esc_attr_e($mstw_twtokensecret2); ?>" id="mstw_twtokensecret2" />			 
            </div>  
          </div> 
	      		  		   		 		 		  								               
          <div style="margin-left:200px;"> 
  		   <input type="hidden" name="mstw_active_tab" value="5" />		             				 
            <input type="submit" name="submit" class="btn btn-primary" value="Submit"/>  
          </div>  
        </fieldset>
 </form>         
        <fieldset>  
          
          <div style="color:maroon;"><?php echo $rate_limit_errors."<br/>"; echo $value; ?></div>
           
        </fieldset>    
</div> 
<div id="ms_sixth_tab"  <?php echo $active6; ?>>
<form method="post" name="system_options" action="" class="form-horizontal">  
  <fieldset>  
          <legend>System requirements check</legend>
          <div class="control-group">  
           <label class="control-label" for="mstw_followwidth">To get feed some of these functions should be enabled in server</label>
            <div class="controls"> 
               Server & php info:&nbsp;&nbsp; <?php echo $_SERVER['SERVER_SOFTWARE']?><br/><br/>                
			   Is cURL enabled:&nbsp;&nbsp;<input type="checkbox" <?php if(is_callable('curl_init')) echo "checked"; ?> disabled value="enabled" /><br/><br/>
			   Is url fopen enabled:&nbsp;&nbsp;<input type="checkbox" <?php if(ini_get( 'allow_url_fopen' )) echo "checked"; ?> disabled value="enabled" /><br/><br/>
			   Is Json enabled:&nbsp;<input type="checkbox" <?php if(function_exists("json_decode")) echo "checked"; ?> disabled value="enabled" /><br/><br/>			 	 
            </div>  
          </div> 
          <div>              
           * If either cUrl or allow_url_fopen(fopen) enabled, it's ok. If both of them disabled, ask your hosting to enable it or if you own your server it's easy to do.<br/>
           Also without these, it may still work by the fallback method.<br/><br/>
           * Json should be enabled(checked), but in any case it's disabled ask your host to enable it                          
		  </div>
  </fieldset>  
</form>  
</div>

</div>  
</div><hr/>  

<div class="row-fluid">
<div class="well" style="color: navy">
Please check <b>"System rquirements"</b> tab above to know if your server has required methods enabled to call & display twitter.com tweets. Make sure consumer key, consumer secret, access token, token secret and username/hashtag/search string are right. 
<br/><br/>Read instructions and find errors if any in <a target="_blank" href="http://extensions.techhelpsource.com/mitsol_tweet_documentation_wordpress.htm">Documentation</a> 
</div> 
<div class="well">
<h4>how to display feed</h4> 
copy and paste this short code anywhere of page or post - <strong>[mitsol_tweets_short_code]</strong> <br/><br/>
To override settings, include setting attributes in short codes as follows - <strong>[mitsol_tweets_short_code tweet_user="twitter" header="true" num="30" post_items="authname,posttxt" backg_color="#ffffff" post_text_size="12" ....... ]</strong> <br/><br/>
<b><a target="_blank" href="http://extensions.techhelpsource.com/mstweets_wordpress_shortcodes.htm">Click here to view all short code attributes for free/pro version. </a></b>

</div>
<div class="well">
<a class="btn btn-info" target="_blank" style="font-weight:bold;" href="http://extensions.techhelpsource.com/wordpress/mitsol-tweets-pro">Click to Buy pro version now for a complete tweet display for your website</a><br/><br/>
To show tweets in slider with different slider settings, show number of retweets & favourites, show images in tweet if exists, make url/hashtag/others in text linkable, include tweet replies, show header & scrolling plugin, and more settings<br/><br/>

<strong>View pro version demo for all the features here - <a class="btn btn-info" target="_blank" href="http://wordpress.techhelpsource.com/mitsol-tweets/">Pro Demo</a></strong>

</div>
</div>
 
 </div>
 <?php  }