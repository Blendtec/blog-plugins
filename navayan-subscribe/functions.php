<?php
    /***************************************************
	* PLUGIN'S CORE FUNCTION
	* *************************************************/
	function nys_Call(){
		add_action('admin_menu','nys_CreateMenu');
		add_filter('user_contactmethods','nys_ExtendContact',10,1);
		//if ( has_action('post_submitbox_misc_actions') ){
			add_action( 'post_submitbox_misc_actions', 'nys_Box' );
		//}elseif ( has_action('post_submitbox_start') ){
			//add_action( 'post_submitbox_start', 'nys_Box' );
		//}
		add_action('widgets_init', 'nys_SubscribeWidgetInit');
		add_shortcode('nys_SubscribePageContent', 'nys_SubscribePageContent');
		add_shortcode('nys_UnSubscribePageContent', 'nys_UnSubscribePageContent');
	}
    
    /***************************************************
	* GET OPTION VALUE WITH MINIMUM FILTER
	* *************************************************/
	function getOption( $option, $defValue = false ){
		$str = get_option( $option ) ? get_option( $option ) : $defValue;
		return stripcslashes( preg_replace('/[\s]+/', ' ', html_entity_decode(nl2br($str)) ) );
	}
    
    /***************************************************
	* GET 'Subscribe/UnSubscribe' PAGE ID/TITLE/URL
	* *************************************************/
	function get_nysPageID( $str='subscribe' ){
		global $wpdb;
		$ID = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_name ='". $str ."' AND post_type = 'page' LIMIT 1");
		if ( $ID ) return (int) $ID;
    }
	function get_nysPageTitle( $str='subscribe' ){ return get_the_title(get_nysPageID($str)); }
	function get_nysPageURL( $str='subscribe' ){ return get_permalink(get_nysPageID($str)); }
	function get_nysConfirmURL($str='subscribe', $strEmail='someone@gmail.com', $strKey='randomkey' ){
		$SubscribePage = get_nysPageURL($str);
		$isParam = strstr($SubscribePage, '?') ? '&' : '?';
		return $SubscribePage . $isParam . 'nysemail='. $strEmail .'&nyskey='. $strKey;
	}
	
    // FORM FIELD CONSTANTS
    define('NYSPostName', (isset($_POST['ny_name']) ? $_POST['ny_name'] : ''));
    define('NYSPostEmail', (isset($_POST['ny_email']) ? $_POST['ny_email'] : ''));
    define('NYSPostCustom', (isset($_POST['ny_custom']) ? $_POST['ny_custom'] : ''));
    
    define('NYSLabelName', getOption( 'ny_subscribe_name_field_text'));
    define('NYSLabelEmail', getOption( 'ny_subscribe_email_field_text', __('E-Mail')));
    define('NYSLabelCustom', getOption( 'ny_subscribe_field_custom' ));
    
    define('NYSErrorName', getOption( 'ny_subscribe_name_field_error_msg', __('Type Name')));
    define('NYSErrorEmail', getOption( 'ny_subscribe_field_email_invalid', __('Invalid Email')));
	define('NYSErrorCustom', getOption( 'ny_subscribe_field_custom_error_message', __('Required...')));
    
    define('NYSNotifyCheckbox', 'ny_notify_subscribers');
    define('NYSSubPageID', get_nysPageID());
    define('NYSUnSubPageID', get_nysPageID('unsubscribe'));
	define('NYSAction', (isset($_GET['nysaction']) ? $_GET['nysaction'] : ''));
	define('NYSGetEmail', (isset($_GET['nysemail']) ? $_GET['nysemail'] : ''));
	define('NYSGetKey', (isset($_GET['nyskey']) ? $_GET['nyskey'] : ''));
	define('NYSDate', md5(trim(date('YmdHisu'))));
	define('NYSSubFormTitle', getOption( 'ny_subscribe_field_form_heading', __('Subscribe') ) );
	define('NYSThemeCSS', ( get_option( 'ny_subscribe_theme_css') == 1 ? '<link href="'. get_stylesheet_uri() .'" type="text/css" rel="stylesheet"/>' : '') );
	
	/***************************************************
	* CREATE 'Subscribe and UnSubscribe' PAGES IF NOT EXIST
	* *************************************************/
	function nys_CreatePages(){
        if ( !NYSSubPageID ){
			wp_insert_post(array(
                'post_title' => 'Subscribe',
                'post_status' => 'publish', 
                'post_type' => 'page',
                'post_author' => 1,
                'post_content' => '[nys_SubscribePageContent]'
            ));
		}
        if ( !NYSUnSubPageID ){
			wp_insert_post(array(
                'post_title' => 'UnSubscribe',
                'post_status' => 'publish', 
                'post_type' => 'page',
                'post_author' => 1,
                'post_content' => '[nys_UnSubscribePageContent]'
            ));
		}
	}
	
	/***************************************************
	* CREATE MENU UNDER 'DASHBOARD -> TOOLS' TAB
	* *************************************************/
	function nys_CreateMenu() {
		if (function_exists('add_options_page')) {
			add_management_page( __( NYSPlugin_Name, NYSPlugin_Slug ), __( NYSPlugin_Name, NYSPlugin_Slug ), 'manage_options', NYSPlugin_Slug, 'nys_Admin');
		}
	}
	
	/***************************************************
	* EXTEND USERMETA FOR ADDITIONAL CUSTOM FIELD
	* *************************************************/
	function nys_ExtendContact( $contactmethods ) {
		$contactmethods[ 'ny_subscribe_field_custom' ] = get_option( 'ny_subscribe_field_custom' );
		return $contactmethods;
	}
	
	/***************************************************
	* ADD NOTIFICATION CHECKBOX TO 'PUBLISH' METABOX
	* *************************************************/
	function nys_Box(){
		$isTicked = get_option( 'ny_subscribe_ticked' ) ? 'checked="checked"' : '';
        global $post;
		
		echo '<div id="nySubscribeBox" style="position:relative; padding: 8px 10px"><label>';
        
        if ( $post->ID == NYSSubPageID || $post->ID == NYSUnSubPageID ){
            _e(NYSPlugin_Name .' '. NYSPlugin_Version);
        }else{
			if ( nys_IsEmailSent($post->ID) ){
				_e( '<strong style="color:#090">Notification Sent!</strong>' );
			}else{
				echo '<input type="checkbox" value="1" '. $isTicked .' name="'. NYSNotifyCheckbox .'" />&nbsp; ';
				_e( 'Notify Subscribers' );
			}
        }
		
		echo '</label> <span style="position:absolute; right: 10px"> <a style="text-decoration:none" href="tools.php?page='. NYSPlugin_Slug .'">';
		_e( 'Settings' );
		echo '</a>';
		echo ' | <a href="'. NYSPlugin_Source .'" target="_blank">'. __('Help') .'</a>';
		echo '</span></div>';
	}
	
	
	/***************************************************
	* UPDATE NOTIFICATION CHECKBOX AFTER POST PUBLISH/SAVED
	* *************************************************/
	$GetPostType = isset($_POST['post_type']) ? $_POST['post_type'] : '';
	$PublishAction = $GetPostType != 'post' ? 'publish_'. $GetPostType : 'publish_post'; // SUPPORTS CUSTOM POST TYPES TOO

	if ( has_action($PublishAction) || has_action('save_post') ){		
		if ( isset($_POST['publish']) ){ // IF POST/PAGE IS PUBLISHED
			add_action( $PublishAction, 'nys_UpdateBox', 10, 1 );
		}elseif ( isset($_POST['save']) ){ // FALLBACK - IF 'publish_post' ACTION NOT PRESENT AND POST/PAGE IS UPDATED/SAVED
			add_action( 'save_post', 'nys_UpdateBox', 10, 1 );
		}
	}
	
	function nys_UpdateBox(){
		nys_NotificationEmail();
	}
	
	/***************************************************
	* CUSTOM COLUMNS FOR EMAIL SENT STATUS/CONFIRMED
	* *************************************************/
	function nys_AddPostColumn($col) {
		$col['nys_email_sent'] = _('Notification Sent?');
		return $col;
	}
	function nys_PostColumnStatus($col_name, $id) {
		switch ($col_name) {
			case 'nys_email_sent':
				echo nys_IsEmailSent($id) ? _('<span style="color:#090">Yes</span>') : _('<span style="color:#f00">No</span>');
				break;
		}
	}
	add_filter('manage_post_posts_columns', 'nys_AddPostColumn');
	add_action('manage_posts_custom_column', 'nys_PostColumnStatus', 10, 2);
	
	function nys_AddUserColumn($col) {
		if(NYSLabelCustom) $col['custom'] = __(NYSLabelCustom);
		$col['register_date'] = __('Not Confirmed Since');
		return $col;
	}
	function nys_UserColumnStatus( $value, $col_name, $user_id ) {
		$ret='';
		if ( $col_name == 'custom'){
			$custom= get_user_meta( $user_id, 'ny_subscribe_field_custom' );
			if ( !empty($custom)){
				$ret .= stripslashes($custom[0]);
			}
		}
		if ( $col_name == 'register_date'){			
			$user = new WP_User( $user_id );
			if ( empty( $user->roles ) ) {
				$date = date_create($user->user_registered);
				$ret .= date_format($date, get_option('links_updated_date_format'));
			}
		}
		return __($ret);
	}
	add_filter('manage_users_columns', 'nys_AddUserColumn');
	add_action('manage_users_custom_column', 'nys_UserColumnStatus', 10, 3);
	
	
	/***************************************************
	* GET VARIOUS USERS COUNT
	* *************************************************/
	function nys_TotalUsers(){
		global $wpdb;
		$users = $wpdb->get_var( "SELECT COUNT(ID) FROM ". $wpdb->prefix ."users" );
		return (int) $users;
	}
	function nys_UserCount( $role ){
		global $wpdb;
		$users = $wpdb->get_var( "SELECT COUNT(umeta_id) FROM ". $wpdb->prefix ."usermeta WHERE meta_key='". $wpdb->prefix ."capabilities' AND meta_value LIKE '%$role%' " );
		return (int) $users;
	}
	function nys_UnconfirmedCount(){
		global $wpdb;
		$users = $wpdb->get_var( "SELECT COUNT(umeta_id) FROM ". $wpdb->prefix ."usermeta WHERE meta_key='". $wpdb->prefix ."capabilities' AND meta_value LIKE '%unconfirmed%' " );
		return (int) $users;
	}
	function nys_ShowCount(){
		if ( getOption( 'ny_subscribe_field_show_count' ) == 1 ){
			return ' ('. __( nys_UserCount( 'subscriber' ) ) .')';
		}
	}
	
	/***************************************************
	 * GET POST FEATURED IMAGE FOR NOTIFICATION
	 * *************************************************/
	function nys_FeaturedImage($post_id){
		if ( has_post_thumbnail($post_id) ){
			return '<a href="'. get_permalink( $post_id ) .'" target="_blank">'. get_the_post_thumbnail($post_id) .'</a>';
		}
	}
		
	/***************************************************
	 * REPLACE ALL SUBSTITUTES
	 * *************************************************/
	function nys_ReplaceSubstitutes( $post_id = 0, $strToReplace = '' ){
		$post = get_post( $post_id ); //global $post;
		
		$str = array(
			'{SITE_NAME}',
			'{SITE_DESCRIPTION}',
			'{SITE_URL}',
			'{SITE_LINK}',
			'{POST_NAME}',
			'{POST_CONTENT}',
			'{POST_EXCERPT}',
			'{POST_CATEGORIES}',
			'{POST_TAGS}',
			'{POST_FEATURED_IMAGE}',
			'{PERMALINK}',
			'{AUTHOR}',
			'{ADMIN_EMAIL}',
			'{AUTHOR_EMAIL}',
			'{UNSUBSCRIBE}'
		);
		$replaceWith = array(
			WPBlogName,
			WPBlogInfo,
			get_option('home'),
			'<a href="'. get_option('siteurl') .'" target="_blank">'. WPBlogName .'</a>',
			stripslashes($post->post_title),
			'<div style="clear:both; margin: 14px 0">'. $post->post_content .'</div>',
			'<div style="clear:both; margin: 14px 0">'. $post->post_excerpt .'</div>',
			get_the_category_list( __( ', ', '', $post->ID ) ),
			get_the_tag_list( '', __( ', ', '', $post->ID ) ),
			nys_FeaturedImage($post->ID),
			'<a href="'. get_permalink( $post->ID ) .'" target="_blank">'. $post->post_title .'</a>',
			stripslashes( get_the_author_meta( 'display_name', $post->post_author ) ),
			WPAdminEmail,
			get_the_author_meta( 'user_email', $post->post_author ),
			'<a href="'. get_nysPageURL('unsubscribe') .'" target="_blank">'. getOption('ny_unsubscribe_label', 'UnSubscribe') .'</a>'
		);
		
		return str_replace ( $str, $replaceWith, $strToReplace );
	}
	
	/***************************************************
	* NAVAYAN SUBSCRIBE WIDGET IN SIDEBAR
	* *************************************************/
	class nys_SubscribeWidget extends WP_Widget {
		public function __construct() {
			$this->WP_Widget(
				2,
				'',
				array(
					'name' => __( NYSPlugin_Name ),
					'description' => __('Display Subscribe form in a sidebar')
				)
			);
		}
		
		public function form() {
			echo '<p class="no-options-widget">' . __('<a href="tools.php?page='. NYSPlugin_Slug .'">Settings</a>') . '</p>';
			return 'noform';
		}
	
		public function widget( $args ) {
			extract($args);
			$title = apply_filters( 'widget_title', NYSSubFormTitle . nys_ShowCount() );
			echo $before_widget;
			
			if ( ! empty( $title ) ){				
				echo $before_title . $title . $after_title;
			}
				
			if ( function_exists('navayan_subscribe') ){	
				navayan_subscribe();
			}
			echo $after_widget;
		}
	}
	function nys_SubscribeWidgetInit() {
		if ( get_option('ny_subscribe_field_sub_form') != 1 ){
			register_widget('nys_SubscribeWidget');
		}
	}
	
	/***************************************************
	* SUBSCRIBE PAGE CONTENT
	* *************************************************/
	function nys_SubscribePageContent(){
		_e("<style type='text/css'>" . get_option('ny_subscribe_css') ."</style>");
		
		if (NYSGetEmail && NYSGetKey){
			$getUser = get_user_by( 'email', NYSGetEmail );
			if ( $getUser && NYSGetKey == $getUser->user_pass ){
				if( empty($getUser->roles[0]) ){
					global $wpdb;
					
					$wpdb->query( "UPDATE ". $wpdb->prefix ."usermeta SET meta_value = 'a:1:{s:10:\"subscriber\";b:1;}' WHERE meta_key = '". $wpdb->prefix ."capabilities' AND user_id = ". $getUser->ID);

					$updateUser = $wpdb->query( "UPDATE ". $wpdb->prefix ."users SET user_pass = '". wp_hash_password( NYSDate ) ."' WHERE ID = ". $getUser->ID );
					if ($updateUser){
						_e( '<p class="nysSuccess">'. getOption('ny_subscribe_field_sub_confirmed', 'Congrats! Your subscription has been confirmed!') .'</p>');
						
						# SEND EMAIL TO ADMIN
						if( getOption( 'ny_subscribe_field_send_email' ) == 1 ){
							$fname = get_user_meta( $getUser->ID, 'first_name' );
							$custom= get_user_meta( $getUser->ID, 'ny_subscribe_field_custom' );
							$getName=''; $getCustom='';
							if ( NYSLabelName && !empty($fname[0]) ){
								$getName = __("<br/>". NYSLabelName .": ". stripslashes($fname[0]) );
							}
							if ( !empty($custom[0]) ){
								$getCustom .= __("<br/>". getOption('ny_subscribe_field_custom') .": ". stripslashes($custom[0]) );
							}
							
							$headers  = "MIME-Version: 1.0 \r\n";
							$headers .= "Content-type: text/html; charset=utf-8 \r\n";
							$headers .= "From: ". NYSPlugin_Name ."<". WPAdminEmail .">";
							$person = !empty($fname[0]) ? stripslashes($fname[0]) : NYSGetEmail;
							$subject = $person . __(" subscribed to ". WPBlogName );
							
							$message = "<!DOCTYPE html>
										<html>
										<head><title>". WPBlogName ."</title></head>
										<body style='margin: 20px'>
										". $subject .".
										". $getName . $getCustom ."
										". __("<br/>Date: ". date( get_option('date_format')) ) ."
										<br/>
										</body>
										</html>";
							
							@wp_mail( WPAdminEmail, $subject, $message, $headers );
						}
					} else {
						_e( '<p class="nysError">'. getOption('ny_subscribe_field_sub_not_confirmed', 'Sorry! Cannot confirm your subscription') .'</p>');
					}
				}
			} else{
				_e( '<p class="nysError">'. getOption('ny_subscribe_field_sub_not_confirmed', 'Sorry! Cannot confirm your subscription') .'</p>');
			}
		} else {
			if ( get_option('ny_subscribe_field_sub_form') == 1){
				if ( !is_user_logged_in() ) {
					_e('<h2>'. NYSSubFormTitle . nys_ShowCount() .'</h2>' );
				}
				navayan_subscribe();
			}else{
				_e( '<p>'. getOption('ny_subscribe_field_sub_empty', 'This page contains nothing until you confirm your subscription!') .'</p>');
			}
		}
	}
	
	/***************************************************
	* UNSUBSCRIBE PAGE CONTENT
	* *************************************************/
	function nys_UnSubscribePageContent(){
		
		_e("<style type='text/css'>" . get_option('ny_subscribe_css') ."</style>");
		
		if (NYSGetEmail && NYSGetKey){
			$getUser = get_user_by( 'email', NYSGetEmail );
			if ( $getUser ){
				if( $getUser->roles[0] == 'subscriber' && NYSGetEmail == $getUser->user_email && NYSGetKey == $getUser->user_pass ){
					
					if ( file_exists( WP_ADMIN_DIR . 'includes/user.php' )){
						require_once( WP_ADMIN_DIR . 'includes/user.php' );
						$deleteSubscriber = wp_delete_user( $getUser->ID, 1 );
						if ( $deleteSubscriber ){
							_e( '<p class="nysSuccess">'. getOption('ny_subscribe_field_unsub_confirmed', 'Done! You will not receive any email from us.') .'</p>' );
						} else {
							_e( '<p class="nysError">'. getOption('ny_subscribe_field_unsub_not_confirmed', 'OOPs! Cannot unsubscribe!') .'</p>');
						}
					}else{
						exit( _e('Wordpress has dropped some core files!') );
					}
				}
			}
		} else {
			
			if ( !is_user_logged_in() ) {
			
				$beforeSubmit = '<p>'. getOption('ny_unsubscribe_msg_before_submit', 'Please type your email address to unsubscribe.') .'</p>';
				$UnSubFormHide = false;
				
				if ( isset($_POST['unsubscribe_submit']) ){
					$unsub_email = trim( stripcslashes ( $_POST['unsubscribe_email'] ) );
					if ( is_email ( $unsub_email ) ){
						$getUser = get_user_by( 'email', $unsub_email );
						if ($getUser){
							// SEND UNSUBSCRIBE CONFIRMATION EMAIL TO USER
							nys_SubUnSubConfirmEmail( 'unsubscribe', $unsub_email, 'nyEmailUnSubscribeSubject', 'nyEmailUnSubscribeBody' );
							_e( '<p class="nysSuccess">'. getOption( 'ny_unsubscribe_msg_after_submit', 'Please check your email to confirm your unsubscription.' ) .'</p>');
							$UnSubFormHide = true;
						}else{
							_e( '<p class="nysError">'. getOption( 'ny_unsubscribe_msg_email_not_exist', 'Cannot unsubscribe! This email does not exist.' ) .'</p>');
							$UnSubFormHide = false;
						}
					}else{
						_e($beforeSubmit);
						_e('<p class="nysError">'. NYSErrorEmail .'</p>');
						$UnSubFormHide = false;
					}
				}else{
					_e($beforeSubmit);
				}
				
				if ( $UnSubFormHide == false ){
					echo '<form id="navayan_unsubscribe_form" name="navayan_unsubscribe_form" method="post">';
					echo '<p><input required="required" type="email" name="unsubscribe_email" id="unsubscribe_email" /></p>';
					echo '<p id="ny_unsubscribe_submit_wrapper"><input type="submit" name="unsubscribe_submit" id="unsubscribe_submit" value="'. __( getOption( 'ny_unsubscribe_button_label', 'UnSubscribe' ) ) .'" /></p>';
					echo '</form>';
				}
			}else{
				_e( "<p>". getOption( 'ny_subscribe_logged_in_msg', 'You are logged in!') ."</p>" );
			}
		}
	}
	
	
	/***************************************************
	* SUBSCRIBE FROM FIELDS
	* *************************************************/
	function nys_FormFields(){		
		if( NYSLabelName ){
			if ( getOption( 'ny_subscribe_field_placeholder' ) == 1 ){
				echo "<p>
					<input required='required' placeholder='".__( NYSLabelName )."' title='".__( NYSLabelName )."' type='text' name='ny_name' id='ny_name' rel='". NYSErrorName ."' value='". stripslashes( NYSPostName ) ."' />
				</p>";
			}else{
				echo "<p>
					<label for='ny_name'>".__( NYSLabelName )."</label>
					<input type='text' required='required' name='ny_name' id='ny_name' rel='". NYSErrorName ."' value='". stripslashes( NYSPostName ) ."' />
				</p>";
			}
		}
		
		if ( getOption( 'ny_subscribe_field_placeholder' ) == 1 ){
			echo "<p>
				<input type='email' required='required' placeholder='".__(NYSLabelEmail)."' title='".__(NYSLabelEmail)."' type='text' name='ny_email' id='ny_email' rel='". NYSErrorEmail ."' value='". stripslashes( NYSPostEmail ) ."' />
			</p>";
		}else{
			echo "<p>
				<label for='ny_email'>".__(NYSLabelEmail)."</label>
				<input type='email' aria-required='true' required='required' name='ny_email' id='ny_email' rel='". NYSErrorEmail ."' value='". stripslashes( NYSPostEmail ) ."' />
			</p>";
		}
		
		if( NYSLabelCustom ){
			if ( getOption( 'ny_subscribe_field_placeholder' ) == 1 ){
				echo "<p>
					<input required='required' placeholder='".__( NYSLabelCustom )."' title='".__( NYSLabelCustom )."' type='text' name='ny_custom' id='ny_custom' rel='". NYSErrorCustom ."' value='". stripslashes( NYSPostCustom ) ."' />
				</p>";
			}else{
				echo "<p>
					<label for='ny_custom'>". __( NYSLabelCustom ) ."</label>
					<input type='text' required='required' name='ny_custom' id='ny_custom' rel='". NYSErrorCustom ."' value='". stripslashes( NYSPostCustom ) ."' />
				</p>";
			}
		}
	}
	
	/***************************************************
	* SUBSCRIBE FORM UI
	* *************************************************/
	if ( !function_exists('navayan_subscribe') ){
		function navayan_subscribe(){
			wp_enqueue_style( '', NYSPlugin_Url . 'default.css' );
			
			$wrapper_id = 'ny_subscribe_wrapper';
			echo "<div id='". $wrapper_id ."'>";
			
			// EXCLUDE SUBSCRIBE FORM FOR LOGGED IN USER
			if ( !is_user_logged_in() ) {
				
				// CHECK FOR BLOCKED IP ADDRESSES
				$BlockedIP = false;
				$SpamIPRemote = explode(',', str_replace("\r\n", '', trim(get_option('ny_subscribe_spam_ip_list_remote')) ) );
				$SpamIPServer = explode(',', str_replace("\r\n", '', trim(get_option('ny_subscribe_spam_ip_list_server')) ) );
				$SpamIPRemoteCount = count($SpamIPRemote);
				$SpamIPServerCount = count($SpamIPServer);
				
				if ( $SpamIPRemoteCount > 0 ){
					for ($i = 0; $i < $SpamIPRemoteCount; $i++){
						if(trim($_SERVER['REMOTE_ADDR']) == $SpamIPRemote[$i]){
							$BlockedIP = true;
							break;
						}
					}
				}
				if ( $SpamIPServerCount > 0 ){
					for ($i = 0; $i < $SpamIPServerCount; $i++){
						if(trim($_SERVER['SERVER_ADDR']) == $SpamIPServer[$i]){
							$BlockedIP = true;
							break;
						}
					}
				}
				
				// HIDE SUBSCRIBE FORM FOR BLOCKED IP ADDRESSES
				if ( $BlockedIP == true ){
					_e( '<p class="nysError">'. getOption('ny_subscribe_spam_ip_msg') .'</p>' );
				}else{
					// DISPLAY SUBSCRIBE FORM
					wp_enqueue_script( NYSPlugin_Slug, NYSPlugin_Url .'default.js', array('jquery'), '1.9' );
	
					_e('<p>'. getOption( 'ny_subscribe_field_form_description', 'Subscribe to get updates!') .'</p>');
	
					if ( isset( $_POST['ny_subscribe_submit'] ) ){
						nys_SubmitSubscribeForm();
					}				
					echo "<form class='v". NYSPlugin_Version ."' id='ny_subscribe_form' method='post' action='#". $wrapper_id ."'>";
					
					nys_FormFields();
		
					echo "<p id='ny_subscribe_submit_wrapper'>
								<input type='submit' name='ny_subscribe_submit' id='ny_subscribe_submit' value='". getOption( 'ny_subscribe_field_label', __('Subscribe') ) ."' />
							</p>
						</form>";
				}
			}else{
				_e( "<p>". getOption( 'ny_subscribe_logged_in_msg', 'You are logged in!') ."</p>" );
			}
			
			echo '</div>';
		}
		add_shortcode('navayan_subscribe', 'navayan_subscribe');
	}	
	
	/***************************************************
	* ADD USERS WHEN FORM SUBMITTED
	* *************************************************/
	function nys_SubmitSubscribeForm(){
		
		_e("<style type='text/css'>" . get_option('ny_subscribe_css') ."</style>");
		
		$fileFormatting = ABSPATH . WPINC . '/formatting.php';
		$fileUser = ABSPATH . WPINC . '/user.php';
		$return=''; $val_name=''; $val_custom='';
		
		if ( file_exists ( $fileFormatting ) && file_exists ( $fileUser ) ){
			require_once( $fileFormatting );
			require_once( $fileUser );
		}else{
			exit( __('Wordpress has dropped some core files!') );
		}
		
		if( NYSLabelName ){ 
			$val_name = NYSPostName;
		}
		if( NYSLabelCustom ){
			$val_custom = NYSPostCustom;
		}
		
		if ( !is_email( NYSPostEmail ) ) {
			$return['err'] = true;
			$return['msg'] = NYSErrorEmail;
		} elseif ( email_exists( NYSPostEmail ) ){			
			$return['err'] = true;
			$return['msg'] = getOption( 'ny_subscribe_field_email_exist', 'This Email already registered');
		} else {

			// DIS-ALLOW SPAM BLOCKED EMAIL/S AND DOMAIN/S
			$BlockedEmail = false;
			$BlockedDomain = false;
			$SpamEmails = explode(',', str_replace("\r\n", '', trim(get_option('ny_subscribe_spam_email_list')) ) );
			$SpamDomains = explode(',', str_replace("\r\n", '', trim(get_option('ny_subscribe_spam_domain_list')) ) );
			$SpamEmailsCount = count($SpamEmails);			
			$SpamDomainsCount = count($SpamDomains);
			
			if ( $SpamEmailsCount > 0 ){
				for ($i = 0; $i < $SpamEmailsCount; $i++){
					if(NYSPostEmail == $SpamEmails[$i]) $BlockedEmail = true;
				}
			}			
			if ( $SpamDomainsCount > 0 ){
				$GetDomain = explode('@', NYSPostEmail);
				$GetDomain = array_reverse($GetDomain);
				for ($i = 0; $i < $SpamDomainsCount; $i++){
					if($GetDomain[0] == $SpamDomains[$i]) $BlockedDomain = true;
				}
			}
		
			if ( $BlockedEmail == true ){
				$return['err'] = true;
				$return['msg'] = getOption('ny_subscribe_spam_email_msg');
			}elseif ( $BlockedDomain == true ){
				$return['err'] = true;
				$return['msg'] = getOption('ny_subscribe_spam_domain_msg');
			}else{
				if ( NYSLabelName && $val_name == ''){
					$return['err'] = true;
					$return['msg'] = NYSErrorName;
				} elseif ( NYSLabelCustom && $val_custom == ''){
					$return['err'] = true;
					$return['msg'] = NYSErrorCustom;
				} else {
					$val_name	= sanitize_text_field($val_name);
					$val_custom	= isset($val_custom) ? sanitize_user( str_replace('@',' ', $val_custom ) ) : '';
					$clean_user	= sanitize_user( NYSPostEmail );
					$val_id		= wp_create_user( $clean_user, NYSDate, NYSPostEmail );
					$user = new WP_User($val_id);
					$user->set_role('unconfirmed');
					
					if ( !$val_id ){
						$return['err'] = true;
						$return['msg'] = getOption( 'ny_subscribe_field_unable_to_subscribe', 'Unable to subscribe');
					}else{
						update_user_meta( $user->ID, 'ny_subscribe_field_custom', $val_custom );
						update_user_meta( $user->ID, 'first_name', $val_name );
						
						// SEND SUBSCRIBE CONFIRMATION EMAIL TO USER
						nys_SubUnSubConfirmEmail( 'subscribe', NYSPostEmail, 'nyEmailSubscribeSubject', 'nyEmailSubscribeBody' );
						
						if ( !is_user_logged_in() ){
							$return['err'] = false;
							$return['msg'] = getOption( 'ny_subscribe_field_success', 'To confirm your subscription, please check your email.');
						}
					}
				}
			}
		}

		$cls = $return['err'] == true ? 'nysError' : 'nysSuccess';
		echo '<p class="'.$cls.'">'. __($return['msg']) .' </p>';
		
		if($return['err'] == false){
			if( getOption( 'ny_subscribe_field_hide_form' ) == 1){
				echo '<style type="text/css">#ny_subscribe_form{display:none}</style>';
			}
			echo '<script type="text/javascript">
					if( typeof jQuery != undefined ){
						jQuery(function ($){
							$("#ny_subscribe_form input:text").val("");
						});
					}
				</script>';
		}
	}
	
	
	/***************************************************
	* ADD ADMIN FIELDS VALUES DURING PLUGIN ACTIVATION
	* *************************************************/
	function nys_LoadOptions(){
		global $admin_fields, $admin_fields_email_template;
		$count_admin_fields = sizeof($admin_fields);
		$count_admin_fields_email_template = sizeof($admin_fields_email_template);
		for ( $i = 0; $i < $count_admin_fields; $i++ ){
			if( !get_option( $admin_fields[$i]['slug'] ) ){
				add_option( $admin_fields[$i]['slug'], $admin_fields[$i]['val'] );
			}	
		}
		for ( $i = 0; $i < $count_admin_fields_email_template; $i++ ){
			if( !get_option( $admin_fields_email_template[$i]['slug'] ) ){
				add_option( $admin_fields_email_template[$i]['slug'], $admin_fields_email_template[$i]['val'] );
			}
		}
	}
	
	/***************************************************
	* ADMIN FIELDS - DISPLAY, UPDATE
	* *************************************************/
	function nys_AdminForm( $totalFields, $arrayVar, $postBtn, $msg ){
		// ADD FORM FIELDS IF NOT EXIST
		for($i = 0; $i < $totalFields; $i++){
			if( !get_option( $arrayVar[$i]['slug'] ) ){
				update_option( $arrayVar[$i]['slug'], $arrayVar[$i]['val'] );
			}
		}
	
		// UPDATE VALUES
		if( isset($_POST[$postBtn]) ){								
			for ( $i = 0; $i < $totalFields; $i++ ){
				if ( $arrayVar[$i]['type'] == 'checkbox' ){
					$mine[$i]['value'] = isset($_POST[ $arrayVar[$i]['slug'] ]) ? 1 : 0;
				}else{
					$mine[$i]['value'] = @$_POST[$arrayVar[$i]['slug']];
				}
				update_option( $arrayVar[$i]['slug'], $mine[$i]['value'] );
			}
			echo '<p class="nys-success">'. $msg . '</p>';
		}
		
		// DISPLAY FIELDS
		for ( $i = 0; $i < $totalFields; $i++ ){
			if ( $arrayVar[$i]['type'] == 'title' ){
				echo '<h3>'. __( $arrayVar[$i]['label'] ) .'</h3>';
			}elseif ( $arrayVar[$i]['type'] == 'subtitle' ){
				echo '<h4 class="subtitle"><label>'. __( $arrayVar[$i]['label'] ) .'</label></h4>';
			}else{
				$checked = get_option($arrayVar[$i]['slug']) == '1' ? 'checked="checked"' : '';
				if ( $arrayVar[$i]['type'] == 'textarea' ) {
					echo '<p id="wrapper_'. $arrayVar[$i]['slug'] .'">';
					echo '<label for="'. $arrayVar[$i]['slug'] .'" style="vertical-align:top">'. __( $arrayVar[$i]['label'] ) .'</label>';
					echo '<textarea name="'. $arrayVar[$i]['slug'] .'" id="'. $arrayVar[$i]['slug'] .'">'. stripslashes( get_option( $arrayVar[$i]['slug'] ) ) .'</textarea>';
					echo '</p>';
				}else{
					echo '<p id="wrapper_'. $arrayVar[$i]['slug'] .'">';
					echo '<label for="'. $arrayVar[$i]['slug'] .'">'. __( $arrayVar[$i]['label'] ) .'</label>';
					echo '<input '. $checked .' type="'. $arrayVar[$i]['type'] .'" name="'. $arrayVar[$i]['slug'] .'" id="'. $arrayVar[$i]['slug'] .'" value="'. stripslashes( get_option( $arrayVar[$i]['slug'] ) ) .'" />';
					echo '</p>';
				}
			}
		}
	}
	
	
	
	/***************************************************
	* CHECK WHETHER EMAIL IS SENT FOR POST
	* *************************************************/
	function nys_IsEmailSent($id){
		return get_post_meta($id, 'NYSEmailSent');
	}
	
	/***************************************************
	* GET SUBSCRIBERS EMAILS
	* *************************************************/
	function nys_SubscribersEmails(){
		global $wpdb;
		$emails = array();
		$subscriber_email = $wpdb->get_results("SELECT user_email
										FROM ". $wpdb->prefix ."users u, ". $wpdb->prefix ."usermeta um
										WHERE um.meta_key='". $wpdb->prefix ."capabilities'
										AND um.meta_value LIKE '%subscriber%'
										AND um.user_id = u.ID ");
		foreach($subscriber_email as $SubEmails){
			$emails[] = $SubEmails->user_email;
		}
		return implode(',',$emails);
	}
	
	/***************************************************
	* SEND POST NOTIFICATION EMAIL
	* *************************************************/
	function nys_NotificationEmail(){
		
		$checked = isset($_POST[NYSNotifyCheckbox]) ? $_POST[NYSNotifyCheckbox] : '';
		if ( !empty($checked) ){
			global $post;
			$PostID = $post->ID;
			
			// IF EMAIL IS NOT SENT FOR THIS POST THEN SEND EMAIL
			if ( !nys_IsEmailSent($PostID) ){
				
				$BlogName = WPBlogName;
				$EmailAdmin = WPAdminEmail;
				$EmailFrom = nys_ReplaceSubstitutes( $PostID, getOption( 'nyEmailFrom', $EmailAdmin ) );
				ini_set("sendmail_from","<$EmailFrom>");
				$EmailTo = '';
				$EmailBCC = nys_SubscribersEmails();
				$EmailHeaders  = "MIME-Version: 1.0 \r\n";
				$EmailHeaders .= "Content-type: text/html; charset=utf-8 \r\n";
				$EmailHeaders .= "From: $BlogName <$EmailFrom>\r\n";			
				$EmailHeaders .= "Bcc: ". $EmailBCC . "\r\n"; // SEND BCC MAIL - IT WILL SAVE TIME AND EXECUTION
				$EmailSubject  = __( stripcslashes( nys_ReplaceSubstitutes( $PostID, getOption( 'nyEmailSubject' ) ) ) );
				$EmailBody = getOption( 'nyEmailBody' );
				$EmailBody = __( stripcslashes( nys_ReplaceSubstitutes( $PostID, $EmailBody ) ) );
				
				$EmailHTML = "<!DOCTYPE html>
							<html>
							<head>
							<title>". $EmailSubject ."</title>
							". NYSThemeCSS ."
							</head>
							<body>
							". $EmailBody ."
							</body>
							</html>";
				
				$MailedBy = "-f $EmailAdmin";
				$SendEmail = @wp_mail( $EmailTo, $EmailSubject, $EmailHTML, $EmailHeaders, $MailedBy );
				
				// UPDATE 'NYSEmailSent' METADATA ONLY WHEN THE EMAIL IS SENT
				if($SendEmail){
					update_post_meta($PostID, 'NYSEmailSent', true);
				}
			}
		}
	}
	
	

	/***************************************************
	* DOUBLE OPT-IN EMAILS - SUB/UNSUB CONFIRMATION
	* *************************************************/
	function nys_SubUnSubConfirmEmail( $eType, $eTo, $eSubject, $eBody ){
		global $post;
		$BlogName = WPBlogName;
		$EmailAdmin = WPAdminEmail;
		$EmailFrom = nys_ReplaceSubstitutes( $post->ID, getOption( 'nyEmailFrom', $EmailAdmin ) );
		ini_set("sendmail_from","<$EmailFrom>");
		$EmailSubject  = __( nys_ReplaceSubstitutes( null, getOption( $eSubject ) ) );
		$EmailHeaders  = "MIME-Version: 1.0 \r\n";
		$EmailHeaders .= "Content-type: text/html; charset=utf-8 \r\n";
		$EmailHeaders .= "From: $BlogName <$EmailFrom>\r\n";
		$MailedBy = "-f $EmailAdmin";
		$getUser = get_user_by( 'email', $eTo );
		$fname = get_user_meta( $getUser->ID, 'first_name' );
		$fname = $fname[0] ? $fname[0] : NYSPostName;
		$EmailBody = getOption( $eBody );
		$EmailBody = str_replace("{SUBSCRIBER_NAME}", $fname, $EmailBody);
		$nysConfirmUrl = get_nysConfirmURL($eType, $eTo, $getUser->user_pass);
		$nysConfirmLabel = '';
		
		if ( $eType == 'subscribe'){
			$nysConfirmLabel = getOption('ny_subscribe_optin_label', 'Click here to confirm your subscription');
		}else if($eType == 'unsubscribe'){
			$nysConfirmLabel = getOption('ny_unsubscribe_optin_label', 'Click here to un-subscribe');
		}
		
		$EmailHTML = "<!DOCTYPE html>
					<html>
					<head>
					<title>". $BlogName ."</title>
					". NYSThemeCSS ."
					</head>
					<body>
					". __( nys_ReplaceSubstitutes( null, $EmailBody ) ) ."
					<br/>
					<p> <a href='". $nysConfirmUrl ."' rel='nofollow' >". $nysConfirmLabel ."</a></p>
					</body>
					</html>";
		@wp_mail( $eTo, $EmailSubject, $EmailHTML, $EmailHeaders, $MailedBy );
	}
?>