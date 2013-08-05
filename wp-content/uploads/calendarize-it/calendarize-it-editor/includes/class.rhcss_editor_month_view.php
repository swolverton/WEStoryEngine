<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/

class rhcss_editor_month_view extends module_righthere_css{
	function rhcss_editor_month_view($args=array()){
		return $this->module_righthere_css($args);
	}
	
	function options($t=array()){
		$i = count($t);
		//-- Container --------------------------------			
		/*
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-month-container'; 
		$t[$i]->label 		= __('Container','rhc');
		$t[$i]->options = array();		
		*/	
		//-- Background
		//.rhcalendar.not-widget
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-month-background'; 
		$t[$i]->label 		= __('View background','rhc');
		$t[$i]->options = array();	
		$t[$i]->options = $this->add_backgroud_options( $t[$i]->options, array(
			'label'		=> __('View Background','rhc'),
			'prefix'	=> 'rhc_monthview_bg',
			'selector'	=> '.rhcalendar.not-widget .fc-view-month'	
		));		
		
		//-- Top cell --------------------------------			
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-month-top'; 
		$t[$i]->label 		= __('Day label','rhc');
		$t[$i]->options = array();		
		
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhc_month_header_font',
			'selector'	=> '.rhcalendar.not-widget .fc-view-month.fc-view .fc-first .fc-widget-header',
			'labels'	=> (object)array(
				'family'	=> __('Day font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	
		
			
		//-- Day number
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-month-day-number'; 
		$t[$i]->label 		= __('Day number','rhc');
		$t[$i]->options = array();		
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhc_month_dnum_font',
			'selector'	=> '.rhcalendar.not-widget .fc-view-month.fc-grid .fc-day-number',
			'labels'	=> (object)array(
				'family'	=> __('Day font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	
			
		//-- Week number
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-month-week-number'; 
		$t[$i]->label 		= __('Week number','rhc');
		$t[$i]->options = array();		
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhc_month_wnum_font',
			'selector'	=> '.rhcalendar.not-widget .fc-view-month.fc-grid tbody .fc-week-number',
			'labels'	=> (object)array(
				'family'	=> __('Day font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	
		$t[$i]->options[] = (object)array(
				'id'				=> 'rhc_month_wnum_bg',
				'type'				=> 'css',
				'label'				=> __('Background color','rhc'),
				'input_type'		=> 'color_or_something_else',
				'holder_class'		=> '',
				'opacity'			=> true,
				'btn_clear'			=> true,
				'selector'			=> '.rhcalendar.not-widget .fc-view-month.fc-grid tbody .fc-week-number',
				'property'			=> 'background-color',
				'other_options'		=> array(
					'transparent'	=> 'transparent'
				),				
				'real_time'			=> true
			);
		$t[$i]->options = $this->add_padding_options($t[$i]->options,array(
			'prefix'	=> 'rhc_month_wnum_pad',
			'selector'	=> '.rhcalendar.not-widget .fc-view-month.fc-grid tbody .fc-week-number'
		));		
		

			
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