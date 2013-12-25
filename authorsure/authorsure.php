<?php
/*
 * Plugin Name: AuthorSure
 * Plugin URI: http://www.authorsure.com
 * Description: Makes it easier to authenticate Authorship with Google using use rel=me, rel=author and rel=publisher links
 * Version: 1.9.1
 * Author: Russell Jamieson
 * Author URI: http://www.diywebmastery.com/about/
 * License: GPLv2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */
define('AUTHORSURE_VERSION', '1.9.1');
define('AUTHORSURE', 'authorsure');
define('AUTHORSURE_ADMIN', 'authorsure_admin');
define('AUTHORSURE_ARCHIVE', 'authorsure_archive');
define('AUTHORSURE_PROFILE', 'authorsure_profile');
define('AUTHORSURE_POST', 'authorsure_post');
define('AUTHORSURE_HOME', 'http://www.authorsure.com/');
define('AUTHORSURE_PRO', 'http://www.authorsure.com/');
define('AUTHORSURE_PLUGIN_URL', plugins_url(AUTHORSURE).'/');
define('AUTHORSURE_IMAGES_URL', AUTHORSURE_PLUGIN_URL.'images/');
define('AUTHORSURE_GOOGLEPLUS_URL', 'https://plus.google.com/');


class authorsure {

    private static $extended_bio_metakey = 'authorsure_extended_bio';
    private static $hide_author_box_metakey = 'authorsure_hide_author_box'; //used for exceptions where the default is to show the author box
    private static $show_author_box_metakey = 'authorsure_show_author_box'; //used for exceptions where the default is to hide the author box
    private static $show_author_metakey = 'authorsure_show_author_on_list';
    private static $include_css_metakey = 'authorsure_include_css';
 	private static $authorsure_count = 0;
 	    
	private static $defaults = array(
 		'author_rel' => 'byline',  //menu, byline, footnote, box
 		'publisher_rel' => '',  //Google Plus URL of publisher	    
 	    'footnote_last_updated_by' => 'Last updated by',
 	    'footnote_last_updated_at' => 'at',
 		'footnote_show_updated_date' => true,
 	    'box_about' => 'About', 		
 	    'box_gravatar_size' => 60,
 	    'box_nofollow_links' => false,
 	    'box_show_profiles' => false,
 	    'hide_box_on_pages' => false,
		'hide_box_on_front_page' => false,
 	    'menu_about_page' => '',
 	    'menu_primary_author' => '', 	  
		'home_author' => '',
 	    'author_page_hook' => 'loop_start',
 	    'author_page_hook_index' => '1',
		'author_page_filter_bio' => false,   
 	    'author_show_title' => true,
 	    'author_show_avatar' => false,
 	    'author_about' => 'About', 		
 	    'author_bio' => 'summary',
 	    'author_bio_nofollow_links' => false,
	    'author_find_more' => 'Find more about me on:',
		'author_profiles_image_size' => 16,	
		'author_profiles_no_labels' => false,	 
	    'author_archive_heading'=> 'Here are my most recent posts',
		'archive_link' => 'publisher', //publisher, top or bottom
		'archive_intro_enabled' => false,
		'archive_last_updated_by' => 'Last updated by',
		'archive_author_id' => 0,
		'archive_hook' => false,
		'minimum_role_for_authorship' => 'contributor',
		'minimum_role_for_bio_links' => 'contributor',
		'minimum_role_for_box_links' => 'contributor'
		
    );    
	private static $pro_defaults = array(
	    'facebook' => array('Facebook','Facebook URL'), 
	    'flickr' => array('Flickr', 'Flickr Profile URL'),
	    'googleplus'=> array('Google Plus', 'Google Plus Profile URL'), 
	    'linkedin' => array('LinkedIn', 'Linked In Profile URL'), 
	    'pinterest' => array('Pinterest', 'Pinterest Profile URL'), 
	    'skype' => array('Skype', 'Skype Name'),
	    'twitter'=> array('Twitter','Twitter Name'),
	    'youtube'=> array('YouTube', 'YouTube Channel URL')
    );    

    private static $options = array();   
    private static $pro_options = array();    
    private static $term_options = array();    
    private static $author = false;  
    private static $intro = '';
    
    private static function get_defaults() {
		return self::$defaults;
    }

    private static function get_pro_defaults() {
		return self::$pro_defaults;
    }

	public static function get_pro_options ($cache = true) {
		if ($cache && (count(self::$pro_options) > 0)) return self::$pro_options;
		$defaults = self::get_pro_defaults();
		self::$pro_options = apply_filters('authorsure_more_contactmethods',self::get_pro_defaults()); 
   		return self::$pro_options;
	}

	public static function get_options ($cache = true) {
		if ($cache && (count(self::$options) > 0)) return self::$options;
		$defaults = self::get_defaults();
		$options = get_option('authorsure_options');
		self::$options = empty($options) ? $defaults : wp_parse_args($options, $defaults); 
   		return self::$options;
	}

	public static function get_term_options ($cache = true) {
		if ($cache && (count(self::$term_options) > 0)) return self::$term_options;
		$options = get_option('authorsure_term_options');
		self::$term_options = empty($options) ? array() : $options; 
   		return self::$term_options;
	}

	public static function get_option($option_name) {
	    $options = self::get_options();
	    if ($option_name && $options && array_key_exists($option_name,$options)) 
	    	return $options[$option_name];
	    else
	        return false;
	}
	
	public static function get_archive_hook() {
	    $archive_link = self::get_option('archive_link');
	    switch ($archive_link) {
	    	case 'top': return 'loop_start';
			case 'bottom': return 'loop_end';
			default: return '';
	    } 
	}

	public static function get_author_page_hook() {
		$hook = self::get_option('author_page_hook');
		if (empty($hook)) $hook = self::$defaults['author_page_hook']; 
		return $hook;
	}

	public static function get_author_page_hook_index() {
		$hook_index = self::get_option('author_page_hook_index');
		if (empty($hook_index)) $hook_index = self::$defaults['author_page_hook_index']; 
		return $hook_index;
	}

	public static function get_show_author_key() {
		    return self::$show_author_metakey;
	}

	public static function get_extended_bio_key() {
		    return self::$extended_bio_metakey;
	}

	public static function get_hide_author_box_key() {
		    return self::$hide_author_box_metakey;
	}

	public static function get_show_author_box_key() {
		    return self::$show_author_box_metakey;
	}
	
	public static function get_include_css_key() {
		    return self::$include_css_metakey;
	}
	
	public static function sanitize_publisher($url, $is_url = false) {
		if (! is_null(parse_url($url, PHP_URL_SCHEME)))
			$url = parse_url($url, PHP_URL_PATH);
		if (strpos($url,'/') !== FALSE) {
			$parts = explode('/',$url);
			$id = $parts[0] ? $parts[0] : $parts[1];
		} else {
			$id = $url;
		}
		return $is_url ? (AUTHORSURE_GOOGLEPLUS_URL . $id . '/') : $id;
	}	
	
	public static function get_publisher() {
		return self::get_option('publisher_rel');	
	}
	
	public static function get_archive_option($term_id, $key) {
		if (!$term_id || !$key) return false;
		$options = self::get_term_options();
		$arc_options= (is_array($options) && array_key_exists($term_id, $options)) ? $options[$term_id] : array();
		return is_array($arc_options) && array_key_exists($key, $arc_options) ? $arc_options[$key] : false;
	}
	
	public static function save_options ($options) {
		$result = update_option('authorsure_options',$options);
		self::get_options(false); //update cache
		return $result;
	}

	public static function save_archive_option ($term_id, $values) {
		if (! $term_id || ! $values || !is_array($values) || !is_numeric($term_id)) return false;
	    $term_options = self::get_term_options(false); //get the option to update	    
		$term_options[$term_id] = $values; //update it
		$result = update_option('authorsure_term_options', $term_options); //save to the database 
		self::get_term_options(false); //update cache
		return $result;
	}

	private static function allow_img($allowedtags) {
		if ( !array_key_exists('img', $allowedtags) 
		|| (array_key_exists('img',$allowedtags) && !array_key_exists('src', $allowedtags['img']))) {
			$allowedtags['img']['src'] = array ();
			$allowedtags['img']['title'] = array ();
			$allowedtags['img']['alt'] = array ();
			$allowedtags['img']['height'] = array ();			
			$allowedtags['img']['width'] = array ();	
		}
		return $allowedtags;
	}
	
	private static function allow_arel($allowedtags) {
		if ( !array_key_exists('a', $allowedtags) 
		|| (array_key_exists('a',$allowedtags) && !array_key_exists('rel', $allowedtags['a'])))
			$allowedtags['a']['rel'] = array ();
		return $allowedtags;
	}	
	
	public static function wordpress_allow_img() {
		global $allowedtags;
		$allowedtags = self::allow_img($allowedtags);
	}

	public static function wordpress_allow_arel() {
		global $allowedtags;
		$allowedtags = self::allow_arel($allowedtags);
	}

	public function genesis_allow_arel($allowedtags) {
		return self::allow_arel($allowedtags);
	}	

	public static function genesis_allow_img($allowedtags) {
		return self::allow_img($allowedtags);
	}

	public static function preserve_bio_links($user, $role) {
		$cap = 'edit_posts';
		switch ($role) {
			case 'administrator' : $cap = 'manage_options'; break;	
			case 'editor' : $cap = 'edit_others_posts'; break;	
			case 'author' : $cap = 'publish_posts'; break;	
		}	
		return user_can($user, $cap);
	}

	public static function is_author($user) {
		$cap = 'edit_posts';
		switch (self::get_option('minimum_role_for_authorship')) {
			case 'administrator' : $cap = 'manage_options'; break;	
			case 'editor' : $cap = 'edit_others_posts'; break;	
			case 'author' : $cap = 'publish_posts'; break;	
		}	
		return user_can($user, $cap);
	}

	public static function get_icon($profile, $label, $size ) {
		return sprintf('<img src="%1$s" alt="%2$s" />%3$s',
			AUTHORSURE_PLUGIN_URL.'images/'.$size.'px/'.$profile.'.png', $profile, $label);
	}

	public static function list_authors() {
		$s='';
		$authors = get_users(array('who' => 'authors', orderby => 'display_name'));
		foreach ($authors as $author) {
			if (get_user_option(self::get_show_author_key(), $author->ID))		
				$s .= self::get_box($author);
		}
		return $s;
	}
	
	public static function add_contactmethods_profile( $contactmethods) {
		return self::add_contactmethods( $contactmethods, 1, 16);
	}
	
	public static function add_contactmethods_nolabels( $contactmethods) {
		return self::add_contactmethods( $contactmethods, -1);
	}	
	
	public static function add_contactmethods( $contactmethods, $label_index=0, $size=0) {
		if ($size==0) $size = self::get_option('author_profiles_image_size');
		$profiles = self::get_pro_options();
		if (is_array($profiles)) 
			foreach ($profiles as $profile => $labels) 
				//if (!array_key_exists($profile,$contactmethods)) 
					$contactmethods[$profile] = self::get_icon($profile, $label_index<0 ? '' : ('&nbsp;'.$labels[$label_index]),$size);
		return $contactmethods;
	}

	public static function get_blog_author_link($id) {
		return '<a rel="me" href="'. get_author_posts_url($id).'">'.get_bloginfo().'</a>';
	}
	
	private static function get_author_link($id) {
		return '<a rel="author" href="'. get_author_posts_url($id).'" class="authorsure-author-link">'.get_the_author_meta('display_name', $id ).'</a>';
	}

	private static function get_avatar($id) {
		return get_avatar( get_the_author_meta('email', $id), self::get_option('box_gravatar_size') );
	}

	private static function about_author($id) {
		return sprintf( '<h4>%1$s %2$s</h4>', self::get_option('box_about'), self::get_author_link($id));
	}

	private static function get_title($id) {
		if  (self::get_option('author_show_title')) {
			$author_name = get_the_author_meta('display_name',$id);
			if ($prefix = self::get_option('author_about'))
				$title = $prefix . ' ' .  $author_name;
			else
				$title = $author_name;
			return sprintf( '<h2 class="authorsure-author-title">%1$s</h2>',$title);				
		} else {
			return '';
		}
	}

	private static function get_bio($id) {
	    $nofollow = self::get_option('author_bio_nofollow_links'); //setting for author page
	    $strip = ! self::preserve_bio_links($id, self::get_option('minimum_role_for_bio_links'));
		switch (authorsure::get_option('author_bio')) {
			case 'summary':  return self::get_summary_bio($id, $nofollow, $strip); break;
			case 'extended':  return self::get_extended_bio($id, $nofollow, $strip); break;
		}
		return '';
	}	
	
	private static function get_summary_bio($id, $nofollow, $strip ) {
		return self::get_filtered_bio($id,'description', $nofollow, $strip);
	}	

	private static function get_extended_bio($id, $nofollow, $strip) { //return extended bio if present else return standard bio
		if ($extended_bio =  self::get_filtered_bio($id, authorsure::$extended_bio_metakey, $nofollow, $strip))
			return $extended_bio;
		else
			return self::get_summary_bio($id, $nofollow, $strip) ;
	}
	
	private static function get_filtered_bio($id, $key, $nofollow, $strip) {
		return self::filter_links(wpautop( get_the_author_meta($key, $id) ), $nofollow, $strip);
	}	

	private static function get_box($author) {
	    $nofollow = self::get_option('box_nofollow_links'); //setting for author box
	    $strip = ! self::preserve_bio_links($author, self::get_option('minimum_role_for_box_links'));	
	    $profiles = self::get_option('box_show_profiles') ? self::get_profiles($author,true) : ''; //profile icons only 
		return sprintf('<div class="authorsure-author-box">%1$s%2$s%3$s%4$s</div><div class="clear"></div>',
			self::get_avatar($author->ID), self::about_author($author->ID), self::get_summary_bio($author->ID, $nofollow, $strip ), $profiles );
	}
	
	private static function get_footnote($id, $last_updated_time, $last_updated_date) {	
		$author = sprintf( '<span style="float:none" class="author vcard"><span class="fn">%1$s</span></span>', self::get_author_link($id) );
		$updated_at = self::get_option('footnote_show_updated_date') ?
			sprintf( ' %1$s <time itemprop="dateModified" datetime="%2$s">%3$s</time>',self::get_option('footnote_last_updated_at'),$last_updated_time, $last_updated_date) : '';
		return sprintf( '<p id="authorsure-last-updated" class="updated" itemscope itemtype="http://schema.org/WebPage" itemid="%1$s">%2$s %3$s%4$s.</p>', 
			get_permalink(), self::get_option('footnote_last_updated_by'), $author, $updated_at);
	}
	
	private static function skype_me ($name, $img, $nolabels) {
		wp_enqueue_script('skypeCheck', 'http://download.skype.com/share/skypebuttons/js/skypeCheck.js',array(),'v2.2',true);
		if ($pos = strpos($name,'/status')) $name = substr($name,0,$pos) ;
		if (($nolabels==false) && ($pos > 0)) {
			$img .= sprintf('&nbsp;<img src="http://mystatus.skype.com/bigclassic/%1$s" style="border: none;" width="100" height="24" alt="My status" />',$name);
		}		
		return sprintf('<li style="list-style-type: none;"><a href="skype:%1$s?call" title="Contact me on Skype">%2$s</a></li>', $name, $img);		
	}

	private static function contact_me_link($href, $channel, $desc, $icons_only) {
		return sprintf('<li style="list-style-type: none;"><a href="%1$s" %4$stitle="Follow me on %2$s">%3$s</a></li>',
				$href, ucwords($channel), $desc, $icons_only ? '' : 'rel="me" ');
	}
	
	private static function get_profiles($user, $icons_only = false) {
		$s='';
		$profiles = self::get_pro_options();
		$no_labels = $icons_only ? true : self::get_option('author_profiles_no_labels'); //fetch option if not set by param
		add_filter('user_contactmethods', array(AUTHORSURE,$no_labels ? 'add_contactmethods_nolabels' : 'add_contactmethods'),10,1);
		foreach (_wp_get_user_contactmethods( $user ) as $name => $desc) {
			if (array_key_exists($name,$profiles) && !empty($user->$name))
				if ('skype'==$name)
					$s .= self::skype_me($user->$name,$desc,$no_labels);
				elseif (('twitter'==$name) && is_null(parse_url($user->$name,PHP_URL_HOST))) //not a URL
					$s .= self::contact_me_link('http://twitter.com/'.$user->$name.'/', $name, $desc, $icons_only);
				else
					$s .= self::contact_me_link($user->$name, $name, $desc, $icons_only);
		}	
		if (empty($s))
			return '';
		elseif ($no_labels)
			return sprintf('<ul class="single-line"><span>%1$s</span>%2$s</ul>',self::get_option('author_find_more'), $s);
		else
			return sprintf('<p>%1$s</p><ul>%2$s</ul>',self::get_option('author_find_more'), $s);
	}
	

	private static function get_archive_term_id() {
		global $wp_query;
		if (is_archive() && ($term = $wp_query->get_queried_object()))
			return $term->term_id;
		else
			return false;
	}

	private static function get_archive_author() {
		if ($author = self::get_archive_option(self::get_archive_term_id(), 'author'))
			return $author; //return author explicitly chosen for this archive
		else
			return self::get_option('archive_author_id'); //return the default author for archives
	}
	
	private static function get_archive_intro() {
		if ($intro = self::get_archive_option(self::get_archive_term_id(),'intro'))
			return sprintf ('<div class="authorsure-archive-intro">%1$s</div>',stripslashes($intro));
		else
			return '';
	}
	
	//add a top section to archive page 
	static function show_archive_intro() {
		echo self::$intro;
	}
	
	static function get_last_update() {
		global $wp_query;
		$args = array_merge ($wp_query->query, array('posts_per_page' => 1, 'orderby' => 'modified', 'order' => 'DESC'));
		$posts = get_posts($args);	//merge with existing query but tweak to get the last modified post for this archive
		if( is_array($posts) && (count($posts) > 0)) {
			$t = get_post_modified_time('c',false, $posts[0]); //express as time 
			$d = apply_filters('get_the_modified_date', 
				get_post_modified_time(get_option('date_format'),false, $posts[0]), ''); //observe formats and date filters
			return array('datetime' => $t, 'date' => $d);
		}
		return false;
	}
		
	//add a section to archive page with rel=author to primary author
	static function show_archive_primary_author( ) {
		if (self::$authorsure_count == 0) { 
	    	self::$authorsure_count += 1;
	    	if($last_update = self::get_last_update())
	    		echo self::get_footnote(self::$author,$last_update['datetime'], $last_update['date']);	
		}
	}

	private static function show_author_profile($user) {
		if 	( self::is_author($user)) {
			if ($archive_heading = self::get_option('author_archive_heading'))
				$subtitle = sprintf('<p id="authorsure-posts-heading">%1$s</p>',$archive_heading);
			else 
				$subtitle = '';
			$title = self::get_title($id);
			if (self::get_option('author_show_avatar')) $title .= self::get_avatar($user->ID);

			echo sprintf('<div id="authorsure-author-profile">%1$s%2$s%3$s<div class="clear"></div>%4$s</div>',
				$title, self::get_bio($user->ID), self::get_profiles($user), $subtitle);
		}
	}

	//obtain user fron parameter or context
	private static function derive_user($attr) {
		if (is_array($attr) && array_key_exists('id',$attr)) {
			$id= $attr['id'];
		} else { //try looking in the post
			global $post;
			$id = ($post && property_exists($post,'post_author') && isset($post->post_author)) ? $post->post_author : 0;
		}
		if ($id > 0)
			$user_obj = new WP_User($id);
		else //try the URL
			$user_obj = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
		
		return ($user_obj && ($user_obj->ID > 0)) ? $user_obj : false	;
	}

	//shortcode for adding author profiles into a page
	public static function show_author_profiles($attr) {
		if ($user = self::derive_user($attr)) 
			return self::get_profiles($user) ;
		else
			return '';	
	}

	//shortcode for adding author box into a page
	public static function show_author_box($attr) {
		if ($user = self::derive_user($attr)) 
			return self::get_box($user) ;
		else
			return '';	
	}
	
	private static function get_home_author() {
		return self::get_option('home_author');
	}
	
	private static function get_home_author_rel() {
		$method = 'googleplus';
		$user_id = self::get_home_author();
		if ($user_id) {
			add_filter('user_contactmethods', array(AUTHORSURE,'add_contactmethods_nolabels'),10,1);
 			if (($user = new WP_User($user_id))
			&& $user->has_prop($method)
			&& ($url = $user->get($method))) 
				return $url;
		}
		return false;
	}

    private static function get_author_link_eligibility($post_author, $post_id, $post_type) {
		if (is_front_page() && self::get_option('hide_box_on_front_page')) return false;

		if (is_singular() && self::is_author($post_author) )
			switch ($post_type) {
				case 'post':  
					return ! get_post_meta($post_id, self::$hide_author_box_metakey, true);
				case 'page': { 
					if (self::get_option('hide_box_on_pages'))
						return get_post_meta($post_id, self::$show_author_box_metakey, true);
					else
						return ! get_post_meta($post_id, self::$hide_author_box_metakey, true);
				}
				default: 
					return get_post_meta($post_id, self::$show_author_box_metakey, true);
			}
		else
			return false; //not an individual page or not an author
    }

	//link (rel="author") the post/post to the author page in a post footnote 
	public static function append_post_author_footnote($content) {
		global $post;
		if (self::get_author_link_eligibility($post->post_author, $post->ID, $post->post_type) ) {
			$content .= self::get_footnote($post->post_author,get_post_modified_time('c'),get_the_modified_date());	
		}
		return $content;
	}

	//link (rel="author") the post/post to the author page in an author box at the foot of the post
	public static function append_post_author_box($content) {
		global $post;
		if (($user = self::derive_user(array('id' => $post->post_author))) 
		&& self::get_author_link_eligibility($post->post_author, $post->ID, $post->post_type) ) {
			$content .= self::get_box($user);	
		}		
		return $content;
	}

	//add primary author contact links to the about page
	public static function append_primary_author($content) {
		global $post;
		$about_page = self::get_option('menu_about_page');
		$primary = self::get_option('menu_primary_author');
		if ($primary && $about_page && is_page($about_page)) {
			$author = new WP_User($primary);
			$content .=  sprintf('<div id="authorsure-author-profile">%1$s</div>', self::get_profiles($author));
		}
		return $content;
	}

	//add a header to author page to link to Google (rel="me")
	public static function insert_author_bio() {
		global $post;
		if (is_author() && !is_feed()) {  //we're on an author page and it is not a feed
			$author_hook_index = self::get_author_page_hook_index(); 
	    	self::$authorsure_count += 1;
	    	if ($author_hook_index == self::$authorsure_count)  { //only add the bio once on the specified instance
				$curauth = (get_query_var('author_name')) ? get_user_by('slug', get_query_var('author_name')) : get_userdata(get_query_var('author'));
				self::show_author_profile($curauth);
			}
		}
	}

	//link the home page and possibly the archive pages to GooglePlus Page (rel="publisher")
	public static function add_publisher_rel() {
		if (($publisher = self::get_publisher())
		&& (is_front_page() || (is_archive() && ('publisher'==self::get_option('archive_link'))))) 
			printf ('<link rel="publisher" href="%1$s" />', AUTHORSURE_GOOGLEPLUS_URL.$publisher);
	}

	//link the home page with (rel="author")
	public static function add_author_rel() {
		if ($url = self::get_home_author_rel()) printf ('<link rel="author" href="%1$s" />', $url);
	}
	
	public static function add_head() {
		global $post;
		if (self::get_publisher()) add_action('wp_head', array(AUTHORSURE,'add_publisher_rel')) ; //add publisher link		
		if (is_front_page() && self::get_home_author()) add_action('wp_head', array(AUTHORSURE,'add_author_rel')) ; //add author link	
		$author_rel = self::get_option('author_rel');
		$about_page = self::get_option('menu_about_page');	
		if (('box'==$author_rel) 
		|| is_author() 
		|| ($about_page && is_page($about_page))
		|| ((is_page() || is_single()) && ($id = get_queried_object_id()) && get_post_meta($id,self::$include_css_metakey))) { 
			//include css for author boxes and on author page
    		wp_enqueue_style( AUTHORSURE, AUTHORSURE_PLUGIN_URL.'authorsure.css',array(),AUTHORSURE_VERSION);
		}
	}
	
    //** filter for use at the get_the_author_description hook **/
	public static function append_profiles($content, $user_id = false) {
		$args = array();
		if ($user_id && ($user_id > 0)) $args['id'] = $user_id;

		if 	(is_author() //only run on author pages
		&&	($user = self::derive_user($args))
		&&	self::is_author($user))
			$content = sprintf('%1$s<div id="authorsure-author-profile">%2$s</div>', $content, self::get_profiles($user));
		return $content;
	}	

	public static function add_single_author() {	
		if (is_singular()) {	
			//additions to posts and pages
			$author_rel = self::get_option('author_rel');
			switch($author_rel) {
				case 'menu': add_filter('the_content', array(AUTHORSURE,'append_primary_author')); break;
				case 'footnote': add_filter('the_content', array(AUTHORSURE,'append_post_author_footnote')); break;
				case 'box': add_filter('the_content', array(AUTHORSURE,'append_post_author_box')); break;
				default: 	
			}
			//additions to author pages 
			if ($author_rel != 'menu') 
				if (self::get_option('author_page_filter_bio')) 
					add_filter('get_the_author_description', array(AUTHORSURE,'append_profiles'),10,2); //append profiles to existing bio
				else
					add_action(self::get_author_page_hook(), array(AUTHORSURE,'insert_author_bio')); //add bio to author page
		}
	}

    static function add_archive_author () {
		if (is_archive() && ! is_author() ) {
			if (self::get_option('archive_intro_enabled') && (self::$intro = self::get_archive_intro())) 
				add_action('loop_start', array(AUTHORSURE, 'show_archive_intro'));
				
			if (($archive_hook = self::get_archive_hook())
			&& (self::$author = self::get_archive_author())) {  //get the archive author for later
				add_action($archive_hook, array(AUTHORSURE, 'show_archive_primary_author')); //add archive
			}
		}
    }  
         
    static function filter_links( $content, $nofollow = false, $strip = false) {
    	if ($nofollow || $strip)
			return preg_replace_callback( '/<a([^>]*)>(.*?)<\/a[^>]*>/is', 
				array( AUTHORSURE, $strip ? 'make_links_text_only' : 'nofollow_link' ), $content ) ;
		else
			return $content ;
    }		

    static function nofollow_link($matches) { //make link nofollow
		$attrs = shortcode_parse_atts( stripslashes ($matches[ 1 ]) );
		$atts='';
		foreach ( $attrs AS $key => $value ) {
			$key = strtolower($key);
			if ('rel' != $key) $atts .= sprintf('%1$s="%2$s" ', $key, $value);
		}
		$atts = substr( $atts, 0, -1 );
		return sprintf('<a rel="nofollow" %1$s>%2$s</a>', $atts, $matches[ 2 ]);
	}

    static function make_links_text_only($matches) { //return only text of link
		return $matches[ 2 ];
	}
	
	public static function add_author_bio() {
		//additions to author pages 
		$author_rel = self::get_option('author_rel');
		if ($author_rel != 'menu') 
			if (self::get_option('author_page_filter_bio')) 
				add_filter('get_the_author_description', array(AUTHORSURE,'append_profiles'),10,2); //append profiles to existing bio
			else
				add_action(self::get_author_page_hook(), array(AUTHORSURE,'insert_author_bio')); //add bio to author page	
	}
	
	public static function init() {		
		add_action( 'wp_loaded', array(AUTHORSURE,'wordpress_allow_arel') );
		add_filter( 'genesis_formatting_allowedtags', array(AUTHORSURE,'genesis_allow_arel') );
		add_shortcode('authorsure_authors', array(AUTHORSURE,'list_authors'));	
		add_shortcode('authorsure_author_box', array(AUTHORSURE,'show_author_box'));
		add_shortcode('authorsure_author_profiles', array(AUTHORSURE,'show_author_profiles'));
		add_action('wp', array(AUTHORSURE,'add_head')); //additions to head section
		add_action('wp', array(AUTHORSURE,'add_single_author')); //adds author rel=author to posts and pages
		add_action('wp', array(AUTHORSURE,'add_archive_author')); //add author rel=author link to archives
		add_action('wp', array(AUTHORSURE,'add_author_bio')); //add author rel=me links to author page
	}	
}


$thisdir = dirname(__FILE__) . '/';
if (is_admin()) {
	require_once($thisdir.'authorsure-admin.php');
	require_once($thisdir.'authorsure-archive.php');
	require_once($thisdir.'authorsure-profile.php');
	require_once($thisdir.'authorsure-post.php');
} else {
	add_action('init', array(AUTHORSURE,'init'));
}
?>