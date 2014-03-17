<?php
/*
 * Plugin Name: AuthorSure
 * Plugin URI: http://www.authorsure.com
 * Description: Makes it easier to implement Google Authorship using rel=me, rel=author and rel=publisher links
 * Version: 2.1.1
 * Author: Russell Jamieson
 * Author URI: http://www.diywebmastery.com/about/
 * License: GPLv2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
define('AUTHORSURE_VERSION', '2.1.1');
define('AUTHORSURE', 'authorsure');
define('AUTHORSURE_FRIENDLY_NAME', 'AuthorSure');
define('AUTHORSURE_HOME', 'http://www.authorsure.com/');
define('AUTHORSURE_PLUGIN_URL', plugins_url(AUTHORSURE).'/');
define('AUTHORSURE_IMAGES_URL', AUTHORSURE_PLUGIN_URL.'images/');
define('AUTHORSURE_GOOGLEPLUS_URL', 'https://plus.google.com/');
require_once(dirname(__FILE__) . '/classes/plugin.php');