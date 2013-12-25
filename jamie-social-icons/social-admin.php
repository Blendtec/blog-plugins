<?php 
add_action( 'admin_init', 'jamie_social_init' );
add_action( 'admin_menu', 'jamie_social_add_page' );
/**
 * Init plugin options to white list our options
 */
 function jamie_social_init(){
	register_setting( 'social_options', 'jamie_social_options', 'jamie_social_validate' );
}
/**
 * Load up the menu page
 */
function jamie_social_add_page() {
	add_options_page('Jamie Social Settings', 'Social Settings', 'administrator', __FILE__, 'social_options_do_page',plugins_url('/images/share-balloon.png', __FILE__));
}
/**
 * Create the options page
 */
add_action('admin_head', 'jamiesocial_register_head');
function jamiesocial_register_head() {
  $url = WP_PLUGIN_URL.'/jamie-social-icons/css/options.css';
  echo "<link rel='stylesheet' href='$url' />\n";
}
function social_options_do_page() {
if ( ! isset( $_REQUEST['settings-updated'] ) )
		$_REQUEST['settings-updated'] = false;
?>
	<div class="wrap">
		 <?php screen_icon( 'jamie_social' ); ?>
		<h2><?php _e('Welcome to Jamie Social Icons', 'jamie_social') ?></h2>
          	<div id="dashboard-widgets-container">
		    <div id="dashboard-widgets" class="metabox-holder">
				<div id="post-body">
					<div id="dashboard-widgets-main-content">      
<div class="postbox-container" id="main-container" style="width:75%;">
<div class="meta-box-sortables" id="left-sortables">
<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
<div class="updated fade"><p><strong><?php _e( 'Options saved now start sharing!', 'jamie_social' ); ?></strong></p></div>
<?php endif; ?>

<form method="post" action="options.php">
<?php settings_fields( 'social_options' ); ?>
<?php $options = get_option( 'jamie_social_options'); 
?>
<?php include('admin/jamiesocial_position.php');?>
<?php include('admin/jamiesocial_posttype.php');?>		
<?php include('admin/jamiesocial_icons.php');?>
<?php include('admin/jamiesocial_lang.php');?> 
<?php include('admin/jamiesocial_insights.php');?> 
<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Options', 'jamie_social' ); ?>" />
</p>
</form>
</div><!--end of left-sortables--> 
</div><!--end of main-container-->
<?php include('admin/martiniboy/martiniboy.php');?>	
</div>
</div>
</div>
</div>
</div>
<?php
}
/**
 * Sanitize and validate input. Accepts an array, return a sanitized array.
 */

function jamie_social_validate( $input ) {
		// Say our text option must be safe text with no HTML tags
	$input['insights'] = wp_filter_nohtml_kses( $input['insights'] );
	$input['linkedin'] = wp_filter_nohtml_kses( $input['linkedin'] );
	$input['facebook_recommend'] = wp_filter_nohtml_kses( $input['facebook_recommend'] );
	$input['facebook'] = wp_filter_nohtml_kses( $input['facebook'] );
	$input['twitter'] = wp_filter_nohtml_kses( $input['twitter'] );
	$input['google'] = wp_filter_nohtml_kses( $input['google'] );
	$input['pinterest'] = wp_filter_nohtml_kses( $input['pinterest'] );
	$input['showing'] = wp_filter_nohtml_kses( $input['showing'] );
	$input['lang'] = wp_filter_nohtml_kses( $input['lang'] );
	$input['align'] = wp_filter_nohtml_kses( $input['align'] );
	$input['icontext_'] = wp_filter_nohtml_kses( $input['icontext_'] );
		$input['icontext'] = wp_filter_nohtml_kses( $input['icontext'] );
 	$input['post'] = wp_filter_nohtml_kses( $input['post'] );
	$input['page'] = wp_filter_nohtml_kses( $input['page'] ); 
/*?	$input['home'] = wp_filter_nohtml_kses( $input['home'] ); 
	$input['archive'] = wp_filter_nohtml_kses( $input['archive'] ); 
	$input['category'] = wp_filter_nohtml_kses( $input['category'] ); 
 >$input[''.$post_type.''] = wp_filter_nohtml_kses( $input[''.$post_type.''] );
 $input['googleanalytics'] = wp_filter_nohtml_kses( $input['googleanalytics'] ); 
 $input['googleanalyticsuse'] = wp_filter_nohtml_kses( $input['googleanalyticsuse'] ); <?php */
		
	return $input;
}