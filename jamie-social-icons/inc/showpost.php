<?php
if($options['showing'] == 'top' && $options['post'] == true) {
function add_post_top_content($content){
	 if(is_singular('post') && is_main_query()){
		 $jamiesocialcode = "";
		 $jamiesocialcode .= '[jamiesocial]';
		 $content = $jamiesocialcode . $content;
	 }
  return  $content;
}
add_filter('the_content','add_post_top_content');
}

if($options['showing'] == 'bottom' && $options['post'] == true) {
 function add_post_bot_content($content){
		 if(is_single()){
		ob_start();
		get_jamie_social_code();
		$content .= ob_get_clean();
		 }
	 return $content;
	 }
	 add_filter('the_content','add_post_bot_content');
}
if($options['showing'] == 'topbottom' && $options['post'] == true) {
	 function add_post_topbot_content($content){
		 if(is_single() && is_main_query()){
			 $jamiesocialcode = "";
		 $jamiesocialcode .= '[jamiesocial]';
  $content = $jamiesocialcode . $content . $jamiesocialcode;
	 }
	 return $content;
	 }
	 add_filter('the_content','add_post_topbot_content');
} 



 /*if($options['showing'] == 'top' && $options['post'] == true && $options['home'] == true) {
function add_home_top_content($content){
	 if(is_home()){
		 $jamiesocialcode = "";
		 $jamiesocialcode .= '[jamiesocial]';
		 $content = $jamiesocialcode . $content;
	 }
  return  $content;
}
add_filter('the_excerpt','add_home_top_content');
}

if($options['showing'] == 'bottom' && $options['post'] == true && $options['home'] == true) {
 function add_home_bot_content($content){
		 if(is_home()){
		ob_start();
		get_jamie_social_code();
		$content .= ob_get_clean();
		 }
	 return $content;
	 }
	 add_filter('the_excerpt','add_home_bot_content');
}
if($options['showing'] == 'topbottom' && $options['post'] == true && $options['home'] == true) {
	 function add_home_topbot_content($content){
		 if(is_home() ){
				 $jamiesocialcode = "";
		 $jamiesocialcode .= '[jamiesocial]';
  $content = $jamiesocialcode . $content . $jamiesocialcode;
		 }
		 return $content;
	 }
	 add_filter('the_excerpt','add_home_topbot_content');
} */