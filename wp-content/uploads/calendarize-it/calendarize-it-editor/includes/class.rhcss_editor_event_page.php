<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/

class rhcss_editor_event_page extends module_righthere_css{
	function rhcss_editor_event_page($args=array()){
		return $this->module_righthere_css($args);
	}
	
	function options($t=array()){
		$i = count($t);
		//-----------------	
		$label = array();
		$box_prefix = 'rhcse_top';
		$selector = 'body img.rhc_top_image';	
		//------------------
		
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= '-image'; 
		$t[$i]->label 		= __('Image','rhc');
		$t[$i]->options = array();	
		//------------------
		$t[$i]->options[]=(object)array('input_type'		=> 'grid_start');
		$t[$i]->options[] = (object)array(
				'id'				=> $box_prefix.'_image_width',
				'type'				=> 'css',
				'label'				=> isset($label['image_width'])?$label['image_width']:__('Width','rhc'),
				'input_type'		=> 'number',
				'class'				=> 'input-mini',
				'unit'				=> '%',
				'min'				=> 0,
				'max'				=> 100,
				'step'				=> 1,
				'holder_class'		=> 'span6',
				'selector'			=> $selector,
				'property'			=> 'width',
				'other_options'		=> array(
					'transparent'	=> 'transparent'
				),				
				'real_time'			=> true
			);	
				
		$t[$i]->options[]=(object)array(
				'id'				=> $box_prefix.'_image_float',
				'type'				=> 'css',
				'label'				=> __('Position','rhc'),
				'input_type'		=> 'select',
				'class'				=> 'input-small',
				'holder_class'		=> 'span6',				
				'selector'			=> $selector,
				'options'			=> array(
					'none'			=>	'none',
					'left'	=>	__('Left','rhc'),
					'right'	=>__('Right','rhc')
				),
				'property'			=> 'float',
				'real_time'			=> true
			);
		$t[$i]->options[]=(object)array('input_type'		=> 'grid_end');					
		//-----------
		
		$t[$i]->options = $this->add_margin_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_img_margin',
			'selector'	=> $selector
		));				

		//----------------------------------------			
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= $box_prefix.'-imagefr'; 
		$t[$i]->label 		= isset($label['image'])?$label['image']:__('Image frame','rhc');
		$t[$i]->options = array();				
		$t[$i]->options = $this->add_padding_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_image_pad',
			'selector'	=> $selector,
			'label'		=> array(
				'top'	=> __('Top width','rhc'),
				'left'	=> __('Left width','rhc'),
				'right'	=> __('Right width','rhc'),
				'bottom'	=> __('Bottom width','rhc')
			)
		));		

	
			
		$t[$i]->options[] = (object)array(
				'id'				=> $box_prefix.'_image_color',
				'type'				=> 'css',
				'label'				=> isset($label['image_color'])?$label['image_color']:__('Frame color','rhc'),
				'input_type'		=> 'color_or_something_else',
				'holder_class'		=> '',
				'opacity'			=> true,
				'btn_clear'			=> true,	
				'selector'	=> $selector,
				'property'			=> 'background-color',
				'other_options'		=> array(
					'transparent'	=> 'transparent'
				),				
				'real_time'			=> true
			);		
			
		$t[$i]->options = $this->add_border_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_image_border',
			'selector'	=> $selector,
		));		
		
		$t[$i]->options = $this->add_border_radius_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_image_radius',
			'selector'	=> $selector,
		));			
			
		//-------------
		
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