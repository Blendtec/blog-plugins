<?php
/*
Plugin Name: Mouseflow 3.1
Plugin URI: http://mouseflow.com
Description: Integrate Mouseflow analytics on your website. Login to your <a href="admin.php?page=mouseflow-dashboard">Mouseflow dashboard</a>. Create a free account <a href="http://mouseflow.com/sign-up/" target="_blank">here</a>, and paste in your tracking code <a href="admin.php?page=mouseflow-for-wordpress">here</a>.
Author: Mouseflow
Version: 3.1
Author URI: http://mouseflow.com
*/

wp_register_style('mouseflow_wp.css', plugins_url('mouseflow-for-wordpress/mouseflow_wp.css'));

function mouseflow_addOptions()
{
	add_option('mouseflow_script');
}
register_activation_hook(__FILE__, 'mouseflow_addOptions');

function mouseflow_settings_link($links) { 
  $settings_link = '<a href="admin.php?page=mouseflow-dashboard">Settings</a>';
  array_unshift($links, $settings_link);
  return $links;
}

$plugin = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$plugin", 'mouseflow_settings_link' );

function mouseflow_menu() {
  add_menu_page('Mouseflow', 'Mouseflow', 'administrator', 'mouseflow-dashboard', 'mouseflow_options3', plugins_url('mouseflow-for-wordpress/mf_logo_wp.png'));
  add_submenu_page('mouseflow-dashboard', 'Dashboard', 'Dashboard', 'administrator', 'mouseflow-dashboard', 'mouseflow_options3');
  add_submenu_page('mouseflow-dashboard', 'Your account', 'Your account', 'administrator', 'mouseflow-scriptpage', 'mouseflow_options2');
  add_submenu_page('mouseflow-dashboard', 'Tracking code', 'Tracking code', 'administrator', 'mouseflow-for-wordpress', 'mouseflow_options');
}

function mouseflow_options3() {
  wp_enqueue_style('mouseflow_wp.css');
echo '

<table class="intro">
	<tr>
		<td class="top" colspan="2"><img src="' .plugins_url('mouseflow-for-wordpress/mouseflow_logo.png', dirname(__FILE__) ). '" > 

	</tr>
	<tr>
		<td class="top2" colspan="2">
		<h2>Welcome to the Mouseflow dashboard</h2>
		Here you can access everything Mouseflow has to offer - directly from your Wordpress dashboard! Learn more about your visitors by viewing recordings of whole user sessions including mouse movements, clicks, scroll events and key stroke.';

if(get_option('mouseflow_script') == ''){
	echo '<h3>Get started</h3>
	In order to get started with the Mouseflow Wordpress-plugin you need to register an account on <a href="http://mouseflow.com/signup/" target="_blank" title="Mouseflow - live click tracking and websites analytics">mouseflow.com</a> (don&#39;t worry, it&#39;s free of charge). Once you have an account you need to install the <div title="To find your tracking code you must log in to Mouseflow.com and go to your list of websites. Click &#39;edit&#39; at the appropriate site and copy the code from the box in the bottom left corner." class="hover">Mouseflow tracking code</div> in Wordpress - simply go to your account and find your tracking code. Copy this into the box here: <a href="' .get_option('mouseflow-dashboard', 'admin.php?page=mouseflow-for-wordpress'). '">Insert tracking code</a>.';
}
echo '
	</td>
	</tr>
</table>
	
<table class="square">
	<tr>
		<td class="link"><a href="' .get_option('mouseflow-dashboard', 'admin.php?page=mouseflow-scriptpage'). '">
			<table class="intro2">
				<tr>
					<td><img style="vertical-align:middle" src="' .plugins_url('mouseflow-for-wordpress/account.png', dirname(__FILE__) ). '" >
					</td>
					<td><h3>Your Mouseflow-account</h3>
Here you can access your Mouseflow-account and get direct access to recordings, heatmaps, analytics and much more. If you don&#39;t already have an account you can create one here as well.
					</td>
				</tr>
			</table>
		</a>
		</td>

		<td class="link"><a href="' .get_option('mouseflow-dashboard', 'admin.php?page=mouseflow-for-wordpress'). '">
			<table class="intro2">
				<tr>';
	

if(get_option('mouseflow_script') == ''){
	echo '<td><img src="' .plugins_url('mouseflow-for-wordpress/gear_bad.png', dirname(__FILE__) ). '" >
					</td>
					<td><h3>Insert tracking code</h3>
		In order to start recording you need to input your <div title="To find your tracking code you must log in to Mouseflow.com and go to your list of websites. Click &#39;edit&#39; at the appropriate site and copy the code from the box in the bottom left corner." class="hover">tracking code</div> here, and it will automatically be inserted into every page of your Wordpress-site - it&#39;s really as easy as that.
					</td>';
}
else{
	echo '<td><img src="' .plugins_url('mouseflow-for-wordpress/gear_good.png', dirname(__FILE__) ). '" >
					</td>
					<td><h3>Tracking code installed</h3>
		Your tracking code is now installed. If you want to view, change or uninstall the code, click here. The installation is not complete until the first visitor is recorded on your site.
					</td>';
}
		
echo '</tr>
			</table>
		</a></td>

	</tr>
	<tr>
		<td class="link"><a href="http://mouseflow.zendesk.com" target="_blank">
			<table class="intro2">
				<tr>
					<td><img src="' .plugins_url('mouseflow-for-wordpress/support.png', dirname(__FILE__) ). '" >
					</td>
					<td><h3>Troubleshooting</h3>
		If you have questions or problems using Mouseflow, chances are you will find the answers in our extensive FAQs, walkthroughs, screencasts and other helpful information.
					</td>
				</tr>
			</table>
		</a></td>
		<td class="link"><a href="http://mouseflow.uservoice.com" target="_blank">
			<table class="intro2">
				<tr>
					<td><img src="' .plugins_url('mouseflow-for-wordpress/uservoice.png', dirname(__FILE__) ). '" >
					</td>
					<td><h3>What should we do next?</h3>
		We know we can always get better - but how? If you are missing a feature or have a suggestion that could improve our service, then please share your thoughts with us here.
					</td>
				</tr>
			</table>
		</a></td>
	</tr>
</table>

';

}
function mouseflow_options2() {
	 wp_enqueue_style('mouseflow_wp.css');
echo '
<table class="intro">
	<tr><td class="top2" colspan="2"><h2>Welcome to the Mouseflow dashboard</h2></td></tr>
</table>';
echo '<iframe src="http://account.mouseflow.com/sign-in" width="1100px" height="1800px"><br></iframe>
';
}

function mouseflow_options() {
	wp_enqueue_style('mouseflow_wp.css');
echo '
<table class="intro">
	<tr>
		<td class="top" colspan="2"><img src="' .plugins_url('mouseflow-for-wordpress/mouseflow_logo.png', dirname(__FILE__) ). '" > 

	</tr>
	<tr>
		<td class="top2" colspan="2">
		<h2>Mouseflow tracking code</h2>
<table class="install"><tr>';

if(get_option('mouseflow_script') == ''){
	echo '<td><img src="' .plugins_url('mouseflow-for-wordpress/gear_bad.png', dirname(__FILE__) ). '" >
					</td>
					<td><h3>Tracking code not installed</h3>
		Your Mouseflow tracking code is not yet installed. You can <a href="' .get_option('mouseflow-dashboard', 'admin.php?page=mouseflow-scriptpage'). '">find the tracking code on your Mouseflow-account</a>. If you don\'t yet have an account, you can easily <a href="https://mouseflow.com/sign-up/" target="_blank">create a Mouseflow-account for free</a>.
					</td>';
}
else{
	echo '<td><img src="' .plugins_url('mouseflow-for-wordpress/gear_good.png', dirname(__FILE__) ). '" >
					</td>
					<td><h3>Tracking code is installed</h3></td>';
}
echo '</tr></table>

<form method="post" action="options.php">';
wp_nonce_field('update-options'); 

if(get_option('mouseflow_script') == ''){
	echo "";
}
else{
echo '<h3>Your current Mouseflow tracking code:</h3><div class="code">';
	echo str_replace(">", "&gt;",str_replace("<", "&lt;", get_option('mouseflow_script')));
echo '</div>';
}
echo '

<h3>Insert tracking code (save empty field to delete)</h3>
<textarea name="mouseflow_script" style="width:800px;height:100px;"></textarea>

<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="mouseflow_script" />



<p class="submit">
                <input type="submit" name="update_message" value="';
echo  _e('Save Changes');
echo '" />
            </p>


</form>
</td>
	</tr>
</table>
';

}

add_action('admin_menu', 'mouseflow_menu');


function add_mouseflow_script()
{
	echo get_option('mouseflow_script');
}
add_action('wp_footer', 'add_mouseflow_script');

?>