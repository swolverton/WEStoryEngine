<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/

class rhcss_editor_all_views extends module_righthere_css{
	function rhcss_editor_all_views($args=array()){
		return $this->module_righthere_css($args);
	}
	
	function options($t=array()){
		$i = count($t);
			
		//-- Day labels --------------------------------			
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-all-day-labels'; 
		$t[$i]->label 		= __('Day label','rhc');
		$t[$i]->options = array();		
			
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhc_allviews_header_font',
			'selector'	=> '.rhcalendar.not-widget .fc-view .fc-first .fc-widget-header',
			'labels'	=> (object)array(
				'family'	=> __('Day font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	
			
		$t[$i]->options = $this->add_backgroud_options( $t[$i]->options, array(
			'label'		=> __('Cell Background','rhc'),
			'prefix'	=> 'rhc_allview_day_label_bg',
			'selector'	=> '.rhcalendar.not-widget .fc-view thead .fc-first .fc-widget-header'/*,
			'derived_color'=> array(
						array(
							'type'	=> 'color_darken',
							'val'	=> '10',
							'sel'	=> ".rhcalendar .fc-state-default, .rhcalendar .fc-state-default .fc-button-inner",
							'arg'	=> array(
								(object)array(
									'name' => 'border-color',
									'tpl'	=>'__value__'
								)
							)
						)
					)	
			*/		
		));				
			
		//-- Border
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-all-border'; 
		$t[$i]->label 		= __('Border','rhc');
		$t[$i]->options = array(
			(object)array(
				'id'				=> 'rhc-all-border-color',
				'type'				=> 'css',
				'label'				=> __('Border color','rhc'),
				'input_type'		=> 'colorpicker',
				'holder_class'		=> '',
				'opacity'			=> true,
				'btn_clear'			=> true,
				'selector'			=> '.rhcalendar.not-widget .fc-view .fc-widget-header, .rhcalendar.not-widget .fc-view .fc-widget-content',
				'property'			=> 'border-color',
				'real_time'			=> true
			)		
		);		
		
		//-- Content dates	
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-all-current-day'; 
		$t[$i]->label 		= __('Current day','rhc');
		$t[$i]->options = array(
			(object)array(
				'id'				=> 'rhc-all-current-day-bg',
				'type'				=> 'css',
				'label'				=> __('Highlight color','rhc'),
				'input_type'		=> 'colorpicker',
				'holder_class'		=> '',
				'opacity'			=> true,
				'btn_clear'			=> true,
				'selector'			=> '.rhcalendar.not-widget .fc-view .fc-widget-content.fc-state-highlight',
				'property'			=> 'background-color',
				'real_time'			=> true
			)			
		);	
		
		//-- EVent
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-all-event'; 
		$t[$i]->label 		= __('Event','rhc');
		$t[$i]->options = array();	
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhc_all_event_time',
			'selector'	=> '.rhcalendar.not-widget .fc-event-time',
			'labels'	=> (object)array(
				'family'	=> __('Event time font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));			
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhc_all_event_title',
			'selector'	=> '.rhcalendar.not-widget .fc-event-title',
			'labels'	=> (object)array(
				'family'	=> __('Event title font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
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