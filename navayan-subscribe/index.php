<?php
/*
Plugin Name: Navayan Subscribe
Description: Allows your visitors to easily and quickly subscribe to your website with double optin process, custom email templates, post/page notifications, block spam. Can be used as sidebar widget.
Version: 1.13
Usage: Install and use as a sidebar widget!
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=amolnw2778@gmail.com&item_name=NavayanSubscribe
Author: Amol Nirmala Waman
Plugin URI: http://blog.navayan.com/navayan-subscribe-easy-way-to-subscribe-wordpress-website-or-blog/
Author URI: http://www.navayan.com/
*/

if(!defined('NYBlog_Url'))				define('NYBlog_Url', 'http://blog.navayan.com/');
if(!defined('NYSPlugin_Source'))		define('NYSPlugin_Source', NYBlog_Url .'navayan-subscribe-easy-way-to-subscribe-wordpress-website-or-blog/' );
if(!defined('NYCSVPlugin_Source'))		define('NYCSVPlugin_Source', NYBlog_Url .'navayan-csv-export-easiest-way-to-export-all-wordpress-table-data-to-csv-format/');
if(!defined('NYSPlugin_WPUrl'))			define('NYSPlugin_WPUrl', 'http://wordpress.org/extend/plugins/navayan-subscribe');
if(!defined('NYSPlugin_Name'))			define('NYSPlugin_Name', __('Navayan Subscribe') );
if(!defined('NYSPlugin_Slug'))			define('NYSPlugin_Slug',  'navayan-subscribe');
if(!defined('NYSPlugin_Version'))		define('NYSPlugin_Version', '1.13');
if(!defined('NYSPlugin_Url'))			define('NYSPlugin_Url', WP_PLUGIN_URL.'/'.NYSPlugin_Slug.'/');
if(!defined('NYSPlugin_Dir'))			define('NYSPlugin_Dir', WP_PLUGIN_DIR.'/'.NYSPlugin_Slug.'/');
if(!defined('NYSPlugin_Info'))			define('NYSPlugin_Info', '<a href="'. NYSPlugin_Source .'" target="_blank">'. NYSPlugin_Name .'</a>'. __(' allows your visitors to easily and quickly subscribe to your website with double optin process, custom email templates, post/page notifications.') );
if(!defined('NYSPlugin_DonateInfo'))	define('NYSPlugin_DonateInfo', __('\'<strong><em>Dhammadana</em></strong>\' (\'Donation\') ), helps to continue the support for the plugin.') );
if(!defined('NYSPlugin_DonateUrl'))		define('NYSPlugin_DonateUrl', 'https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=amolnw2778@gmail.com&item_name='.NYSPlugin_Name );
if(!defined('NYDonatePage'))			define('NYDonatePage', ' href="http://www.navayan.com/donate.php" target="_blank" ');
if(!defined('WPBlogName'))				define('WPBlogName', html_entity_decode(stripslashes(get_bloginfo('name')), ENT_QUOTES) );
if(!defined('WPBlogInfo'))				define('WPBlogInfo', html_entity_decode(stripslashes(get_bloginfo('description')), ENT_QUOTES) );
if(!defined('WPAdminEmail'))			define('WPAdminEmail', get_option('admin_email'));

$AmbedkarThoughts = array(
	"Men are mortal. So are ideas. An idea needs propagation as much as a plant needs watering. Otherwise both will wither and die.",
	"History bears out the proposition that political revolutions have always been preceded by social and religious revolutions.",
	"Democracy is not merely a form of Government. It is primarily a mode of associated living, of conjoint communicated experience. It is essentially an attitude of respect and reverence towards fellowmen.",
	"The question is not whether a community lives or dies; the question is on what plane does it live."
);

// WORDPRESS ADMIN DIRECTORY
if ( strrpos(WP_CONTENT_DIR, '/wp-content/', 1) !== false){
	$WP_ADMIN_DIR = substr(WP_CONTENT_DIR, 0, -10) . 'wp-admin/';
}else{
	$WP_ADMIN_DIR = substr(WP_CONTENT_DIR, 0, -11) . '/wp-admin/';
}
if (!defined('WP_ADMIN_DIR')) define('WP_ADMIN_DIR', $WP_ADMIN_DIR);

if ( file_exists(NYSPlugin_Dir.'functions.php') && file_exists(NYSPlugin_Dir.'fields.php') ){
    include_once 'fields.php';
    include_once 'functions.php';
}else{
    exit( _e("Navayan Subscribe - Core files are missing! Please <a href='http://wordpress.org/extend/plugins/navayan-subscribe' target='_blank'>re-install plugin</a>") );
}

/***************************************************
* EXIT IF PLUGIN'S CORE FUNCTION NOT FOUND
* *************************************************/
function_exists('nys_Call') ? nys_Call() : exit( __('Core function not found! Please <a href="'. NYSPlugin_WPUrl .'">re-install '. NYSPlugin_Name .'</a>') );


/***************************************************
* UNINSTALL PLUGIN
* *************************************************/
register_deactivation_hook( __FILE__, 'nys_PluginDeactivate' );
function nys_PluginDeactivate(){
	if(get_option('ny_subscribe_wipe') == 1){
		global $wpdb;
		$wpdb->query("DELETE FROM $wpdb->options WHERE
					(option_name LIKE '%ny_subscribe_%') OR
					(option_name LIKE '%ny_unsubscribe_%') OR
					(option_name LIKE '%nyEmail%')
					");
		$wpdb->query("DELETE FROM $wpdb->comments WHERE (comment_post_ID = ". NYSSubPageID ." OR comment_post_ID = ". NYSUnSubPageID .")");
		$wpdb->query("DELETE FROM $wpdb->postmeta WHERE (post_id = ". NYSSubPageID ." OR post_id = ". NYSUnSubPageID .")");
		wp_delete_post( NYSSubPageID, true );
		wp_delete_post( NYSUnSubPageID, true );
	}
}

/***************************************************
* INSTALL NAVAYAN SUBSCRIBE
* *************************************************/
register_activation_hook( __FILE__, 'nys_PluginActivate' );
function nys_PluginActivate(){
	// DELETE OLDER PAGES AND ITS RELATED DATA
	$nysOldPage = get_nysPageID( 'navayan-unsubscribe' );
	if( $nysOldPage ){
		global $wpdb;
		$wpdb->query("DELETE FROM $wpdb->comments WHERE comment_post_ID = $nysOldPage");
		$wpdb->query("DELETE FROM $wpdb->postmeta WHERE post_id = $nysOldPage");
		wp_delete_post( $nysOldPage, true );
	}
	$nysOldPage2 = get_nysPageID( 'navayan-subscribe-optin' );
	if( $nysOldPage2 ){
		global $wpdb;
		$wpdb->query("DELETE FROM $wpdb->comments WHERE comment_post_ID = $nysOldPage2");
		$wpdb->query("DELETE FROM $wpdb->postmeta WHERE post_id = $nysOldPage2");
		wp_delete_post( $nysOldPage2, true );
	}

	nys_CreatePages();
	nys_LoadOptions();
}

/***************************************************
* NAVAYAN SUBSCRIBE FORM SETTINGS
* *************************************************/
function nys_Admin() {
	
	if ( !current_user_can( 'manage_options' ) ) exit(__('<h2 class="nysNote">You do not have admin privileges!</h2>'));
	
	global $admin_fields, $admin_fields_email_template, $admin_fields_spam_block;
	$count_admin_fields = sizeof($admin_fields);
	$count_admin_fields_email_template = sizeof($admin_fields_email_template);
	$count_admin_fields_spam_block = sizeof($admin_fields_spam_block);
	global $AmbedkarThoughts;

	wp_enqueue_style( NYSPlugin_Slug, NYSPlugin_Url . 'default.css', '', NYSPlugin_Version, 'screen' );
	wp_enqueue_script( NYSPlugin_Slug, NYSPlugin_Url .'default.js', array('jquery'), NYSPlugin_Version );
	
	$tabEmail = '';
	$tabAbout = '';
	$tabSetting = '';
	$tabSpamBlock = '';
	echo '<style type="text/css">';	
	if( isset($_POST['ny_subscribe_submit_template']) ){
		$tabEmail = 'class="on"';
	}elseif( isset($_POST['ny_subscribe_submit_spamblock']) ){
		$tabSpamBlock = 'class="on"';
	}elseif ( isset( $_POST['ny_subscribe_submit_form_settings'] ) ){
		$tabSetting = 'class="on"';
	}else{
		$tabAbout = 'class="on"';
	}
	echo '</style>';
?>
	
	<div id="wrapper">
		<div class="titlebg" id="plugin_title">
			<span class="head i_mange_coupon"><h1><?php echo NYSPlugin_Name;?></h1></span>
		</div>
		<div id="page">
			<p>
				<?php _e( 'v'.NYSPlugin_Version . ' &nbsp;|&nbsp; ' . ' <a href="'. NYSPlugin_Source .'" target="_blank">Plugin\'s Homepage</a>' ); ?> &nbsp; &nbsp; 
				<a href="<?php echo NYBlog_Url; ?>wordpress/" target="_blank"><?php _e('Similar Topics');?></a> &nbsp; &nbsp; 
				<a href="<?php echo NYCSVPlugin_Source; ?>" target="_blank"><?php _e('Export Users to CSV');?></a> &nbsp; &nbsp; 
				<a href="<?php echo NYSPlugin_DonateUrl; ?>" target="_blank"><?php _e('PayPal Donate');?></a> &nbsp; &nbsp;
				<a <?php echo NYDonatePage; ?>><?php _e('More Donate Options');?></a> &nbsp; &nbsp; 
				<a href="<?php echo NYSPlugin_WPUrl; ?>" target="_blank"><?php _e('Rate this plugin');?></a>
			</p>
			
			<?php
				// WARN ADMIN IF WP < 3.3
				global $wp_version;
				if (version_compare($wp_version, '3.3', '<')) {
					_e('<h2 class="nysNote">You are using older WordPress ('. $wp_version .'). <strong>'. NYSPlugin_Name .'</strong> requires minimum 3.3 (newest better!). <a href="http://wordpress.org/latest.zip" target="_blank">Update WordPress</a></h2>');
				}
	
				// CHECK IF PAGES ARE EXIST. IF NOT ASK TO RE-CREATE
				if ( (!NYSSubPageID || !NYSUnSubPageID) && !NYSAction ){
					_e('<div class="error" style="margin-left:0; margin-bottom:10px"><p> <span style="color:#f00; font-weight:700">CAUTION!</span> <strong>Subscribe</strong> and/or <strong>UnSubscribe</strong> page/s are missing. <a class="button button-secondary" href="'. $_SERVER['REQUEST_URI'] .'&nysaction=repage">Re-create</a></p></div>');
				}
				if ( NYSAction == 'repage' ){
					nys_CreatePages();
					_e('<div class="updated" style="margin-left:0; margin-bottom:10px"><p> <strong>Subscribe</strong> and/or <strong>UnSubscribe</strong> page/s recreated!</p></div>');
				}
			?>
			
			<div id="nySubscribeTabs">
				<a <?php echo $tabSetting;?> href="#nySubscribeSettings"><?php _e('Settings');?></a>
				<a <?php echo $tabEmail; ?> href="#nySubscribeEmailTemplate"><?php _e('Email Templates');?></a>
				<a <?php echo $tabSpamBlock; ?> href="#nySubscribeSpamBlock"><?php _e('Spam Block');?></a>
				<a <?php echo $tabAbout; ?> href="#nySubscribeAbout"><?php _e('About');?></a>
				<a href="<?php echo NYSPlugin_DonateUrl; ?>" target="_blank" class="donatelink"><?php _e('Donate (Dhammadana)');?></a>
			</div>
			
			<div id="nySubscribeBlocks">
				<div id="nySubscribeSettings">
					<form id="nySubscribeSettingsForm" method="post">
						<?php nys_AdminForm( $count_admin_fields, $admin_fields, 'ny_subscribe_submit_form_settings', __('Settings saved!') ); ?>
						<p>
							<label>&nbsp;</label>
							<input type="submit" name="ny_subscribe_submit_form_settings" id="ny_subscribe_submit_form_settings" class="button button-primary button-large" value="<?php _e('Save Settings');?>" />
						</p>
					</form>
				</div><!-- #nySubscribeSettings -->
				
				<div id="nySubscribeEmailTemplate">
					<form id="nyEmailTemplateForm" method="post">
						<?php nys_AdminForm( $count_admin_fields_email_template, $admin_fields_email_template, 'ny_subscribe_submit_template', __('Email templates updated!') ); ?>
						<p>
							<label>&nbsp;</label>
							<input type="submit" name="ny_subscribe_submit_template" id="ny_subscribe_submit_template" class="button button-primary button-large" value="<?php _e('Update Email Templates');?>" />
						</p>
					</form>
					
					<div id="nySubscribeSubstitutes">
						<p>
							<?php _e('Following are the keywords, you can you use in email template.');?><br/>
							<?php _e('Copy the keyword with curly braces and paste it into email template.');?>
						</p>
						<ul>
							<li><strong>{SITE_NAME}</strong> - <?php _e(WPBlogName);?></li>
							<li><strong>{SITE_URL}</strong> - <?php echo site_url();?></li>
							<li><strong>{SITE_LINK}</strong> - <a href="<?php echo site_url();?>" target="_blank"><?php _e(WPBlogName);?></a></li>
							<li><strong>{SITE_DESCRIPTION}</strong> - <?php _e(WPBlogInfo);?></li>
							<li><strong>{POST_NAME}</strong> - <?php _e('Title of the published post');?></li>
							<li><strong>{POST_CONTENT}</strong> - <?php _e('Content of published post');?></li>
							<li><strong>{POST_EXCERPT}</strong> - <?php _e('Excerpt of published post. Note: Excerpt field must not empty!');?></li>
							<li><strong>{POST_CATEGORIES}</strong> - <?php _e('Category/ies of published post');?></li>
							<li><strong>{POST_TAGS}</strong> - <?php _e('Tag/s of published post');?></li>
							<li><strong>{POST_FEATURED_IMAGE}</strong> - <?php _e('Featured image (thumbnail) of published post. (Use <b>ONLY</b> with <b>Post Notification</b>)');?></li>
							<li><strong>{PERMALINK}</strong> - <?php _e('URL of published post');?></li>
							<li><strong>{AUTHOR}</strong> - <?php _e('Author Name of published post');?></li>
							<li><strong>{AUTHOR_EMAIL}</strong> - <?php _e('Email of author of published post');?></li>
							<li><strong>{ADMIN_EMAIL}</strong> - <?php echo WPAdminEmail;?></li>							
							<li><strong>{SUBSCRIBER_NAME}</strong> - <?php _e('Will display the Name (if it has) of Subscriber in confirmation emails');?></li>
							<li><strong>{UNSUBSCRIBE}</strong> - <a href="<?php echo get_nysPageURL('unsubscribe'); ?>" target="_blank"><?php echo get_nysPageTitle('unsubscribe');?></a> <?php _e( 'page. (Use <b>ONLY</b> for <b>Post Notification</b> email)' );?></li>
							<li><br/><?php _e('Subscribe confirmation URL will be like this:');?><br/><a><?php echo get_nysConfirmURL();?></a></li>
							<li><?php _e('UnSubscribe confirmation URL will be like this:');?><br/><a><?php echo get_nysConfirmURL('unsubscribe');?></a></li>
						</ul>
					</div>
					
				</div><!-- #nySubscribeEmailTemplate -->
				
				<div id="nySubscribeSpamBlock">
					<form id="nySubscribeSpamBlockForm" method="post">
						<?php nys_AdminForm( $count_admin_fields_spam_block, $admin_fields_spam_block, 'ny_subscribe_submit_spamblock', __('Spam Block Saved!') ); ?>
						<p>
							<label>&nbsp;</label>
							<input type="submit" name="ny_subscribe_submit_spamblock" id="ny_subscribe_submit_spamblock" class="button button-primary button-large" value="<?php _e('Update Spam Block');?>" />
						</p>
					</form>
				</div><!-- #nySubscribeSpamBlock -->
				
				<div id="nySubscribeAbout">
					
					<blockquote><?php _e( $AmbedkarThoughts[array_rand($AmbedkarThoughts)] ); ?><br/>- <a href="http://ambedkar.navayan.com" target="_blank">Dr. Bhimrao Ramji Ambedkar</a></blockquote>
					<blockquote><?php _e(NYSPlugin_Info);?></blockquote>
					<p><strong><?php _e("User's Stats:");?></strong></p>
					<table cellspacing="0" class="wp-list-table widefat">
						<thead>
							<tr>
								<th><a href='users.php'><?php _e('Total Users');?></a></th>
								<th><a href='users.php?role=administrator'><?php _e('Administrators');?></a></th>
								<th><a href='users.php?role=subscriber'><?php _e('Subscribers');?></a></th>
								<th><a href='users.php?role=editor'><?php _e('Editors');?></a></th>
								<th><a href='users.php?role=author'><?php _e('Authors');?></a></th>
								<th><a href='users.php?role=contributor'><?php _e('Contributors');?></a></th>
								<th><a href='users.php?role=unconfirmed'><?php _e('Not Confirmed');?></a></th>
								<th><a href="<?php echo NYCSVPlugin_Source;?>" target="_blank"><?php _e('Export Users');?></a></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php echo nys_TotalUsers();?></td>
								<td><?php echo nys_UserCount( 'administrator' );?></td>
								<td><?php echo nys_UserCount( 'subscriber' );?></td>
								<td><?php echo nys_UserCount( 'editor' );?></td>
								<td><?php echo nys_UserCount( 'author' );?></td>
								<td><?php echo nys_UserCount( 'contributor' );?></td>
								<td><?php echo nys_UnconfirmedCount();?></td>
								<td><?php _e( '<strong><a href="'. NYCSVPlugin_Source .'" target="_blank">Navayan CSV Export</a></strong> will help you to export <br/>your users/subscribers to <br/>CSV (Comma Separate Value) format' ); ?></td>
							</tr>
						</tbody>
					</table>
					
				</div><!-- #nySubscribeAbout -->
	
				<h2><strong><?php _e("Donate (Dhammadana):");?></strong></h2>
				<p>
					<a href="<?php echo NYSPlugin_DonateUrl;?>" target="_blank" class="button button-primary button-large"><?php _e('Donate through PayPal');?></a>
					<?php _e(' &nbsp; OR &nbsp; ');?>
					<a <?php echo NYDonatePage;?> class="button button-secondary button-large"><?php _e('More Donate Options');?></a>
					<?php _e(' &nbsp; AND &nbsp; ');?>
					<a href="<?php echo NYSPlugin_WPUrl;?>" target="_blank" class="button button-primary button-large"><?php _e('Rate this plugin');?></a>
					<?php _e(' &nbsp; AND &nbsp; ');?>
					<a href="<?php echo NYSPlugin_Source;?>" target="_blank" class="button button-primary button-large"><?php _e('Say JaiBhim and Thanks!');?></a>
				</p>
				<p>
					<?php _e('Donating few amount, certainly makes a difference!');?>
					<a href="http://www.justinparks.com/have-you-made-donation-to-your-wordpress-plugin-developer/" target="_blank"><?php _e('read it why?');?></a>
				</p>
				<p><?php _e('If you are unable to donate through PayPal, <a '. NYDonatePage .'><strong>check more options</strong></a>');?></p>
				<p><?php _e("Donating is not THE only way to support. You can <a href='http://www.navayan.com/advertise.php' target='_blank'><strong>Advertise with us</strong></a> either on our <a href='". str_replace('blog.', '', NYBlog_Url) ."' target='_blank'>Parent Site</a> OR on our <a href='". NYBlog_Url ."' target='_blank'>Technical Blog</a>. This way we both can help each other!");?>
				</p>
				
			</div>	
			
		</div>
	</div>
	
<?php } ?>