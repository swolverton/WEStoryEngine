<?php

/**
 * 
 * I control what template is used.
 * @version $Id$
 * @copyright 2003 
 **/

class rhc_template_frontend {
	function rhc_template_frontend(){
		global $rhc_plugin;
		if( '1'!=$rhc_plugin->get_option('template_archive')){
			add_filter('archive_template', array(&$this,'archive_template'));	
		}
		if( '1'!=$rhc_plugin->get_option('template_single')){
			add_filter('single_template', array(&$this,'single_template'));
		}
		if( '1'!=$rhc_plugin->get_option('template_taxonomy')){
			add_filter('taxonomy_template', array(&$this,'taxonomy_template'));	
			add_filter('category_template', array(&$this,'taxonomy_template'));	
		}
		
		add_filter( 'query_vars', array(&$this,'query_vars') );
		
		add_action('rhc_before_content',array(&$this,'before_content'));
		add_action('rhc_after_content',array(&$this,'after_content'));
		add_shortcode('rhc_sidebar', array(&$this,'rhc_sidebar_shortcode') );
	}

	function rhc_sidebar_shortcode($atts,$content=null,$code=""){
		$output = '';
		include_once RHC_PATH.'includes/class.rhc_sidebar_shortcode.php';
		return $output;
	}
	
	function before_content(){
		global $rhc_plugin;
		echo do_shortcode($rhc_plugin->get_option('rhc-before-content'));
	}
	
	function after_content(){
		global $rhc_plugin;
		echo do_shortcode($rhc_plugin->get_option('rhc-after-content'));
	}
	
	function get_template_path(){
		global $rhc_plugin;
		return $rhc_plugin->get_template_path();
		//return apply_filters('rhc_templates_path',RHC_PATH.'templates/default/');
	}
	
	function query_vars($vars){
		array_push($vars,RHC_DISPLAY);
		return $vars;
	}
	
	function is_calendar(){
		global $rhc_plugin;
		return (get_query_var( RHC_DISPLAY )==$rhc_plugin->get_option('rhc-visualcalendar-slug',RHC_VISUAL_CALENDAR,true));
	}
	
	function archive_template($template){	
		if( $this->is_calendar() ){
			$template = $this->query_template( $this->get_template_path().'archive-'.get_query_var( 'post_type' ).'-calendar.php' );				
		}	
		return $template;
	}
	
	function single_template($template){
		$o = get_queried_object();
		if($o->post_type==RHC_EVENTS){
			$filename = $this->get_template_path().'single-event.php';
			if(file_exists($filename)){
				return $filename;
			}
		}

		return $template;
	}
	
	function taxonomy_template($template){	
		if( $this->is_calendar() ){
			$template = $this->query_template( $this->get_template_path().'taxonomy-calendar.php' );				
		}else{
			$map_original_name = array(
				RHC_VENUE 		=> 'venue',
				RHC_ORGANIZER	=> 'organizer',
				RHC_CALENDAR	=> 'calendar'
			);
			$o = get_queried_object();
			$filename = sprintf('%staxonomy-%s.php',
				$this->get_template_path(),
				isset($map_original_name[$o->taxonomy])?$map_original_name[$o->taxonomy]:$o->taxonomy
			);

			if(file_exists( $filename )){
				return $filename;
			}
		}		

		return $template;
	}
	
	function query_template($filename){
		if(file_exists($filename)){
			return $filename;
		}else{
			$filename = $this->get_template_path().'calendar.php';
			if(file_exists( $filename )){
				return $filename;
			}else{
				return RHC_PATH.'templates/default/calendar.php';
			}
		}
	}
}
?>