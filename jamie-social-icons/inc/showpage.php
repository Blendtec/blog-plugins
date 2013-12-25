<?php
if($options['showing'] == 'top' && $options['page'] == true) {
function add_page_top_content($content){
	 if(is_page() && is_main_query()){
 $jamiesocialcode = "";
		 $jamiesocialcode .= '[jamiesocial]';
   $content = $jamiesocialcode . $content;
	}
	return $content;
}
add_filter('the_content','add_page_top_content');
}
if($options['showing'] == 'bottom' && $options['page'] == true) {
 function add_page_bot_content($content){
		 if(is_page()){
		ob_start();
		get_jamie_social_code();
		$content .= ob_get_clean();
		 }
	 return $content;
	 }
	 add_filter('the_content','add_page_bot_content');
}
if($options['showing'] == 'topbottom' && $options['page'] == true) {
	 function add_page_topbot_content($content){
		 if(is_page() && is_main_query()){
	 $jamiesocialcode = "";
		 $jamiesocialcode .= '[jamiesocial]';
  $content = $jamiesocialcode . $content . $jamiesocialcode;
	 }
	 return $content;
	 }
	 add_filter('the_content','add_page_topbot_content');
} 