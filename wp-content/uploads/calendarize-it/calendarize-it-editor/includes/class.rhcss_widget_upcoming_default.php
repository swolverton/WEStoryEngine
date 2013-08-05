<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/

class rhcss_widget_upcoming_default extends module_righthere_css{
	function rhcss_widget_upcoming_default($args=array()){
		$args['cb_init']=array(&$this,'cb_init');
		return $this->module_righthere_css($args);
	}
	
	function cb_init(){
		//called on the head when editor is active.
	}
	
	function options($t=array()){
		$i = count($t);

		$box_prefix = 'rhcwu-default';
		$item_selector = '.rhc-widget-upcoming-item:not(.rhc-widget-a):not(.rhc-widget-b)';
		$use_date_options = true;
		include 'widget_upcoming_options.php';
		
			
		//-- Saved and DC  -----------------------		
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rh-saved-list'; 
		$t[$i]->label 		= __('Templates','rhc');
		$t[$i]->options = array(
			(object)array(
				'id'				=> 'rh_saved_settings',
				'input_type'		=> 'backup_list'
			)			
		);			
//----------------------------------------------------------------------
		return $t;
	}
}
?>