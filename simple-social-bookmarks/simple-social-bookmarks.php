<?php
/*
Plugin Name: Artiss Social Bookmarks
Plugin URI: http://www.artiss.co.uk/social-bookmarks
Description: Display links to social bookmark sites
Version: 3.2.4
Author: David Artiss
Author URI: http://www.artiss.co.uk
*/

/**
* Artiss Social Bookmarks
*
* Main code - include various functions
*
* @package	Artiss-Social-Bookmarks
* @since	3.2
*/

define( 'artiss_social_bookmarks_version', '3.2.4' );

$functions_dir = WP_PLUGIN_DIR . '/simple-social-bookmarks/includes/';

include_once( $functions_dir . 'asb-shared-functions.php' );			        // Shared functions

// Include all the various functions

if ( is_admin() ) {

    include_once( $functions_dir . 'asb-admin-config.php' );		            // Assorted admin configuration changes

} else {

    include_once( $functions_dir . 'asb-define-bookmarks.php' );			    // Set up the bookmark definitions

	include_once( $functions_dir . 'asb-generate-bookmarks.php' );			    // Generate the bookmarks

	include_once( $functions_dir . 'asb-shortcodes.php' );			            // Shortcode definitions

	include_once( $functions_dir . 'asb-functions.php' );			            // Function calls

	include_once( $functions_dir . 'asb-deprecated.php' );			            // Deprecated functions

}
?>