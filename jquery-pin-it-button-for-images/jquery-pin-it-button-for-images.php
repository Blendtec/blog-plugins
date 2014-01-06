<?php
/*
Plugin Name: jQuery Pin It Button For Images
Plugin URI: http://mrsztuczkens.me/jpibfi/
Description: Highlights images on hover and adds a "Pin It" button over them for easy pinning.
Author: Marcin Skrzypiec
Version: 1.17
Author URI: http://mrsztuczkens.me/
*/

if ( ! function_exists( 'add_action' ) ) {
	echo "Hi there!  I'm just a plugin, not much I can do when called directly.";
	exit;
}

/*
 *
 * CONSTANTS
 *
 */
define( "JPIBFI_VERSION", "1.17" );
define( "JPIBFI_VERSION_MINOR", "a" );
define( "JPIBFI_METADATA", "jpibfi_meta" );
define( "JPIBFI_SELECTION_OPTIONS", "jpibfi_selection_options" );
define( "JPIBFI_VISUAL_OPTIONS", "jpibfi_visual_options" );
define( "JPIBFI_ADVANCED_OPTIONS", "jpibfi_advanced_options" );
define( "JPIBFI_VERSION_OPTION", "jpibfi_version");

//DEFAULT PIN BUTTON IMAGE
define( "JPIBFI_IMAGE_URL", plugins_url( '/images/pinit-button.png', __FILE__ ) );
define( "JPIBFI_IMAGE_WIDTH", 65 );
define( "JPIBFI_IMAGE_HEIGHT", 41 );

/*
 *
 * FRONT END STUFF
 *
 */

/*
 * Checks if the plugin wasn't deactivated in the given post/page
 */
function jpibfi_add_plugin_to_post( $post_id ) {
	$post_meta = get_post_meta( $post_id, JPIBFI_METADATA, true );
	return empty( $post_meta )
			|| false == array_key_exists( 'jpibfi_disable_for_post', $post_meta )
			|| '1' != $post_meta['jpibfi_disable_for_post'];
}

/*
 * function copied from https://gist.github.com/wesbos/1189639
 */
function jpibfi_is_blog_page() {
	global $post;

	$post_type = get_post_type( $post );

	return ( ( is_home() || is_archive() || is_single() ) && ( $post_type == 'post' )	);
}

/*
 * True if plugin should be added to the current post/page
 */
function jpibfi_add_plugin() {

	global $post;
	$options = get_option( JPIBFI_SELECTION_OPTIONS );

	if ( is_front_page() )
		return isset( $options['show_on_home'] ) && $options['show_on_home'] == "1";
	else if ( is_single() )
		return isset( $options['show_on_single'] ) && $options['show_on_single'] == "1" ? jpibfi_add_plugin_to_post( $post->ID ) : false;
	else if ( is_page() )
		return isset( $options['show_on_page'] ) && $options['show_on_page'] == "1" ? jpibfi_add_plugin_to_post( $post->ID ) : false;
	else if ( is_category() || is_archive() || is_search() )
		return isset( $options['show_on_category'] ) && $options['show_on_category'] == "1";
	else if ( jpibfi_is_blog_page() )
		return isset( $options['show_on_blog'] ) && $options['show_on_blog'] == "1";
	return true;
}

//Adds all necessary scripts
function jpibfi_add_plugin_scripts() {
	if ( ! ( jpibfi_add_plugin() ) )
		return;

	wp_register_style( 'jquery-pin-it-button-style', plugins_url( '/css/style.css', __FILE__ ), array(), JPIBFI_VERSION . JPIBFI_VERSION_MINOR, 'all' );
	wp_enqueue_style( 'jquery-pin-it-button-style' );
	wp_enqueue_script( 'jquery-pin-it-button-script', plugins_url( '/js/script.min.js', __FILE__ ), array( 'jquery' ), JPIBFI_VERSION . JPIBFI_VERSION_MINOR, false );

	$visual_options = get_option( JPIBFI_VISUAL_OPTIONS );
	$selection_options = get_option( JPIBFI_SELECTION_OPTIONS );
	$advanced_options = get_option( JPIBFI_ADVANCED_OPTIONS );
	$use_custom_image = isset( $visual_options[ 'use_custom_image' ] ) && $visual_options[ 'use_custom_image' ] == "1";

	$parameters_array = array(
		'image_selector' 		=> $selection_options['image_selector'],
		'disabled_classes' 	=> $selection_options['disabled_classes'],
		'enabled_classes' 	=> $selection_options['enabled_classes'],
		'description_option'=> $visual_options['description_option'],
		'use_post_url' 			=> isset ( $visual_options['use_post_url'] ) ? $visual_options['use_post_url'] : '0',
		'min_image_height'	=> $selection_options['min_image_height'],
		'min_image_width'		=> $selection_options['min_image_width'],
		'site_title'				=> get_bloginfo( 'name', 'display' ),
		'mode'							=> $visual_options[ 'mode' ],
		'button_position'		=> $visual_options[ 'button_position' ],
		'debug'							=> isset( $advanced_options[ 'debug'] ) ? $advanced_options[ 'debug'] : '0',
		'pin_image_height' 	=> $use_custom_image ? $visual_options['custom_image_height'] : JPIBFI_IMAGE_HEIGHT,
		'pin_image_width'		=> $use_custom_image ? $visual_options['custom_image_width'] : JPIBFI_IMAGE_WIDTH,
		'button_margin_top'	=> $visual_options[ 'button_margin_top' ],
		'button_margin_bottom'	=> $visual_options[ 'button_margin_bottom' ],
		'button_margin_left'=> $visual_options[ 'button_margin_left' ],
		'button_margin_right'	=> $visual_options[ 'button_margin_right' ]
	);

	wp_localize_script( 'jquery-pin-it-button-script', 'jpibfi_options', $parameters_array );
}

add_action( 'wp_enqueue_scripts', 'jpibfi_add_plugin_scripts' );

function jpibfi_print_header_style_action() {
	if ( ! ( jpibfi_add_plugin() ) )
		return;

	$options = get_option( JPIBFI_VISUAL_OPTIONS );

	$use_custom_image = isset( $options[ 'use_custom_image' ] ) && $options[ 'use_custom_image' ] == "1";

	$width  = $use_custom_image ? $options['custom_image_width'] : JPIBFI_IMAGE_WIDTH;
	$height = $use_custom_image ? $options['custom_image_height'] : JPIBFI_IMAGE_HEIGHT;

	if ( isset( $options[ 'retina_friendly' ] ) && $options[ 'retina_friendly' ] == '1' ){
		$width = floor( $width / 2 );
		$height = floor ( $height / 2 );
	}

	$url = $use_custom_image ? $options['custom_image_url'] : JPIBFI_IMAGE_URL;

	?>
	<!--[if lt IE 9]>
		<style type="text/css">
			.pinit-overlay {
				background-image: url( '<?php echo plugins_url( '/images/transparency_0.png', __FILE__ ); ?>' ) !important;
			}
		</style>
	<![endif]-->

<style type="text/css">
	a.pinit-button {
		width: <?php echo $width; ?>px !important;
		height: <?php echo $height; ?>px !important;
		background: transparent url('<?php echo $url; ?>') no-repeat 0 0 !important;
		background-size: <?php echo $width; ?>px <?php echo $height; ?>px !important
	}

	a.pinit-button.pinit-top-left {
		margin: <?php echo $options['button_margin_top']; ?>px 0 0 <?php echo $options['button_margin_left']; ?>px;
	}

	a.pinit-button.pinit-top-right {
		margin: <?php echo $options['button_margin_top']; ?>px <?php echo $options['button_margin_right']; ?>px 0 0;
	}

	a.pinit-button.pinit-bottom-left {
		margin: 0 0 <?php echo $options['button_margin_bottom']; ?>px <?php echo $options['button_margin_left']; ?>px;
	}

	a.pinit-button.pinit-bottom-right {
		margin: 0 <?php echo $options['button_margin_right']; ?>px <?php echo $options['button_margin_bottom']; ?>px 0;
	}

	img.pinit-hover {
		opacity: <?php echo (1 - $options['transparency_value']); ?> !important;
		filter:alpha(opacity=<?php echo (1 - $options['transparency_value']) * 100; ?>) !important; /* For IE8 and earlier */
	}
</style>
<?php
}

add_action( 'wp_head', 'jpibfi_print_header_style_action' );

/*
 * Adds data-jpibfi-description attribute to each image that is added through media library. The value is the "Description"  of the image from media library.
 * This piece of code uses a lot of code from the Photo Protect http://wordpress.org/plugins/photo-protect/ plugin
 */
function jpibfi_add_description_attribute_to_images( $content ) {

	$imgPattern = '/<img[^>]*>/i';
	$attrPattern = '/ ([\w]+)[ ]*=[ ]*([\"\'])(.*?)\2/i';

	preg_match_all($imgPattern, $content, $images, PREG_SET_ORDER);

	foreach ($images as $img) {

		preg_match_all($attrPattern, $img[0], $attributes, PREG_SET_ORDER);

		$newImg = '<img';
		$src = '';
		$id = '';

		foreach ($attributes as $att) {
			$full = $att[0];
			$name = $att[1];
			$value = $att[3];

			$newImg .= $full;

			if ('class' == $name ) {
				$id = jpibfi_get_post_id_from_image_classes( $value );
			}	else if ( 'src' == $name ) {
				$src = $value;
			}
		}

		$description = jpibfi_get_image_description( $id, $src );
		$newImg .= ' data-jpibfi-description="' . esc_attr( $description ) . '" />';
		$content = str_replace($img[0], $newImg, $content);
	}

	return $content;
}

/*
 * Adds a hidden field with url and and description of the pin that's used when user uses "Link to individual page"
 * Thanks go to brocheafoin, who added most of the code that handles creating description
 */
function jpibfi_prepare_the_content( $content ) {
	if ( ! jpibfi_add_plugin() )
		return $content;
	global $post;

	$options = get_option( JPIBFI_VISUAL_OPTIONS );

	$add_attributes = false == is_singular() && isset( $options[ 'use_post_url' ] ) && '1' == $options[ 'use_post_url' ];

	$attributes_html = '';

	//if we need to add additional attributes to handle use_post_url setting
	if ( $add_attributes ){
		//if page description should be used as pin description and an excerpt for the post exists
		if ( has_excerpt( $post->ID ) && 2 == $options[ 'description_option' ] )
			$description = wp_kses( $post->post_excerpt, array() );
		else
			$description = get_the_title($post->ID);

		$attributes_html .= 'data-jpibfi-url="' . get_permalink( $post->ID ) . '" ' ;
		$attributes_html .= 'data-jpibfi-description ="' . esc_attr( $description ) . '" ';
	}

	$input_html = '<input class="jpibfi" type="hidden" ' . $attributes_html . '>';
	$content = $input_html . $content;

	$add_image_descriptions = '5' == $options[ 'description_option' ];

	//if we need to add data-jpibfi-description to each image
	if ( $add_image_descriptions ){
		$content = jpibfi_add_description_attribute_to_images( $content );
	}

	return $content;
}

add_filter( "the_content", 'jpibfi_prepare_the_content' );
add_filter( "the_excerpt", 'jpibfi_prepare_the_content' );

/*
 *
 * POST EDITOR CODE
 *
 */

function jpibfi_add_meta_box() {
	//for posts
	add_meta_box(
		'jpibfi_settings_id', // this is HTML id of the box on edit screen
		'jQuery Pin It Button for Images - ' . __( 'Settings', 'jpibfi' ), // title of the box
		'jpibfi_print_meta_box', // function to be called to display the checkboxes, see the function below
		'post', // on which edit screen the box should appear
		'side', // part of page where the box should appear
		'default' // priority of the box
	);

	//for pages
	add_meta_box(
		'jpibfi_settings_id',
			'jQuery Pin It Button for Images - ' . __( 'Settings', 'jpibfi' ),
		'jpibfi_print_meta_box',
		'page',
		'side',
		'default'
	);
}

add_action( 'add_meta_boxes', 'jpibfi_add_meta_box' );

// display the metabox
function jpibfi_print_meta_box( $post, $metabox ) {

	wp_nonce_field( plugin_basename( __FILE__ ), 'jpibfi_nonce' );

	$post_meta = get_post_meta( $post->ID, JPIBFI_METADATA, true );
	$checked = isset( $post_meta ) && isset( $post_meta['jpibfi_disable_for_post'] ) && $post_meta['jpibfi_disable_for_post'] == '1';

	echo '<input type="checkbox" id="jpibfi_disable_for_post" name="jpibfi_disable_for_post" value="1"'	. checked( $checked, true, false ) . '>';
	echo '<label for="jpibfi_disable_for_post">' . __( 'Disable "Pin it" button for this post (works only on single pages/posts)', 'jpibfi' ) . '</label><br />';
}

function jpibfi_save_meta_data( $post_id ) {

	//check user's permissions
	if ( ! current_user_can( 'edit_post', $post_id ) )
		return;
	// check if this isn't an auto save
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		return;
	// security check = updating possible only using post edit form
	if ( !isset( $_POST['jpibfi_nonce'] ) || ! wp_verify_nonce( $_POST['jpibfi_nonce'], plugin_basename( __FILE__ ) ) )
		return;


	$post_meta = array( 'jpibfi_disable_for_post' => '0' );
	// now store data in custom fields based on checkboxes selected
	$post_meta['jpibfi_disable_for_post'] =
			isset( $_POST['jpibfi_disable_for_post'] ) && $_POST['jpibfi_disable_for_post'] == '1' ? '1' : '0';

	if ( $post_meta['jpibfi_disable_for_post'] == '1' )
		update_post_meta( $post_id, JPIBFI_METADATA, $post_meta );
	else
		delete_post_meta( $post_id, JPIBFI_METADATA );
}

add_action( 'save_post', 'jpibfi_save_meta_data' );

//END POST EDITOR CODE

//Delete everything the plugin added into DB
function jpibfi_uninstall_plugin() {

	//delete all added options
	delete_option( JPIBFI_SELECTION_OPTIONS );
	delete_option( JPIBFI_VISUAL_OPTIONS );
	delete_option( JPIBFI_ADVANCED_OPTIONS );
	delete_option( JPIBFI_VERSION_OPTION );

	//delete added metadata from all posts
	delete_post_meta_by_key( JPIBFI_METADATA );
}

register_uninstall_hook( __FILE__, 'jpibfi_uninstall_plugin' );

/*
 *
 * ADMIN SETTINGS
 *
 */

function jpibfi_load_plugin_textdomain() {

	load_plugin_textdomain( 'jpibfi', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}

add_action( 'plugins_loaded', 'jpibfi_load_plugin_textdomain' );

/*
 * Function updates DB if it detects new version of the plugin
 */
function jpibfi_update_plugin() {

	$version = get_option( JPIBFI_VERSION_OPTION );

	//if the plugin isn't installed at all or the version is below 1.00
	if ( false == $version ) {
		//rewrite old settings (if they exist) to new settings
		jpibfi_convert_settings();

		//if new settings are still absent (plugin wasn't installed previously), we need to add them
		if( false == get_option( JPIBFI_VISUAL_OPTIONS ) )
			add_option( JPIBFI_VISUAL_OPTIONS, jpibfi_default_visual_options() );

		if( false == get_option( JPIBFI_SELECTION_OPTIONS ) )
			add_option( JPIBFI_SELECTION_OPTIONS, jpibfi_default_selection_options() );

		if( false == get_option( JPIBFI_ADVANCED_OPTIONS ) )
			add_option( JPIBFI_ADVANCED_OPTIONS, jpibfi_default_advanced_options() );

	}	else if ( (float)$version < (float)JPIBFI_VERSION ) { //if the plugins version is older than current, we need to update options with new defaults

		$option = get_option( JPIBFI_VISUAL_OPTIONS );
		$option['button_position'] = '5' == $option['button_position'] ? '0' : $option['button_position'];
		jpibfi_update_option_fields( $option, jpibfi_default_visual_options(), JPIBFI_VISUAL_OPTIONS );

		$option = get_option( JPIBFI_SELECTION_OPTIONS );
		jpibfi_update_option_fields( $option, jpibfi_default_selection_options(), JPIBFI_SELECTION_OPTIONS );

		$option = get_option( JPIBFI_ADVANCED_OPTIONS );
		jpibfi_update_option_fields( $option, jpibfi_default_advanced_options(), JPIBFI_ADVANCED_OPTIONS );
	}

	//update the version of the plugin stored in option
	update_option( JPIBFI_VERSION_OPTION, JPIBFI_VERSION );
}

register_activation_hook( __FILE__, 'jpibfi_update_plugin' );
add_action( 'plugins_loaded', 'jpibfi_update_plugin' );

/*
 * This function converts old settings from versions earlier than 1.00 (if there are any) format to new settings format (changed names of options, etc)
 */
function jpibfi_convert_settings() {
	//get all old options
	$basic_option = get_option( "jptbfi_options" );
	$advanced_options = get_option( 'jptbfi_advanced_options' );

	//if options don't exist, there's nothing to convert so the function ends
	if ( false == $basic_option || false == $advanced_options )
		return;

	//rewrite them to new options
	$default_selection_options = jpibfi_default_selection_options();

	$selection_options = array();
	$selection_options[ 'image_selector' ] = isset ( $basic_option[ 'image_selector' ] ) ? $basic_option[ 'image_selector' ] : $default_selection_options[ 'image_selector' ];
	$selection_options[ 'disabled_classes' ] = isset ( $basic_option[ 'disabled_classes' ] ) ? $basic_option[ 'disabled_classes' ] : $default_selection_options[ 'disabled_classes' ];
	$selection_options[ 'enabled_classes' ] = isset ( $basic_option[ 'enabled_classes' ] ) ? $basic_option[ 'enabled_classes' ] : $default_selection_options[ 'enabled_classes' ];
	$selection_options[ 'min_image_height' ] = isset ( $basic_option[ 'min_image_height' ] ) ? $basic_option[ 'min_image_height' ] : $default_selection_options[ 'min_image_height' ];
	$selection_options[ 'min_image_width' ] = isset ( $basic_option[ 'min_image_width' ] ) ? $basic_option[ 'min_image_width' ] : $default_selection_options[ 'min_image_width' ];

	$selection_options[ 'show_on_home' ] = isset ( $advanced_options[ 'on_home' ] ) && '1' == $advanced_options[ 'on_home' ] ? '1' : '0';
	$selection_options[ 'show_on_single' ] = isset ( $advanced_options[ 'on_single' ] ) && '1' == $advanced_options[ 'on_single' ] ? '1' : '0';
	$selection_options[ 'show_on_page' ] = isset ( $advanced_options[ 'on_page' ] ) && '1' == $advanced_options[ 'on_page' ] ? '1' : '0';
	$selection_options[ 'show_on_category' ] = isset ( $advanced_options[ 'on_category' ] )&& '1' == $advanced_options[ 'on_category' ] ? '1' : '0';
	$selection_options[ 'show_on_blog' ] = isset ( $advanced_options[ 'on_blog' ] ) && '1' == $advanced_options[ 'on_blog' ] ? '1' : '0';

	update_option( JPIBFI_SELECTION_OPTIONS, $selection_options );
	//just in case any new fields are added to the option in the future
	jpibfi_update_option_fields( $selection_options, jpibfi_default_selection_options(), JPIBFI_SELECTION_OPTIONS );

	//visual options
	$default_visual_options = jpibfi_default_visual_options();

	$visual_options = array();
	$visual_options[ 'description_option' ] = isset ( $basic_option[ 'description_option' ] ) ? $basic_option[ 'description_option' ] : $default_visual_options[ 'description_option' ];
	$visual_options[ 'transparency_value' ] = isset ( $basic_option[ 'transparency_value' ] ) ? $basic_option[ 'transparency_value' ] : $default_visual_options[ 'transparency_value' ];
	$visual_options[ 'use_custom_image' ] = isset ( $basic_option[ 'use_custom_image' ] ) && '1' == $basic_option[ 'use_custom_image' ] ? '1' : '0';
	$visual_options[ 'custom_image_url' ] = isset ( $basic_option[ 'custom_image_url' ] ) ? $basic_option[ 'custom_image_url' ] : $default_visual_options[ 'custom_image_url' ];
	$visual_options[ 'custom_image_height' ] = isset ( $basic_option[ 'custom_image_height' ] ) ? $basic_option[ 'custom_image_height' ] : $default_visual_options[ 'custom_image_height' ];
	$visual_options[ 'custom_image_width' ] = isset ( $basic_option[ 'custom_image_width' ] ) ? $basic_option[ 'custom_image_width' ] : $default_visual_options[ 'custom_image_width' ];
	$visual_options[ 'use_post_url' ] = isset ( $basic_option[ 'use_post_url' ] ) && '1' == $basic_option[ 'use_post_url' ] ? '1' : '0';
	$visual_options[ 'button_position' ] = isset ( $basic_option[ 'button_position' ] )
			?	( '5' == $basic_option[ 'button_position' ] ? '0' : $basic_option[ 'button_position' ] )
			: $default_visual_options[ 'button_position' ];

	update_option( JPIBFI_VISUAL_OPTIONS, $visual_options );
	//just in case any new fields are added to the option in the future
	jpibfi_update_option_fields( $visual_options, jpibfi_default_visual_options(), JPIBFI_VISUAL_OPTIONS );

	//delete all old options
	delete_option( "jptbfi_options" );
	delete_option( 'jptbfi_advanced_options' );
	delete_option( 'jpibfi_button_custom_css' );
}

function jpibfi_print_admin_page_action() {
	$page = add_submenu_page(
		'options-general.php',
		'jQuery Pin It Button For Images', 	// The value used to populate the browser's title bar when the menu page is active
		'jQuery Pin It Button For Images', // The text of the menu in the administrator's sidebar
		'administrator',					// What roles are able to access the menu
		'jpibfi_settings',				// The ID used to bind submenu items to this menu
		'jpibfi_print_admin_page'				// The callback function used to render this menu
	);

	add_action( 'admin_print_styles-' . $page, 'jpibfi_add_admin_site_scripts' );
}

add_action( 'admin_menu', 'jpibfi_print_admin_page_action' );

/*
 * adds admin scripts
 */
function jpibfi_add_admin_site_scripts() {

	wp_register_style( 'jquery-pin-it-button-admin-style', plugins_url( '/css/admin.css', __FILE__ ), array(), JPIBFI_VERSION . JPIBFI_VERSION_MINOR, 'all' );
	wp_enqueue_style( 'jquery-pin-it-button-admin-style' );

	wp_enqueue_script( 'jquery-pin-it-button-admin-script', plugins_url( '/js/admin.js', __FILE__ ), array( 'jquery' ), JPIBFI_VERSION . JPIBFI_VERSION_MINOR, false );

	wp_register_script( 'angular', plugins_url( '/js/angular.min.js', __FILE__ ) , '', '1.0.7', false );
	wp_enqueue_script( 'jquery-pin-it-button-admin-angular-script', plugins_url( '/js/admin-angular.js', __FILE__ ), array( 'angular' ), JPIBFI_VERSION . JPIBFI_VERSION_MINOR, false );

	if ( function_exists( "wp_enqueue_media") ) {
		wp_enqueue_media();
		wp_enqueue_script( 'jpibfi-upload-new', plugins_url( '/js/upload-button-new.js', __FILE__ ), array(), JPIBFI_VERSION . JPIBFI_VERSION_MINOR, false );
	} 	else {
		wp_enqueue_script( 'jpibfi-upload-old', plugins_url( '/js/upload-button-old.js', __FILE__ ), array('thickbox', 'media-upload' ), JPIBFI_VERSION . JPIBFI_VERSION_MINOR, false );
	}
}


function jpibfi_plugin_settings_filter( $links ) {
	$settings_link = '<a href="options-general.php?page=jpibfi_settings">' . __( 'Settings', 'jpibfi' ) . '</a>';
	array_unshift( $links, $settings_link );
	return $links;
}

$jpibfi_plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$jpibfi_plugin", 'jpibfi_plugin_settings_filter' );

function jpibfi_print_admin_page() {
	?>
	<!-- Create a header in the default WordPress 'wrap' container -->
	<div class="wrap">

		<h2><?php _e( 'jQuery Pin It Button For Images Options', 'jpibfi' ); ?></h2>

		<?php
		$tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'selection_options';
		if ( 'selection_options' != $tab && 'visual_options' != $tab  && 'advanced_options' != $tab )
			$tab = 'selection_options';
		?>
		<div id="icon-plugins" class="icon32"></div>
		<h2 class="nav-tab-wrapper">
			<a href="?page=jpibfi_settings&tab=selection_options" class="nav-tab <?php echo 'selection_options' == $tab ? 'nav-tab-active' : ''; ?>"><?php _e( 'Selection Settings', 'jpibfi' ); ?></a>
			<a href="?page=jpibfi_settings&tab=visual_options" class="nav-tab <?php echo 'visual_options' == $tab ? 'nav-tab-active' : ''; ?>"><?php _e( 'Visual Settings', 'jpibfi' ); ?></a>
			<a href="?page=jpibfi_settings&tab=advanced_options" class="nav-tab <?php echo  'advanced_options' == $tab ? 'nav-tab-active' : ''; ?>"><?php _e( 'Advanced Settings', 'jpibfi' ); ?></a>
		</h2>

		<p>
			<?php
			printf( __('If you would like to support development of the plugin, please %sdonate%s.', 'jpibfi') . ' ', '<a href="http://bit.ly/Uw2mEP" target="_blank" rel="nofollow"><b>', '</b></a>' );
			printf( __('If you experience issues with the plugin, check out the %ssupport forum%s.', 'jpibfi') . ' ', '<a href="http://wordpress.org/support/plugin/jquery-pin-it-button-for-images" target="_blank" rel="nofollow"><b>', '</b></a>' );
			printf( __('To help promote the plugin, %sleave a review%s.', 'jpibfi') . ' ', '<a href="http://wordpress.org/support/view/plugin-reviews/jquery-pin-it-button-for-images" target="_blank" rel="nofollow"><b>', '</b></a>' );
			printf( __('If you have any suggestions for improvements, %suse the feedback form%s.', 'jpibfi') . ' ', '<a href="http://mrsztuczkens.me/jquery-pin-it-button-for-images-feedback-form/" target="_blank" rel="nofollow"><b>', '</b></a>' );
			?>
		</p>
		<form method="post" action="options.php" ng-app="jpibfiApp" ng-controller="jpibfiController">
			<?php

			if ( 'selection_options' == $tab ) {
				settings_fields( 'jpibfi_selection_options' );
				do_settings_sections( 'jpibfi_selection_options' );
			} else if ( 'visual_options' == $tab) {
				settings_fields( 'jpibfi_visual_options' );
				do_settings_sections( 'jpibfi_visual_options' );
			} else if ( 'advanced_options' == $tab) {
				settings_fields( 'jpibfi_advanced_options' );
				do_settings_sections( 'jpibfi_advanced_options' );
			}

			submit_button();
			?>
		</form>
		<p>
			The Silk Icon Set is provided by Mark James and is availble from <a href="http://famfamfam.com/lab/icons/silk/">FamFamFam</a>	under the	<a href="http://creativecommons.org/licenses/by/2.5/">Creative Commons Attribution 2.5 License</a>.
		</p>
	</div>
<?php

	//cumbersome, but needed for error management to work properly in WP 3.3
	delete_option( JPIBFI_SELECTION_OPTIONS . '_errors' );
	delete_option( JPIBFI_VISUAL_OPTIONS . '_errors' );
}

/*
 *
 * SELECTION OPTIONS
 *
 */

/*
 * Default values for selection options section
 */
function jpibfi_default_selection_options() {
	$defaults = array(
		'image_selector'      => 'div.jpibfi_container img',
		'disabled_classes'    => 'nopin;wp-smiley',
		'enabled_classes'     => '',
		'min_image_height'		=> '0',
		'min_image_width'			=> '0',
		'show_on_home'     		=> '1',
		'show_on_single'   		=> '1',
		'show_on_page'    		=> '1',
		'show_on_category' 		=> '1',
		'show_on_blog'				=> '1'
	);

	return $defaults;
}

/*
 * Defines selection options section and adds all required fields
 */
function jpibfi_initialize_selection_options() {

	// First, we register a section.
	add_settings_section(
		'selection_options_section',			// ID used to identify this section and with which to register options
		__( 'Selection', 'jpibfi' ),		// Title to be displayed on the administration page
		'jpibfi_selection_options_callback',	// Callback used to render the description of the section
		'jpibfi_selection_options'		// Page on which to add this section of options
	);

	//lThen add all necessary fields to the section
	add_settings_field(
		'image_selector',						// ID used to identify the field throughout the plugin
		__( 'Image selector', 'jpibfi' ),							// The label to the left of the option interface element
		'jpibfi_image_selector_callback',	// The name of the function responsible for rendering the option interface
		'jpibfi_selection_options',	// The page on which this option will be displayed
		'selection_options_section',			// The name of the section to which this field belongs
		array(								// The array of arguments to pass to the callback. In this case, just a description.
			sprintf ( __( 'jQuery selector for all the images that should have the "Pin it" button. Set the value to %s if you want the "Pin it" button to appear only on images in content or %s to appear on all images on site (including sidebar, header and footer). If you know a thing or two about jQuery, you might use your own selector. %sClick here%s to read about jQuery selectors.', 'jpibfi' ),
				'<a href="#" class="jpibfi_selector_option">div.jpibfi_container img</a>',
 				'<a href="#" class="jpibfi_selector_option">img</a>',
				'<a href="http://api.jquery.com/category/selectors/" target="_blank">',
				'</a>'
			)
		)
	);

	add_settings_field(
		'disabled_classes',
		__( 'Disabled classes', 'jpibfi' ),
		'jpibfi_disabled_classes_callback',
		'jpibfi_selection_options',
		'selection_options_section',
		array(
			__( 'Pictures with these CSS classes won\'t show the "Pin it" button. Please separate multiple classes with semicolons. Spaces are not accepted.', 'jpibfi' ),
		)
	);

	add_settings_field(
		'enabled_classes',
		__( 'Enabled classes', 'jpibfi' ),
		'jpibfi_enabled_classes_callback',
		'jpibfi_selection_options',
		'selection_options_section',
		array(
			__( 'Only pictures with these CSS classes will show the "Pin it" button. Please separate multiple classes with semicolons. If this field is empty, images with any (besides disabled ones) classes will show the Pin It button.', 'jpibfi' ),
		)
	);

	add_settings_field(
		'show_on_field',
		__( 'On which pages the "Pin it" button should be shown', 'jpibfi' ),
		'jpibfi_show_on_field_callback',
		'jpibfi_selection_options',
		'selection_options_section',
		array(
			__( 'Check on which pages you want the Pinterest button to show up.', 'jpibfi' ),
		)
	);

	add_settings_field(
		'min_image',
		__( 'Minimum resolution that should trigger the "Pin it" button to show up', 'jpibfi' ),
		'jpibfi_min_image_callback',
		'jpibfi_selection_options',
		'selection_options_section',
		array(
			__( 'If you\'d like the "Pin it" button to not show up on small images (e.g. social media icons), just set the appropriate values above. The default values cause the "Pin it" button to show on every eligible image.', 'jpibfi' ),
		)
	);

	register_setting(
		'jpibfi_selection_options',
		'jpibfi_selection_options',
		'jpibfi_sanitize_selection_options'
	);
}

add_action( 'admin_init', 'jpibfi_initialize_selection_options' );

function jpibfi_selection_options_callback() {
	echo '<p>' . __('Which images can be pinned', 'jpibfi') . '</p>';
}

function jpibfi_image_selector_callback( $args ) {

	$options = jpibfi_get_selection_options();

	$selector = esc_attr( $options['image_selector'] );

	echo '<input type="text" id="image_selector" name="jpibfi_selection_options[image_selector]" value="' . $selector . '"/>';
	echo jpibfi_create_description( $args[0] );
}

function jpibfi_disabled_classes_callback( $args ){

	$options = jpibfi_get_selection_options();
	$value = esc_attr( $options[ 'disabled_classes' ] );

	?>
	<input type="hidden" name="jpibfi_selection_options[disabled_classes]" value="{{ disabledClassesFormatted }}" ng-init="initDisabledClasses('<?php echo $value; ?>')">
	<span ng-hide="disabledClasses.length > 0">
		<?php echo jpibfi_create_description( __( 'No classes added.', 'jpibfi' ) ); ?>
	</span>
	<ul class="jpibfi-classes-list" ng-hide="disabledClasses.length == 0">
		<li ng-repeat="class in disabledClasses">
			<a ng-click="deleteDisabledClass(class)">X</a><span>{{ class }}</span>
		</li>
	</ul>
	<div>
		<div>
			<label for="disabledClass" ><?php _e( 'Class name', 'jpibfi' ); ?></label>
			<input id="disabledClass" type="text" ng-model="disabledClass">
			<button type="button" ng-click="addDisabledClass(disabledClass)"><?php _e( 'Add to list', 'jpibfi' ); ?></button>
		</div>
	</div>

	<?php

	echo jpibfi_create_description( $args[0] );
	echo jpibfi_create_errors( 'disabled_classes' );
}

function jpibfi_enabled_classes_callback( $args ){

	$options = jpibfi_get_selection_options();
	$value = esc_attr( $options[ 'enabled_classes' ] );

	?>
	<input type="hidden" name="jpibfi_selection_options[enabled_classes]" value="{{ enabledClassesFormatted }}" ng-init="initEnabledClasses('<?php echo $value; ?>')">
	<span ng-hide="enabledClasses.length > 0">
		<?php echo jpibfi_create_description( __( 'No classes added.', 'jpibfi' ) ); ?>
	</span>
	<ul class="jpibfi-classes-list" ng-hide="enabledClasses.length == 0">
		<li ng-repeat="class in enabledClasses">
			<a ng-click="deleteEnabledClass(class)">X</a><span>{{ class }}</span>
		</li>
	</ul>
	<div>
		<div>
			<label for="enabledClass" ><?php _e( 'Class name', 'jpibfi' ); ?></label>
			<input id="enabledClass" type="text" ng-model="enabledClass">
			<button type="button" ng-click="addEnabledClass(enabledClass)"><?php _e( 'Add to list', 'jpibfi' ); ?></button>
		</div>
	</div>

	<?php

	echo jpibfi_create_description( $args[0] );
	echo jpibfi_create_errors( 'enabled_classes' );
}

function jpibfi_show_on_field_callback( $args ) {
	$options = jpibfi_get_selection_options();

	$show_on_home = jpibfi_exists_and_equal_to( $options, 'show_on_home', '1' );
	$show_on_page = jpibfi_exists_and_equal_to( $options, 'show_on_page', '1' );
	$show_on_single = jpibfi_exists_and_equal_to( $options, 'show_on_single', '1' );
	$show_on_category = jpibfi_exists_and_equal_to( $options, 'show_on_category', '1' );
	$show_on_blog = jpibfi_exists_and_equal_to( $options, 'show_on_blog', '1' );
	?>

	<input type="checkbox" id="show_on_home" name="jpibfi_selection_options[show_on_home]" <?php checked( true, $show_on_home ); ?> value="1" />
	<label for="show_on_home"><?php _e( 'Home page', 'jpibfi' ); ?></label><br/>
	<input type="checkbox" id="show_on_page" name="jpibfi_selection_options[show_on_page]" <?php checked( true, $show_on_page ); ?> value="1" />
	<label for="show_on_page"><?php _e( 'Pages', 'jpibfi' ); ?></label><br />
	<input type="checkbox" id="show_on_single" name="jpibfi_selection_options[show_on_single]" <?php checked( true, $show_on_single ); ?> value="1" />
	<label for="show_on_single"><?php _e( 'Single posts', 'jpibfi' ); ?></label><br />
	<input type="checkbox" id="show_on_category"	name="jpibfi_selection_options[show_on_category]" <?php checked( true, $show_on_category ); ?> value="1" />
	<label for="show_on_category"><?php _e( 'Category and archive pages', 'jpibfi' ); ?></label><br />
	<input type="checkbox" id="show_on_blog"	name="jpibfi_selection_options[show_on_blog]" <?php checked( true, $show_on_blog ); ?> value="1" />
	<label for="show_on_blog"><?php _e( 'Blog pages', 'jpibfi' ); ?></label>

	<?php
	echo jpibfi_create_description( $args[0] );
}

function jpibfi_min_image_callback( $args ) {
	$options = jpibfi_get_selection_options();

	$min_image_height = $options[ 'min_image_height' ];
	$min_image_width = $options[ 'min_image_width' ];
	?>

	<p>
		<label for="min_image_height"><?php _e('Height', 'jpibfi'); ?></label>
		<input type="number" min="0" step="1" id="min_image_height" name="jpibfi_selection_options[min_image_height]" value="<?php echo $min_image_height; ?>"
					 class="small-text" /> px
		<?php echo jpibfi_create_errors( 'min_image_height' ); ?>
	</p>

	<p>
		<label for="min_image_width"><?php _e('Width', 'jpibfi'); ?></label>
		<input type="number" min="0" step="1" id="min_image_width" name="jpibfi_selection_options[min_image_width]" value="<?php echo $min_image_width; ?>"
					 class="small-text" /> px
		<?php echo jpibfi_create_errors( 'min_image_width' ); ?>
	</p>

	<?php

	echo jpibfi_create_description( $args[0] );
}

function jpibfi_sanitize_selection_options( $input ) {

	foreach( $input as $key => $value ) {
		switch($key) {
			case 'disabled_classes':
			case 'enabled_classes':
				if ( false == jpibfi_contains_css_class_names_or_empty( $input [ $key ] ) ) {

					$field = '';
					if ( 'disabled_classes' == $key )
						$field = __( 'Disabled classes', 'jpibfi' );
					else if ( 'enabled_classes' == $key )
						$field = __( 'Enabled classes', 'jpibfi' );

					add_settings_error(
						$key,
						esc_attr( 'settings_updated' ),
						$field . ' - ' . __('the given value doesn\'t meet the requirements. Please correct it and try again.', 'jpibfi')
					);
				}
				break;
			case 'min_image_height':
			case 'min_image_width':
				if ( !is_numeric( $value ) || $value < 0 ) {

					$field = '';
					if ( 'min_image_height' == $key )
						$field = __( 'Minimum image height', 'jpibfi' );
					else if ( 'min_image_width' == $key )
						$field = __( 'Minimum image width', 'jpibfi' );

					add_settings_error(
						$key,
						esc_attr( 'settings_updated' ),
						$field . ' - ' . sprintf ( __('value must be a number greater or equal to %d.', 'jpibfi'), '0' )
					);
				}
				break;
		}

	}

	$errors = get_settings_errors();

	if ( count( $errors ) > 0 ) {

		update_option( JPIBFI_SELECTION_OPTIONS . '_errors', $input );
		return get_option( JPIBFI_SELECTION_OPTIONS );

	} else {

		delete_option( JPIBFI_SELECTION_OPTIONS . '_errors' );
		return $input;

	}
}

function jpibfi_get_selection_options() {
	return jpibfi_get_options( JPIBFI_SELECTION_OPTIONS );
}

/*
 *
 * VISUAL OPTIONS
 *
 */

/*
 * Default values for visual options section
 */
function jpibfi_default_visual_options() {

	$defaults = array(
		'transparency_value'  => '0.5',
		'description_option'  => '1',
		'use_custom_image'    => '0',
		'custom_image_url'    => '',
		'custom_image_height' => '0',
		'custom_image_width'  => '0',
		'use_post_url'        => '0',
		'button_position'			=> '0',
		'mode'								=> 'static',
		'button_margin_top'		=> '20',
		'button_margin_right'	=> '20',
		'button_margin_bottom'=> '20',
		'button_margin_left'	=> '20',
		'retina_friendly'     => '0'
	);

	return $defaults;
}

/*
 * Defines visual options section and defines all required fields
 */
function jpibfi_initialize_visual_options() {

	// First, we register a section.
	add_settings_section(
		'visual_options_section',			// ID used to identify this section and with which to register options
		__( 'Visual', 'jpibfi' ),		// Title to be displayed on the administration page
		'jpibfi_visual_options_callback',	// Callback used to render the description of the section
		'jpibfi_visual_options'		// Page on which to add this section of options
	);

	//Then add all necessary fields to the section
	add_settings_field(
		'mode',
		__( 'Mode', 'jpibfi' ),
		'jpibfi_mode_option_callback',
		'jpibfi_visual_options',
		'visual_options_section',
		array(
			__( 'Static mode adds a layer on the top of the image that restricts image download, but works on websites that protect images download. Dynamic mode doesn\'t add that layer and allows image download. If you\'re experiencing issues with static mode, try using dynamic mode.', 'jpibfi' ),
		)
	);

	add_settings_field(
		'description_option',
		__( 'Description source', 'jpibfi' ),
		'jpibfi_description_option_callback',
		'jpibfi_visual_options',
		'visual_options_section',
		array(
			__( 'From where the Pinterest message should be taken. Please note that "Image description" works properly only for images that were added to your Media Library.', 'jpibfi' ),
		)
	);

	add_settings_field(
		'use_post_url',
		__( 'Linked page', 'jpibfi' ),
		'jpibfi_use_post_url_callback',
		'jpibfi_visual_options',
		'visual_options_section',
		array(
			__( 'When checked, the link on Pinterest will always point to the individual page with the image and title of this individual page will be used if you\'ve selected Title as the description source, even when the image was pinned on an archive page, category page or homepage. If false, the link will point to the URL the user is currently on.', 'jpibfi' ),
		)
	);

	add_settings_field(
		'transparency_value',
		__( 'Transparency value', 'jpibfi' ),
		'jpibfi_transparency_value_callback',
		'jpibfi_visual_options',
		'visual_options_section',
		array(
			__( 'This setting sets the transparency of the image.', 'jpibfi' ),
		)
	);

	add_settings_field(
		'custom_pin_it_button',
		__( 'Custom "Pin It" button', 'jpibfi' ),
		'jpibfi_custom_pin_it_button_callback',
		'jpibfi_visual_options',
		'visual_options_section',
		array(
			__( 'Check the <b>Use custom image</b> checkbox, specify image\'s URL, height and width to use your own Pinterest button design. You can just upload an image using Wordpress media library if you wish.', 'jpibfi' ),
		)
	);

	add_settings_field(
		'pin_it_button_position',
		__( '"Pin it" button position', 'jpibfi' ),
		'jpibfi_pin_it_button_position_callback',
		'jpibfi_visual_options',
		'visual_options_section',
		array(
			__( 'Where the "Pin it" button should appear on the image.', 'jpibfi' ),
		)
	);

	add_settings_field(
		'pin_it_button_margins',
		__( '"Pin it" button margins', 'jpibfi' ),
		'jpibfi_pin_it_button_margins_callback',
		'jpibfi_visual_options',
		'visual_options_section',
		array(
			sprintf( __( 'Margins are used to adjust the position of the "Pin it" button, but not all margins are used on all button positions. Here is an example. If you\'re using the "%s" position, the button\'s position will be affected only by top and left margins. Bottom and right margins affect "%s" position, etc. The "%s" position does not use any margins at all.', 'jpibfi' ),
				__( 'Top left', 'jpibfi' ),
				__( 'Bottom right', 'jpibfi' ),
				__( 'Middle', 'jpibfi' )
			),
		)
	);

	add_settings_field(
		'retina_friendly',
		__( 'Retina friendly', 'jpibfi' ),
		'jpibfi_retina_friendly_callback',
		'jpibfi_visual_options',
		'visual_options_section',
		array(
			__( 'Please note that checking this option will result in rendering the "Pin it" button half of its normal size (if you use a 80x60 image, the button will be 40x30). When uploading a custom "Pin it" button (the default one is too small), please make sure both width and height are even numbers (i.e. divisible by two) when using this option.', 'jpibfi' ),
		)
	);

	register_setting(
		'jpibfi_visual_options',
		'jpibfi_visual_options',
		'jpibfi_sanitize_visual_options'
	);

}

add_action( 'admin_init', 'jpibfi_initialize_visual_options' );

function jpibfi_visual_options_callback() {
	echo '<p>' . __('How it should look like', 'jpibfi') . '</p>';
}

function jpibfi_mode_option_callback( $args ) {
	$options = jpibfi_get_visual_options();
	$mode = $options[ 'mode' ];

	?>

	<select id="mode" name="jpibfi_visual_options[mode]">
		<option value="static" <?php selected ( "static", $mode ); ?>><?php _e( 'Static', 'jpibfi' ); ?></option>
		<option value="dynamic" <?php selected ( "dynamic", $mode ); ?>><?php _e( 'Dynamic', 'jpibfi' ); ?></option>
	</select>

	<?php
	echo jpibfi_create_description( $args[0] );
}

function jpibfi_description_option_callback( $args ) {
	$options = jpibfi_get_visual_options();


	$description_option = $options[ 'description_option' ];
	?>

	<select id="description_option" name="jpibfi_visual_options[description_option]">
		<option value="1" <?php selected ( "1", $description_option ); ?>><?php _e( 'Page title', 'jpibfi' ); ?></option>
		<option value="2" <?php selected ( "2", $description_option ); ?>><?php _e( 'Page description', 'jpibfi' ); ?></option>
		<option value="3" <?php selected ( "3", $description_option ); ?>><?php _e( 'Picture title or (if title not available) alt attribute', 'jpibfi' ); ?></option>
		<option value="4" <?php selected ( "4", $description_option ); ?>><?php _e( 'Site title (Settings->General)', 'jpibfi' ); ?></option>
		<option value="5" <?php selected ( "5", $description_option ); ?>><?php _e( 'Image description', 'jpibfi' ); ?></option>
	</select>

	<?php
	echo jpibfi_create_description( $args[0] );
}

function jpibfi_use_post_url_callback( $args ) {

	$options = jpibfi_get_visual_options();
	$use_post_url = jpibfi_exists_and_equal_to( $options, 'use_post_url', '1' );

	echo '<input type="checkbox" id="use_post_url" name="jpibfi_visual_options[use_post_url]" value="1" ' . checked( true, $use_post_url, false ) . '>';
	echo '<label for="use_post_url">' . __( 'Always link to individual post page', 'jpibfi' ) . '</label>';

	echo jpibfi_create_description( $args[0] );
}

function jpibfi_transparency_value_callback( $args ) {
	$options = jpibfi_get_visual_options();

	$transparency_value = $options[ 'transparency_value' ];

	echo '<label for="transparency_value">' . sprintf ( __('Choose transparency (between %.02f and %.02f)', 'jpibfi'), '0.00', '1.00' ) . '</label><br/>';
	echo '<input type="number" min="0" max="1" step="0.01" id="transparency_value" name="jpibfi_visual_options[transparency_value]"' .
			'value="' . $transparency_value . '" class="small-text" >';
	echo jpibfi_create_description( $args[0] );
	echo jpibfi_create_errors( 'transparency_value' );
}

function jpibfi_custom_pin_it_button_callback( $args ) {
	$options = jpibfi_get_visual_options();

	$use_custom_image = jpibfi_exists_and_equal_to( $options, 'use_custom_image', '1' );
	$custom_image_url = $options[ 'custom_image_url' ];
	$custom_image_height = $options[ 'custom_image_height' ];
	$custom_image_width = $options[ 'custom_image_width' ];

	?>
	<p>
		<input type="checkbox" id="use_custom_image" name="jpibfi_visual_options[use_custom_image]" value="1" <?php checked( true, $use_custom_image ); ?> >
		<label class="chbox-label" for="use_custom_image"><?php _e( 'Use custom image', 'jpibfi' ); ?></label>
	</p>

	<button id=upload-image><?php _e( 'Upload an image using media library','jpibfi' ); ?></button><br />

	<p>
		<label for="custom_image_url"><?php _e( 'URL address of the image', 'jpibfi' ); ?></label>
		<input type="url" id="custom_image_url" name="jpibfi_visual_options[custom_image_url]" value="<?php echo $custom_image_url; ?>">
	</p>

	<p>
		<label for="custom_image_height"><?php _e( 'Height', 'jpibfi' ); ?></label>
		<input type="number" min="0" step="1" id="custom_image_height" name="jpibfi_visual_options[custom_image_height]" value="<?php echo $custom_image_height; ?>"
					 class="small-text" /> px
		<?php echo jpibfi_create_errors( 'custom_image_height' ); ?>
	</p>

	<p>
		<label for="custom_image_width"><?php _e( 'Width', 'jpibfi' ); ?></label>
		<input type="number" min="0" step="1" id="custom_image_width" name="jpibfi_visual_options[custom_image_width]" value="<?php echo $custom_image_width; ?>"
					 class="small-text"  /> px
		<?php echo jpibfi_create_errors( 'custom_image_width' ); ?>
	</p>

	<p>
		<b><?php _e( 'Custom Pin It button preview', 'jpibfi' ); ?></b><br/>
		<span id="custom_button_preview" style="width: <?php echo $custom_image_width; ?>px; height: <?php echo $custom_image_height; ?>px; background-image: url('<?php echo $custom_image_url; ?>');">
			Preview
		</span><br/>
		<button id="refresh_custom_button_preview"><?php _e( 'Refresh preview', 'jpibfi' ); ?></button>
	</p>

	<?php

	echo jpibfi_create_description( $args[0] );
}

function jpibfi_pin_it_button_position_callback( $args ) {
	$options = jpibfi_get_visual_options();

	$jpibfi_button_dropdown = array(
		__( 'Top left', 'jpibfi' ),
		__( 'Top right', 'jpibfi' ),
		__( 'Bottom left', 'jpibfi' ),
		__( 'Bottom right', 'jpibfi' ),
		__( 'Middle', 'jpibfi' )
	);

	$button_position = $options[ 'button_position' ];

	?>

	<select name="jpibfi_visual_options[button_position]" id="button_position">
		<?php for( $i = 0; $i < count( $jpibfi_button_dropdown ); $i++) { ?>
			<option value="<?php echo $i; ?>" <?php selected( $i, $button_position ); ?>><?php echo $jpibfi_button_dropdown[ $i ]; ?></option>
		<?php } ?>
	</select><br/>

	<?php
	echo jpibfi_create_description( $args[0] );
}

function jpibfi_pin_it_button_margins_callback( $args ) {
	$options = jpibfi_get_visual_options();
	?>
	<label for="button_margin_top"><?php _e('Top', 'jpibfi'); ?></label>
	<input type="number" min="-1000" max="1000" step="1" id="button_margin_top" name="jpibfi_visual_options[button_margin_top]" value="<?php echo $options[ 'button_margin_top' ]; ?>" class="small-text" >px<br/>
	<label for="button_margin_bottom"><?php _e('Bottom', 'jpibfi'); ?></label>
	<input type="number" min="-1000" max="1000" step="1" id="button_margin_bottom" name="jpibfi_visual_options[button_margin_bottom]" value="<?php echo $options[ 'button_margin_bottom' ]; ?>" class="small-text" >px<br/>
	<label for="button_margin_left"><?php _e('Left', 'jpibfi'); ?></label>
	<input type="number" min="-1000" max="1000" step="1" id="button_margin_left" name="jpibfi_visual_options[button_margin_left]" value="<?php echo $options[ 'button_margin_left' ]; ?>" class="small-text" >px<br/>
	<label for="button_margin_right"><?php _e('Right', 'jpibfi'); ?></label>
	<input type="number" min="-1000" max="1000" step="1" id="button_margin_right" name="jpibfi_visual_options[button_margin_right]" value="<?php echo $options[ 'button_margin_right' ]; ?>" class="small-text" >px<br/>

	<?php
	echo jpibfi_create_description( $args[0] );
}

function jpibfi_retina_friendly_callback( $args ) {

	$options = jpibfi_get_visual_options();
	$retina_friendly = jpibfi_exists_and_equal_to( $options, 'retina_friendly', '1' );

	echo '<input type="checkbox" id="retina_friendly" name="jpibfi_visual_options[retina_friendly]" value="1" ' . checked( true, $retina_friendly, false ) . '>';
	echo '<label for="retina_friendly">' . __( 'Optimize for high pixel density displays', 'jpibfi' ) . '</label>';

	echo jpibfi_create_description( $args[0] );
}

function jpibfi_sanitize_visual_options( $input ) {

	foreach( $input as $key => $value ) {

		switch($key) {
			case 'transparency_value':
				if ( !is_numeric( $input[ $key ] ) || ( $input[ $key ] < 0.0 ) || ( $input[ $key ] > 1.0 ) ) {

					add_settings_error(
						$key,
						esc_attr( 'settings_updated' ),
						sprintf( __('Transparency value must be a number between %.02d and %.02f.', 'jpibfi'), '0.00', '1.00' )
					);
				}
				break;
			case 'custom_image_height':
			case 'custom_image_width':
				$name = "";
				if ( 'custom_image_height' == $key )
					$name = __('Custom image height', 'jpibfi' );
				else if ( 'custom_image_width' == $key )
					$name = __('Custom image width', 'jpibfi' );

				if ( '' != $value && ( !is_numeric( $value ) || $value < 0 ) ) {
					add_settings_error(
						$key,
						esc_attr( 'settings_updated' ),
						$name . ' - ' . sprintf ( __('value must be a number greater or equal to %d.', 'jpibfi'), '0' )
					);
				}
				break;
		}
	}

	$errors = get_settings_errors();

	if ( count( $errors ) > 0 ) {

		update_option( JPIBFI_VISUAL_OPTIONS . '_errors', $input );
		return get_option( JPIBFI_VISUAL_OPTIONS );

	} else {

		delete_option( JPIBFI_VISUAL_OPTIONS . '_errors' );
		return $input;

	}
}

function jpibfi_get_visual_options() {
	return jpibfi_get_options( JPIBFI_VISUAL_OPTIONS );
}

/*
 *
 * ADVANCED OPTIONS
 *
 */
function jpibfi_default_advanced_options() {
	$defaults = array(
		'debug'      => '0'
	);

	return $defaults;
}

/*
 * Defines selection options section and adds all required fields
 */
function jpibfi_initialize_advanced_options() {

	// First, we register a section.
	add_settings_section(
		'advanced_options_section',
		__( 'Advanced Settings', 'jpibfi' ),
		'jpibfi_advanced_options_callback',
		'jpibfi_advanced_options'
	);

	//lThen add all necessary fields to the section
	add_settings_field(
		'debug',
		__( 'Debug', 'jpibfi' ),
		'jpibfi_debug_callback',
		'jpibfi_advanced_options',
		'advanced_options_section',
		array(
			__( 'Use debug mode only if you are experiencing some issues with the plugin and you are reporting them to the developer of the plugin', 'jpibfi' ),
		)
	);

	register_setting(
		'jpibfi_advanced_options',
		'jpibfi_advanced_options' //no sanitization needed for now
	);
}

add_action( 'admin_init', 'jpibfi_initialize_advanced_options' );

function jpibfi_advanced_options_callback() {
	echo '<p>' . __('Advanced settings', 'jpibfi') . '</p>';
}

function jpibfi_get_advanced_options() {
	return jpibfi_get_options( JPIBFI_ADVANCED_OPTIONS );
}

function jpibfi_debug_callback( $args ){
	$options = jpibfi_get_advanced_options();
	$debug = jpibfi_exists_and_equal_to( $options, 'debug', '1' );

	echo '<input type="checkbox" id="debug" name="jpibfi_advanced_options[debug]" value="1" ' . checked( "1", $debug, false ) . '>';
	echo '<label for="debug">' . __( 'Enable debug mode', 'jpibfi' ) . '</label>';

	echo jpibfi_create_description( $args[0] );
}


/*
 *
 * UTILITIES
 *
 */

//function gets the id of the image by searching for class with wp-image- prefix, otherwise returns empty string
function jpibfi_get_post_id_from_image_classes( $class_attribute ) {
	$classes = preg_split( '/\s+/', $class_attribute, -1, PREG_SPLIT_NO_EMPTY );
	$prefix = 'wp-image-';

	for ($i = 0; $i < count( $classes ); $i++) {

	if ( $prefix === substr( $classes[ $i ], 0, strlen( $prefix ) ))
			return str_replace( $prefix, '',  $classes[ $i ] );

	}

	return '';
}

/*
 * Get description for a given image
 */
function jpibfi_get_image_description( $id, $src ) {

	$error_result = jpibfi_get_image_description_error_array();

	$result = is_numeric( $id ) ? jpibfi_get_image_description_by_id( $id ) : $error_result;

	//if description based on id wasn't found
	if ( $error_result[ 'result' ] === $result[ 'result' ] ) {
		$id = jpibfi_fjarrett_get_attachment_id_by_url( $src );
		$result = is_numeric ( $id ) ? jpibfi_get_image_description_by_id( $id ) : $error_result;
	}

	return $result[ 'description' ];
}

/*
 * Function searches for image based on $id and returns an array based on results
 */
function jpibfi_get_image_description_by_id( $id ){

	$attachment = get_post( $id );

	if ( null == $attachment ) {
		$result = jpibfi_get_image_description_error_array();
	} else {
		$result = array(
			'description' => $attachment->post_content,
			'result' => '0'
		);
	}

	return $result;
}

//returns error image description array
function jpibfi_get_image_description_error_array() {
	return array(
		'description' => '',
		'result' => '1'
	);
}

/*
 * Function returns properly formatted description of a setting
 */
function jpibfi_create_description( $desc ) {
	return '<p class="description">' . $desc . '</p>';
}

function jpibfi_create_errors( $setting_name ) {
	$error = "";

	$errors_array = get_settings_errors( $setting_name );

	if ( count ( $errors_array ) > 0 ) {
		$error .= '<div class="jpibfi-error-message">';
		for( $i = 0; $i < count( $errors_array ); $i++ ){
			$error .= $errors_array[ $i ]['message'] . '</br>';
		}
		$error .= '</div>';
	}

	return $error;
}

/*
 * writes or returns chosen text depending whether the given condition is true or false
 */
function jpibfi_conditional( $condition, $true_value = "", $false_value = "", $echo = true) {
	if ( $condition ) {
		if ( $echo )
			echo $true_value;
		return $true_value;
	}
	if ( $echo )
		echo $false_value;
	return $false_value;
}

function jpibfi_exists_and_equal_to( $array , $name, $expected_value = "1" ) {
	return isset( $array[ $name ] ) && $array[ $name ] == $expected_value;
}

/*
 * If there are errors, let's return the array with 'unsaved' data
 * 'Saved' settings otherwise
 */
function jpibfi_get_options( $name ) {
	//cumbersome, but works in WP 3.3
	$options = get_option( $name . '_errors' );

	return false == $options ? get_option( $name ) : $options;

	//the code below is a much better error management solution, but doesn't work in WP 3.3
//	if ( JPIBFI_SELECTION_OPTIONS == $name )
//		$settings_list = jpibfi_default_selection_options();
//	else
//		$settings_list = jpibfi_default_visual_options();
//
//	$errors = false;
//
//	foreach( $settings_list as $key => $value) {
//		if ( count( get_settings_errors( $key ) ) > 0 ) {
//			$errors = true;
//			break;
//		}
//	}
//	return $errors ? get_option( $name . '_errors' ) : get_option( $name );
}

/*
 * Function makes sure that option has all needed fields by checking with defaults
 */
function jpibfi_update_option_fields( $option, $default_option, $option_name ) {

	$new_option = array();

	if ( false == $option )
		$option = array();

	foreach ($default_option as $key => $value ) {
		if ( false == array_key_exists( $key, $option ) )
			$new_option [ $key ] = $value;
		else
			$new_option [ $key ] = $option [ $key ];
	}

	update_option( $option_name, $new_option );

}

function jpibfi_is_string_css_class_name( $class_name ) {
	return 1 == preg_match( "/^-?[_a-zA-Z]+[_a-zA-Z0-9-]*$/", $class_name );
}

//checks if given string contains class names separated by semicolons or is empty
function jpibfi_contains_css_class_names_or_empty( $str ) {
	if ( 0 == strlen( $str ) )
		return true;

	$names = explode( ';', $str );
	$only_class_names = true;

	for( $i = 0; $i < count( $names ) && $only_class_names; $i++ )
		$only_class_names = jpibfi_is_string_css_class_name( $names [ $i ] );

	return $only_class_names;
}

/**
 * Function copied from http://frankiejarrett.com/get-an-attachment-id-by-url-in-wordpress/
 * Return an ID of an attachment by searching the database with the file URL.
 *
 * First checks to see if the $url is pointing to a file that exists in
 * the wp-content directory. If so, then we search the database for a
 * partial match consisting of the remaining path AFTER the wp-content
 * directory. Finally, if a match is found the attachment ID will be
 * returned.
 *
 * @return {int} $attachment
 */
function jpibfi_fjarrett_get_attachment_id_by_url( $url ) {

	// Split the $url into two parts with the wp-content directory as the separator.
	$parse_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );

	// Get the host of the current site and the host of the $url, ignoring www.
	$this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
	$file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );

	// Return nothing if there aren't any $url parts or if the current host and $url host do not match.
	if ( ! isset( $parse_url[1] ) || empty( $parse_url[1] ) || ( $this_host != $file_host ) )
		return;

	// Now we're going to quickly search the DB for any attachment GUID with a partial path match.
	// Example: /uploads/2013/05/test-image.jpg
	global $wpdb;

	$prefix     = $wpdb->prefix;
	$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM " . $prefix . "posts WHERE guid RLIKE %s;", $parse_url[1] ) );

	// Returns null if no attachment is found.
	return  $attachment ? $attachment[0] : null;
}