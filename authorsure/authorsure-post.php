<?php
add_action('init', array(AUTHORSURE_POST,'admin_init'));

class authorsure_post {
    
	static function admin_init() {
		$author_rel = AUTHORSURE::get_option('author_rel');
		if (('box'==$author_rel) || ('footnote'==$author_rel)) 
			add_action( 'do_meta_boxes', array( AUTHORSURE_POST, 'do_meta_boxes'), 20, 2 );
		add_action( 'save_post', array( AUTHORSURE_POST, 'save'));
	}

	static function do_meta_boxes( $post_type, $context) {
		$post_types=get_post_types();
		if ( in_array($post_type, $post_types ) && 'advanced' === $context ) {
			$vars = array( 'post_type' => $post_type);
			add_meta_box( 'authorsure-visibility', 'AuthorSure Settings', array( AUTHORSURE_POST, 'visibility_panel' ), $page, 'advanced', 'low' ,$vars);
			global $current_screen;
			if (method_exists($current_screen,'add_help_tab')) {
	    		$current_screen->add_help_tab( array(
			        'id'	=> 'authorsure_help_tab',
    			    'title'	=> __('AuthorSure Settings'),
        			'content'	=> __(
'<h3>AuthorSure Settings</h3><p>In the <b>AuthorSure Settings</b> section below you can choose whether to enable or disable AuthorSure links on this page. 
For example, you might want to disable the author links on contact, privacy statement and terms and conditions pages, and on posts with recipe microformats.</p>')) );
			}
		}
	}
		
	static function visibility_panel($post,$metabox) {
		global $post;
		$post_type = $metabox['args']['post_type'];
		switch ($post_type) {
			case 'post' : $showtime = false; break; //option is always to hide on posts	as it is present by default
			case 'page' : $showtime = AUTHORSURE::get_option('hide_box_on_pages') ; break;//option on page depends on default
			default: $showtime = true; //option is always to show on custom post types as it is absent by default
		}
		$key = $showtime ? AUTHORSURE::get_show_author_box_key() : AUTHORSURE::get_hide_author_box_key();
		$toggle = get_post_meta($post->ID, $key, true);
		$author_box_toggle = $toggle?' checked="checked"':'';		
		$action = $showtime ? 'show' : 'hide'; 
		$label =  __($showtime ? 'enable author links on this page' : 'disable author links on this page');
		print <<< AUTHORSURE_VISIBILITY
<p class="meta-otions"><input type="hidden" name="authorsure_toggle_action" value="{$action}" />
<label><input class="valinp" type="checkbox" name="{$key}" id="{$key}" {$author_box_toggle} value="1" />&nbsp;{$label}</label></p>
AUTHORSURE_VISIBILITY;
    }
	
	static function save($post_id) {
		if (array_key_exists('authorsure_toggle_action', $_POST)) {
			$key = 'show'==$_POST['authorsure_toggle_action'] ? AUTHORSURE::get_show_author_box_key() : AUTHORSURE::get_hide_author_box_key();	
			$val = array_key_exists($key, $_POST) ? $_POST[$key] : false;
			update_post_meta( $post_id, $key, $val );
		}
		update_post_meta( $post_id, AUTHORSURE::get_include_css_key(), 
			strpos(get_post_field('post_content', $post_id),'[authorsure') !== FALSE);		
	}	

}
?>