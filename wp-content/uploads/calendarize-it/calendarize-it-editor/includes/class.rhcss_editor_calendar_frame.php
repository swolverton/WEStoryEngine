<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/

class rhcss_editor_calendar_frame extends module_righthere_css{
	function rhcss_editor_calendar_frame($args=array()){
		$args['cb_init']=array(&$this,'cb_init');
		return $this->module_righthere_css($args);
	}

	function cb_init(){
		//called on the head when editor is active.
	}
	
	function options($t=array()){
		$i = count($t);
		//require RHL_PATH.'includes/admin_frontend_options.php';
		//----------------------------------------------------------------------
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-header'; 
		$t[$i]->label 		= __('Calendar header','rhc');
		$t[$i]->options = array();
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhc_title',
			'selector'	=> 'body .rhcalendar .fullCalendar .fc-header-title h2',
			'labels'	=> (object)array(
				'family'	=> __('Title font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')
								
			)
		));

		$t[$i]->options[] =(object)array(
				'id'				=> 'rhc_title_margin_top',
				'type'				=> 'css',
				'label'				=> __('Title top margin','rhc'),
				'input_type'		=> 'number',
				'unit'				=> 'px',
				'class'				=> 'input-mini',
				'min'				=> 0,
				'max'				=> 100,
				'step'				=> 1,
				'selector'			=> 'body .rhcalendar .fullCalendar .fc-header-title',
				'property'			=> 'margin-top',
				'real_time'			=> true
			);			
		
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhc_header_btn_font',
			'selector'	=> '.rhcalendar .fc-header .fc-button, .rhcalendar .fc-footer .fc-button',
			'labels'	=> (object)array(
				'family'	=> __('Header button font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')
								
			)
		));

		$t[$i]->options[] =(object)array(
				'id'				=> 'rhc_header_btn_font_shadow',
				'type'				=> 'css',
				'label'				=> __('Header button font shadow','rhc'),
				'input_type'		=> 'textshadow',
				'opacity'			=> true,
				'selector'			=> '.rhcalendar .fc-header .fc-button:not(.fc-state-active), .rhcalendar .fc-footer .fc-button',
				'property'			=> 'text-shadow',
				'real_time'			=> true,
				'btn_clear'			=> true
			);	
		//----------------------------------------------------------------------
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-header-btn-def'; 
		$t[$i]->label 		= __('Header button (default state)','rhc');
		$t[$i]->options = array();
		$t[$i]->options = $this->add_backgroud_options( $t[$i]->options, array(
			'label'		=> __('Button background','rhc'),
			'prefix'	=> 'rhc_head_btn_bg_def',
			'selector'	=> '.rhcalendar .fc-state-default,.rhcalendar .fc-footer .fc-button.fc-state-default'/*,
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
		
		//---- HEAD BUTTON ACTIVE ------------------------------------------------------------------
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-header-btn-act'; 
		$t[$i]->label 		= __('Header button (active state)','rhc');
		$t[$i]->options = array();
		
		$t[$i]->options[] =(object)array(
				'id'				=> 'rhc_header_btn_act_color',
				'type'				=> 'css',
				'label'				=> __('Font color','rhc'),
				'input_type'		=> 'color_or_something_else',
				'selector'			=> '.rhcalendar .fc-state-default.fc-state-active',
				'property'			=> 'color',
				'real_time'			=> true,
				'btn_clear'			=> true
			);
				
		$t[$i]->options[] =(object)array(
				'id'				=> 'rhc_header_btn_act_shadow',
				'type'				=> 'css',
				'label'				=> __('Text shadow','rhc'),
				'input_type'		=> 'textshadow',
				'opacity'			=> true,
				'selector'			=> '.rhcalendar .fc-state-default.fc-state-active',
				'property'			=> 'text-shadow',
				'real_time'			=> true,
				'btn_clear'			=> true
			);	
									
		$t[$i]->options = $this->add_backgroud_options( $t[$i]->options, array(
			'label'		=> __('Button background','rhc'),
			'prefix'	=> 'rhc_head_btn_bg_act',
			'selector'	=> '.rhcalendar .fc-state-default.fc-state-active'			
		));		
		
		//----------------------------------------------------------------------

		
		//--- FILTER BOX-------------------------------
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-filter-head'; 
		$t[$i]->label 		= __('Filter box','rhc');
		$t[$i]->options = array();
		$t[$i]->options[] =(object)array(
				'id'				=> 'rhc_filter_head_shadow',
				'type'				=> 'css',
				'label'				=> __('Box shadow','rhc'),
				'input_type'		=> 'textshadow',
				'opacity'			=> true,
				'selector'			=> 'body .rhcalendar .fbd-main-holder',//make sure this is more specific than the background derived one.
				'property'			=> 'box-shadow',
				'real_time'			=> true,
				'btn_clear'			=> true
			);			
					
		//--- FILTER HEAD BG-------------------------------
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-filter-head-bg'; 
		$t[$i]->label 		= __('Filter head background','rhc');
		$t[$i]->options = array();
		
		$t[$i]->options = $this->add_backgroud_options( $t[$i]->options, array(
			'label'		=> __('Background','rhc'),
			'label_bg'	=> __('Tip and background color','rhc'),
			'prefix'	=> 'rhc_filter_head_bg',
			'selector'	=> '.rhcalendar .fbd-head',
			'queue'		=> 'calendar_frame',
			'derived_color'=> array(
						array(
							'type'	=> 'color_darken',
							'val'	=> '1',
							'sel'	=> ".rhcalendar .fbd-main-holder",
							'arg'	=> array(
								(object)array(
									'name' => 'box-shadow',
									'tpl'	=>'0 1px 12px __value__;'
								)
							)
						),			
						array(
							'type'	=> 'color_darken',
							'val'	=> '1',
							'sel'	=> ".rhcalendar .fbd-arrow",
							'arg'	=> array(
								(object)array(
									'name' => 'border-color',
									'tpl'	=>'transparent transparent __value__ transparent'
								)
							)
						),
						array(
							'type'	=> 'color_darken',
							'val'	=> '20',
							'sel'	=> ".rhcalendar .fbd-arrow-border",
							'arg'	=> array(
								(object)array(
									'name' => 'border-color',
									'tpl'	=>'transparent transparent __value__ transparent'
								)
							)
						)
					)		
		));		

		//--- FILTER BOX BG-------------------------------
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-filter-body-bg'; 
		$t[$i]->label 		= __('Filter body background','rhc');
		$t[$i]->options = array();
		$t[$i]->options = $this->add_backgroud_options( $t[$i]->options, array(
			'label'		=> __('Background','rhc'),
			'prefix'	=> 'rhc_filter_body_bg',
			'selector'	=> '.rhcalendar .fbd-main-holder',
			'queue'		=> 'calendar_frame',
			'derived_color'=> array(		
						array(
							'type'	=> 'same',
							'val'	=> '1',
							'sel'	=> ".rhcalendar .ical-tooltip .fbd-arrow",
							'arg'	=> array(
								(object)array(
									'name' => 'border-color',
									'tpl'	=>'__value__ transparent transparent transparent'
								)
							)
						),
						array(
							'type'	=> 'color_darken',
							'val'	=> '20',
							'sel'	=> ".rhcalendar .ical-tooltip .fbd-arrow-border",
							'arg'	=> array(
								(object)array(
									'name' => 'border-color',
									'tpl'	=>'__value__ transparent transparent transparent'
								)
							)
						)
					)						
			)
		);		
		
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-filter-tab'; 
		$t[$i]->label 		= __('Filter tab','rhc');
		$t[$i]->options = array();			
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhc_filter_tab_font',
			'selector'	=> '.rhcalendar .fbd-tabs a',
			'labels'	=> (object)array(
				'family'	=> __('Tab font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhc_filter_tab_body_font',
			'selector'	=> '.rhcalendar .fbd-term-label',
			'labels'	=> (object)array(
				'family'	=> __('Content font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));
		$t[$i]->options[] =(object)array(
				'id'				=> 'rhc_filter_tab_bgcolor',
				'type'				=> 'css',
				'label'				=> __('Content bg color','rhc'),
				'input_type'		=> 'color_or_something_else',
				'opacity'			=> true,
				'selector'			=> '.rhcalendar .fbd-tabs-panel',
				'property'			=> 'background-color',
				'real_time'			=> true,
				'btn_clear'			=> true,
				'derived'=> array(
							array(
								'type'	=> 'color_darken',
								'val'	=> '5',
								'sel'	=> ".rhcalendar .fbd-tabs-panel",
								'arg'	=> array(
									(object)array(
										'name' => 'border-color',
										'tpl'	=>'__value__;'
									)
								)
							),
							array(
								'type'	=> 'color_darken',
								'val'	=> '5',
								'sel'	=> ".rhcalendar .fbd-ul li.fbd-tabs.fbd-active-tab",
								'arg'	=> array(
									(object)array(
										'name' => 'border-color',
										'tpl'	=>'__value__;'
									)
								)
							),
							array(
								'type'	=> 'same',
								'val'	=> '5',
								'sel'	=> ".fbd-ul li.fbd-tabs.fbd-active-tab",
								'arg'	=> array(
									(object)array(
										'name' => 'background-color',
										'tpl'	=>'__value__;'
									),
									(object)array(
										'name' => 'border-bottom',
										'tpl'	=>'1px solid __value__;'
									)
								)
							)
						)					
			);			
		//-- FILTER TAB PRIMARY button--------------------------------			
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-filter-tab-primary'; 
		$t[$i]->label 		= __('Filter primary button','rhc');
		$t[$i]->options = array();							
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhc_filter_tab_primary_font',
			'selector'	=> '.rhcalendar .fbd-button-primary',
			'labels'	=> (object)array(
				'family'	=> __('Font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));
		
		$t[$i]->options[] =(object)array(
				'id'				=> 'rhc_filter_tab_primary_font_shadow',
				'type'				=> 'css',
				'label'				=> __('Text shadow','rhc'),
				'input_type'		=> 'textshadow',
				'opacity'			=> true,
				'selector'			=> '.rhcalendar .fbd-button-primary',
				'property'			=> 'text-shadow',
				'real_time'			=> true,
				'btn_clear'			=> true
			);			
			
		$t[$i]->options[] =(object)array(
				'id'				=> 'rhc_filter_primary_bg',
				'type'				=> 'css',
				'label'				=> __('Background color','rhc'),
				'input_type'		=> 'color_gradient',
				'opacity'			=> true,
				'selector'			=> '.rhcalendar .fbd-button-primary',
				'property'			=> 'background-image',
				'real_time'			=> true,
				'btn_clear'			=> true,
				'derived'			=> array(
						array(
							'type'	=> 'gradient_darken',
							'val'	=> '5',
							'sel'	=> ".rhcalendar .fbd-button-primary",
							'arg'	=> array(
								(object)array(
									'name' => 'border-color',
									'tpl'	=>'__value__'
								)
							)
						),
						array(
							'type'	=> 'same2',
							'val'	=> '',
							'sel'	=> ".rhcalendar .fbd-button-primary:hover",
							'arg'	=> array(
								(object)array(
									'name' => 'background-image',
									'tpl'	=>'__value__'
								)
							)
						),
						array(
							'type'	=> 'same2',
							'val'	=> '',
							'sel'	=> ".rhcalendar .fbd-button-primary:active",
							'arg'	=> array(
								(object)array(
									'name' => 'background-image',
									'tpl'	=>'__value__'
								)
							)
						)
					)
		);

		//-- FILTER TAB SECONDARY button--------------------------------			
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-filter-tab-secondary'; 
		$t[$i]->label 		= __('Filter secondary button','rhc');
		$t[$i]->options = array();							
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhc_filter_tab_secondary_font',
			'selector'	=> '.rhcalendar .fbd-button-secondary',
			'labels'	=> (object)array(
				'family'	=> __('Font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	
								
		$t[$i]->options[] =(object)array(
				'id'				=> 'rhc_filter_tab_secondary_font_shadow',
				'type'				=> 'css',
				'label'				=> __('Text shadow','rhc'),
				'input_type'		=> 'textshadow',
				//'class'				=> 'input-small',
				'opacity'			=> true,
				'selector'			=> '.rhcalendar .fbd-button-secondary',
				'property'			=> 'text-shadow',
				'real_time'			=> true,
				'btn_clear'			=> true
			);	
			
		$t[$i]->options[] =(object)array(
				'id'				=> 'rhc_filter_secondary_bg',
				'type'				=> 'css',
				'label'				=> __('Background color','rhc'),
				'input_type'		=> 'color_gradient',
				'opacity'			=> true,
				'selector'			=> '.rhcalendar .fbd-button-secondary',
				'property'			=> 'background-image',
				'real_time'			=> true,
				'btn_clear'			=> true,
				'derived'			=> array(
						array(
							'type'	=> 'gradient_darken',
							'val'	=> '5',
							'sel'	=> ".rhcalendar .fbd-button-secondary",
							'arg'	=> array(
								(object)array(
									'name' => 'border-color',
									'tpl'	=>'__value__'
								)
							)
						),
						array(
							'type'	=> 'same2',
							'val'	=> '',
							'sel'	=> ".rhcalendar .fbd-button-secondary:hover",
							'arg'	=> array(
								(object)array(
									'name' => 'background-image',
									'tpl'	=>'__value__'
								)
							)
						),
						array(
							'type'	=> 'same2',
							'val'	=> '',
							'sel'	=> ".rhcalendar .fbd-button-secondary:active",
							'arg'	=> array(
								(object)array(
									'name' => 'background-image',
									'tpl'	=>'__value__'
								)
							)
						)
					)
		);

		//-- TOOLTIP --------------------------------			
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-tooltip'; 
		$t[$i]->label 		= __('Tooltip','rhc');
		$t[$i]->options = array();	
		$t[$i]->options = $this->add_backgroud_options( $t[$i]->options, array(
			'label'		=> __('Background','rhc'),
			'label_bg'	=> __('Bg, border and tip color','rhc'),
			'prefix'	=> 'rhc_tooltip',
			'selector'	=> '.fct-tooltip',
			'derived_color'=> array(
						array(
							'type'	=> 'color_darken',
							'val'	=> '5',
							'sel'	=> ".fct-tooltip",
							'arg'	=> array(
								(object)array(
									'name' => 'border-color',
									'tpl'	=>'__value__;'
								),
								(object)array(
									'name' => 'box-shadow',
									'tpl'	=>'0 1px 12px __value__;'
								)
							)
						),
						array(
							'type'	=> 'same',
							'val'	=> '',
							'sel'	=> ".fc-tip-left .fct-arrow",
							'arg'	=> array(
								(object)array(
									'name' => 'border-color',
									'tpl'	=>'transparent __value__ transparent transparent;'
								)
							)
						),
						array(
							'type'	=> 'same',
							'val'	=> '',
							'sel'	=> ".fc-tip-right .fct-arrow",
							'arg'	=> array(
								(object)array(
									'name' => 'border-color',
									'tpl'	=>'transparent transparent transparent __value__;'
								)
							)
						)
					)				
			)			
		);

		//-- TOOLTIP fonts--------------------------------			
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-tooltip-fonts'; 
		$t[$i]->label 		= __('Tooltip fonts','rhc');
		$t[$i]->options = array();		
		
		$t[$i]->options[] =(object)array(
				'id'				=> 'rhc_tooltip_title_line_h',
				'type'				=> 'css',
				'label'				=> __('Title line height','rhc'),
				'input_type'		=> 'number',
				'min'				=> 0,
				'max'				=> 200,
				'step'				=> 1,
				'unit'				=> 'px',
				'class'				=> 'input-small',
				'selector'			=> '.fct-header .fc-title, .fct-header .fc-title a',
				'property'			=> 'line-height',
				'real_time'			=> true,
				'btn_clear'			=> true
			);	
			
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhc_tooltip_title_font',
			'selector'	=> '.fct-header .fc-title, .fct-header .fc-title a',
			'labels'	=> (object)array(
				'family'	=> __('Title font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	
			
		$t[$i]->options[] =(object)array(
				'id'				=> 'rhc_fct_default_font',
				'type'				=> 'css',
				'label'				=> __('Default font family'),
				'input_type'		=> 'font',
				'class'				=> '',
				'holder_class'		=> '',
				//'class'				=> 'input-mini pop_rangeinput',
				'selector'			=> '.fct-tooltip',
				'property'			=> 'font-family',
				'real_time'			=> true
			);		
	
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
//endif;		
//----------------------------------------------------------------------
		return $t;
	}
}
?>