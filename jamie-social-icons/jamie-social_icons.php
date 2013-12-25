<?php 
/*
Plugin Name: Jamie Social Icons
Plugin URI: http://www.martiniboy.co.uk/wordpress-plugins/jamie-social-icons/
Description: Add Facebook Like, Tweet, Google Plus 1, Pinterest and Linkedin button at bottom/top of page/post and now track your social links with google analytics
Author: Jamie Grove
Version: 0.9.8.3
Author URI: http://www.martiniboy.co.uk
*/
/*  Copyright 2012  Jamie Grove  (email : jamie@martiniboy.co.uk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define( 'PLUGIN_DIR', dirname(__FILE__).'/' ); 
function jamiesocial_admin_action_links($links, $file) {
    static $jamiesocial;
    if (!$jamiesocial) {
        $jamiesocial = plugin_basename(__FILE__);
    }
    if ($file == $jamiesocial) {
        $settings_link = '<a href="options-general.php?page=jamie-social-icons/social-admin.php">Settings</a>';
        array_unshift($links, $settings_link);
    }
    return $links;
}

add_filter('plugin_action_links', 'jamiesocial_admin_action_links', 10, 2);

function jamiesocial_on_deactivate() {
    $options = get_option('jamie_social_options');
        delete_option('jamie_social_options');
}
register_uninstall_hook(__FILE__, 'jamiesocial_on_deactivate');
if ( is_admin()) {
include "social-admin.php";
}
function get_jamie_social_code(){
$options = get_option( 'jamie_social_options' ); 
$url = get_permalink(get_the_ID());
$title = get_the_title(get_the_ID());
?>
<span class="martiniboy_social_list">
<?php include('admin/jamiesocial_icontext.php');?>
<ul class="jamie_social <?php if($options['align'] == 'left'){?>martinileft<?php } ?><?php if($options['align'] == 'center'){?>martinicenter<?php } ?><?php if($options['align'] == 'right'){?>martiniright<?php } ?>">
<?php 
if($options['facebook'] == true){
 ?>
  <li class="martinifb"><fb:like  send="false" layout="button_count"  <?php if($options['facebook_recommend'] == true){ $verb = _('recommend');?>action="<?php echo $verb;  ?>" <?php } ?> href="<?php echo $url; ?>" show_faces="false" width="90"></fb:like>   </li>


  <?php } 
if($options['google'] == true){ ?>
  <!--[if !lte IE 7]><!-->
  <li>
    <g:plusone size="medium" href="<?php echo $url;?>" callback="plusone_vote"></g:plusone>
  </li>
  <!--<![endif]-->
  <?php } 
if($options['twitter'] == true){ ?>
  <li><a href="https://twitter.com/share" class="twitter-share-button" data-count="horizontal" data-url="<?php echo $url;?>" data-text="<?php echo $title;?>">Tweet</a></li>
  <?php }
if($options['pinterest'] == true){ ?>
  <li class="pin"> <a class="pin-it-button" count-layout="horizontal" always-show-count="1" href="http://pinterest.com/pin/create/button/?url=<?php echo $url;?>">Pin It</a> <a href="javascript:void(run_pinmarklet())" class="pinarea"></a></li>
  <script src="http://assets.pinterest.com/js/pinit.js" type="text/javascript"/></script>
  <?php }
if($options['linkedin'] == true){ ?>
  <li><script type="in/share" data-url="<?php echo $url; ?>" data-counter="right" data-onsuccess="LinkedInShare"></script></li>
  <?php } ?>
</ul>
<br class="jamie_clear" />
</span>
<?php
}

function the_jamie_social_code(){
    return get_jamie_social_code();
}
function jamiesocial($atts){
	ob_start();
		  get_jamie_social_code();
		$content = ob_get_clean();
	return $content;
}
add_shortcode('jamiesocial', 'jamiesocial');
 $options = get_option( 'jamie_social_options' );
 include('inc/showpost.php');
 include('inc/showpage.php');
include('inc/wysiwyg_button.php');
add_action(  'admin_head ','jamie_social' );
add_action( 'wp_enqueue_scripts', 'jamie_add_my_stylesheet' );
function jamie_add_my_stylesheet() {
         // Respects SSL, Style.css is relative to the current file
         wp_register_style( 'jamie_social', plugins_url('css/social.css', 
__FILE__) );
         wp_enqueue_style( 'jamie_social' );
		
     }

 function jamie_add_my_scripts() {?>
<?php $options = get_option( 'jamie_social_options' ); if($options['insights'] == true){ ?>
<meta property="fb:admins" content="<?php echo $options['insights']; ?>" />
 <?php } ?>
<?php if($options['lang'] == 'eng'){ 
$locale = 'en_GB';
} elseif($options['lang'] == 'esp'){ 
$locale = 'es_ES';
} elseif ($options['lang'] == 'pt'){ 
$locale = 'pt_PT';
} elseif ($options['lang'] == 'de'){
$locale = 'de_DE';
} elseif ($options['lang'] == 'fr'){
$locale = 'fr_FR';
} else {?>
<?php include('locale/locale.php');?>
<?php } ?>
	 <!--[if lte IE 8]>
<div id="fb-root" style="width: 90;"></div> 
<?php 
wp_enqueue_script(
		'fb_ie8',
		'http://connect.facebook.net/'.$locale.'/all.js#xfbml=1');
		?> 
<!--<![endif]-->
<?php	wp_enqueue_script(
		'jamiesocial_tracking',
		plugins_url('/jamie-social-icons/js/googletracking.js')
	);
	wp_enqueue_script(
		'jamietwitterlinkedin',
		plugins_url('/jamie-social-icons/js/head_twitterlinkedin.js')
	);
}  
function jamie_add_my_footer_scripts() {  
  ?>
<?php $options = get_option( 'jamie_social_options' ); 
if($options['lang'] == 'eng'){ 
$locale = 'en_GB';
} elseif($options['lang'] == 'esp'){ 
$locale = 'es_ES';
} elseif ($options['lang'] == 'pt'){ 
$locale = 'pt_PT';
} elseif ($options['lang'] == 'de'){
$locale = 'de_DE';
} elseif ($options['lang'] == 'fr'){
$locale = 'fr_FR';
} else {?>
<?php include('locale/locale.php');?>
<?php } ?>
<!--[if !lte IE 8]><!--> 
<?php
  if ( wp_script_is( 'jamiesocial_facebook', 'done' ) ) {
?>
<script>
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/<?php echo $locale;?>/all.js#xfbml=1";
  fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));
<?php } ?>
<!--<![endif]--> 
<?php

wp_enqueue_script(
		'jamiesocial_social',
		plugins_url('/jamie-social-icons/js/social.js'), true);
wp_enqueue_script(
		'jamiesocial_linked','http://platform.linkedin.com/in.js', true);?>
<?php } 
add_action( 'wp_enqueue_scripts', 'jamie_add_my_scripts' );
add_action( 'admin_enqueue_scripts', 'jamie_add_my_scripts' );
add_action( 'wp_footer', 'jamie_add_my_footer_scripts' );
add_action( 'admin_footer', 'jamie_add_my_footer_scripts' );

?>