<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/

if( 'module_righthere_css' == get_parent_class($this) ) :

		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= $box_prefix.'-dbox'; 
		$t[$i]->label 		= __('Widget Date box','rhc');
		$t[$i]->options = array();	
		/*
		$t[$i]->options[] =(object)array(
				'id'				=> $box_prefix.'_width',
				'type'				=> 'css',
				'label'				=> __('Width','rhc'),
				'input_type'		=> 'number',
				'unit'				=> 'px',
				'class'				=> 'input-mini',
				'min'				=> 0,
				'max'				=> 100,
				'step'				=> 1,
				'selector'			=> $agenda_selector,
				'property'			=> 'width',
				'real_time'			=> true
			);		
		*/	
		$t[$i]->options = $this->add_border_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_box_border',
			'selector'	=> $agenda_selector
		));		
		
		$t[$i]->options = $this->add_border_radius_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_box_radius',
			'selector'	=> $agenda_selector
		));		
		
		$t[$i]->options[] =(object)array(
				'id'				=> $box_prefix.'_shadow',
				'type'				=> 'css',
				'label'				=> __('Box shadow','rhc'),
				'input_type'		=> 'textshadow',
				'opacity'			=> true,
				'selector'			=> $agenda_selector,
				'property'			=> 'box-shadow',
				'real_time'			=> true,
				'btn_clear'			=> true
			);
			
		$t[$i]->options = $this->add_padding_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_box_pad',
			'selector'	=> $agenda_selector
		));					
		//--------
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= $box_prefix.'-dbox-bg'; 
		$t[$i]->label 		= __('Widget Date box Background','rhc');
		$t[$i]->options = array();	
		
		$t[$i]->options = $this->add_backgroud_options( $t[$i]->options, array(
			'label'		=> __('Background','rhc'),
			'prefix'	=> $box_prefix.'_bg_',
			'selector'	=>  $agenda_selector
		));						
		//--------
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= $box_prefix.'-dbox-day'; 
		$t[$i]->label 		= __('Widget Date box Day','rhc');
		$t[$i]->options = array();	
		
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> $box_prefix.'_day_',
			'selector'	=> $item_selector.' .rhc-date-day',
			'labels'	=> (object)array(
				'family'	=> __('Day font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	
		
		$t[$i]->options = $this->add_padding_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_day_pad',
			'selector'	=> $item_selector.' .rhc-date-day',
			'left'=>false,
			'right'=>false
		));		
				
		$t[$i]->options = $this->add_border_radius_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_day_radius',
			'selector'	=> $item_selector.' .rhc-date-day'
		));		
		
		$t[$i]->options = $this->add_backgroud_options( $t[$i]->options, array(
			'label'		=> __('Background','rhc'),
			'prefix'	=> $box_prefix.'_day_bg_',
			'selector'	=>  $item_selector.' .rhc-date-day'
		));				
		//--------
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= $box_prefix.'-dbox-month'; 
		$t[$i]->label 		= __('Widget Date box Month','rhc');
		$t[$i]->options = array();		
		
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> $box_prefix.'_month_',
			'selector'	=> $item_selector.' .rhc-date-month-year',
			'labels'	=> (object)array(
				'family'	=> __('Month font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));			

		$t[$i]->options = $this->add_padding_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_month_pad',
			'selector'	=> $item_selector.' .rhc-date-month-year',
			'left'=>false,
			'right'=>false
		));		
		
		$t[$i]->options = $this->add_border_radius_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_month_radius',
			'selector'	=> $item_selector.' .rhc-date-month-year'
		));		
	
		$t[$i]->options = $this->add_backgroud_options( $t[$i]->options, array(
			'label'		=> __('Background','rhc'),
			'prefix'	=> $box_prefix.'_month_bg_',
			'selector'	=>  $item_selector.' .rhc-date-month-year'
		));		

endif;
?>