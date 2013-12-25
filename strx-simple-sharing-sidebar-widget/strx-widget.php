<?php
/** Wordpress Widget Helper Class */
if (!class_exists('Strx_Widget')){
abstract class Strx_Widget extends WP_Widget {
    /***********************************************************
    **  Abstract Functions
    ***********************************************************/

    /** Return the pugin id, ie: my-plugin */
    abstract function w_id();
    /** Return the pugin name, ie: My Plugin */
    abstract function w_name();

    /** Return the form content to show in admin dashboard */
    abstract function w_form($instance);
    /** Return the real widget content */
    abstract function w_content($instance);
    /** Return the plugin default options, a name=>value array, ie: array('title'=>'My Plugin Title') */
    abstract function w_defaults();

    /** Title field to retrieve from options */
    function w_title_field(){ return 'title'; }
	/** Possibility to set a custom title */
	function w_title($instance){
		return $instance['title'];
	}

    /***********************************************************
    **  Static Functions
    ***********************************************************/

    /** Register the Widget using Wordpress Actions */
    static function w_init($classname=null){
        if (is_null($classname)){
            $classname=get_called_class();
        }
        add_action( 'widgets_init', create_function('', 'return register_widget("'.$classname.'");') );
    }

    /***********************************************************
    **  Wordpress Hooks
    ***********************************************************/

    /** Class constructor */
    function Strx_Widget(){
        $widget_ops=$this->w_defaults();
        $control_ops = array('width' => 462);
        $this->WP_Widget($this->w_id(), $this->w_name(), $widget_ops, $control_ops);
    }

    /** Admin Dashboard Form */
	function form($instance) {
		$instance = $this->parse_args( $instance );
        echo $this->w_form($instance);
	}

    /** Widget Code */
	function widget($args, $instance) {
        extract($args);
        $tfld=$this->w_title_field();        
        echo $before_widget;
        if (!empty($tfld)){
			$t=$this->w_title($instance);
            $title = apply_filters('widget_title', empty($t) ? '&nbsp;' : $t, $instance, $this->id_base);
            if ( $title ){ echo $before_title . $title . $after_title; }
        }
        echo '<div class="'.$this->id_base.'-wrapper">'.$this->w_content($instance).'</div>';
        echo $after_widget;	
    }
    /** Called By Wordpress when saving settings */
	function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $new_instance = $this->parse_args( $new_instance );
        $def=$this->w_defaults();
        foreach($new_instance as $k=>$v){
            $instance[$k] = strip_tags($v);
            if (empty($instance[$k]) && !empty($def[$k])){
                $instance[$k]=$def[$k];
            }
        }
        return $instance;
	}


    /***********************************************************
    **  Utility Functions
    ***********************************************************/

    /** Return an input type=text field ready for admin dashboard */
    function w_form_input($instance, $name, $title=null){
        if (is_null($title)) { $title=ucwords($name); }
        return  '<label for="'.$this->get_field_id($name).'">'.__($title).'</label>'.
                '<input class="widefat" type="text" id="'.$this->get_field_id($name).'" name="'.$this->get_field_name($name).'" value="'.esc_attr($instance[$name]).'"/>';
    }    

    /** Return a textarea ready for admin dashboard */
    function w_form_textarea($instance, $name, $title=null){
        if (is_null($title)) { $title=ucwords($name); }
        return  '<label for="'.$this->get_field_id($name).'">'.__($title).'</label>'.
                '<textarea class="widefat" id="'.$this->get_field_id($name).'" name="'.$this->get_field_name($name).'">'.esc_attr($instance[$name]).'</textarea>';
    }

    /** Simplify wp_parse_args */
    function parse_args($instance){
        return wp_parse_args( (array)$instance, $this->w_defaults());
    }

	/** jQuery like extend */
	function extend() {
		$args = func_get_args();
		$extended = array();
		if(is_array($args) && count($args)) {
			foreach($args as $array) {
				if(is_array($array)) {
					$extended = array_merge($extended, $array);
				}
			}
		}
		return $extended;
	}
}
}