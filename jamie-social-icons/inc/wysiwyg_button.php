<?php if($options['post'] != true || $options['page'] !=true) {
add_action('init', 'add_jamiesocial_button');
}
    function add_jamiesocial_button() {  
       if ( current_user_can('edit_posts') && current_user_can('edit_pages')  )  
       {  
         add_filter('mce_external_plugins', 'add_jamiesocial_plugin');  
         add_filter('mce_buttons', 'register_jamiesocial_button');  
	   
       } 
    }   
	    function register_jamiesocial_button($buttons) {  
       array_push($buttons, "jamiesocial");  
       return $buttons;  
    }  
	    function add_jamiesocial_plugin($plugin_array) {  
       $plugin_array['jamiesocial'] = WP_PLUGIN_URL.'/jamie-social-icons/inc/editor_jamie.js';  
       return $plugin_array;  
    }
	?>