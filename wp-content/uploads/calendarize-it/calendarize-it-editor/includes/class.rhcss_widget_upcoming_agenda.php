<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/

class rhcss_widget_upcoming_agenda extends module_righthere_css{
	function rhcss_widget_upcoming_agenda($args=array()){
		$args['cb_init']=array(&$this,'cb_init');
		return $this->module_righthere_css($args);
	}
	
	function cb_init(){
		//called on the head when editor is active.
	}
	
	function options($t=array()){
		$i = count($t);

		$box_prefix = 'rhcwu-agenda';
		$item_selector = '.rhc-widget-a';
		$agenda_selector = '.rhc-widget-a .rhc-featured-date';
		$use_date_options = false;
		
		include 'widget_upcoming_options.php';
		
		include 'widget_upcoming_agenda_options.php';
		
					
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