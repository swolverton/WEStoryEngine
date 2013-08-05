<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/
if( 'module_righthere_css' == get_parent_class($this) ) :
		$label = isset($label)?$label:array();
		//--  --------------------------------			
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= $box_prefix.'-outer'; 
		$t[$i]->label 		= isset($label['outer'])?$label['outer']:__('Back Container','rhc');
		$t[$i]->options = array();		

		$t[$i]->options[] = (object)array(
				'id'				=> $box_prefix.'_back_width',
				'type'				=> 'css',
				'label'				=> isset($label['image_width'])?$label['image_width']:__('Width','rhc'),
				'input_type'		=> 'number',
				'class'				=> 'input-mini',
				'unit'				=> '%',
				'min'				=> 0,
				'max'				=> 100,
				'step'				=> 1,
				'holder_class'		=> '',
				'selector'			=> ".fe-extrainfo-container$box_selector",
				'property'			=> 'width',
				'other_options'		=> array(
					'transparent'	=> 'transparent'
				),				
				'real_time'			=> true
			);	
		
		$t[$i]->options = $this->add_padding_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_outer_pad',
			'selector'	=> ".fe-extrainfo-container$box_selector"
		));				

		$t[$i]->options = $this->add_border_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_outer_border',
			'selector'	=> ".fe-extrainfo-container$box_selector"
		));		
				
		$t[$i]->options = $this->add_border_radius_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_outer_radius',
			'selector'	=> ".fe-extrainfo-container$box_selector,$box_selector .fe-extrainfo-container2"
		));		
		//--  --------------------------------			
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= $box_prefix.'-outer-bg'; 
		$t[$i]->label 		= isset($label['outer-bg'])?$label['outer-bg']:__('Back Container background','rhc');
		$t[$i]->options = array();		
		$t[$i]->options = $this->add_backgroud_options( $t[$i]->options, array(
			'label'		=> __('Back background','rhc'),
			'prefix'	=> $box_prefix.'_outer_bg',
			'selector'	=> ".fe-extrainfo-container$box_selector"			
		));				
		//--  --------------------------------			
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= $box_prefix.'-inner'; 
		$t[$i]->label 		= isset($label['inner'])?$label['inner']:__('Top container','rhc');
		$t[$i]->options = array();			
		$t[$i]->options = $this->add_backgroud_options( $t[$i]->options, array(
			'label'		=> __('Inner background','rhc'),
			'prefix'	=> $box_prefix.'_inner_bg',
			'selector'	=> "$box_selector .fe-extrainfo-container2"		
		));		
		//--  --------------------------------		
	if($with_image):		
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= $box_prefix.'-image'; 
		$t[$i]->label 		= isset($label['image'])?$label['image']:__('Image frame','rhc');
		$t[$i]->options = array();				
		$t[$i]->options = $this->add_padding_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_image_pad',
			'selector'	=> ".fe-extrainfo-container$box_selector .fe-image-holder img, .rhc.fe-extrainfo-container$box_selector .sws-gmap3-frame",
			'label'		=> array(
				'top'	=> __('Top width','rhc'),
				'left'	=> __('Left width','rhc'),
				'right'	=> __('Right width','rhc'),
				'bottom'	=> __('Bottom width','rhc')
			)
		));		

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
				'holder_class'		=> '',
				'selector'			=> ".fe-extrainfo-container$box_selector .fe-image-holder img, .rhc.fe-extrainfo-container$box_selector .sws-gmap3-frame",
				'property'			=> 'width',
				'other_options'		=> array(
					'transparent'	=> 'transparent'
				),				
				'real_time'			=> true
			);		
	
		$t[$i]->options[] = (object)array(
				'id'				=> $box_prefix.'_image_color',
				'type'				=> 'css',
				'label'				=> isset($label['image_color'])?$label['image_color']:__('Frame color','rhc'),
				'input_type'		=> 'color_or_something_else',
				'holder_class'		=> '',
				'opacity'			=> true,
				'btn_clear'			=> true,
				'selector'			=> ".fe-extrainfo-container$box_selector .fe-image-holder img, .rhc.fe-extrainfo-container$box_selector .sws-gmap3-frame",
				'property'			=> 'background-color',
				'other_options'		=> array(
					'transparent'	=> 'transparent'
				),				
				'real_time'			=> true
			);		
			
		$t[$i]->options = $this->add_border_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_image_border',
			'selector'	=> ".fe-extrainfo-container$box_selector .fe-image-holder img, .rhc.fe-extrainfo-container$box_selector .sws-gmap3-frame"
		));		
		
		$t[$i]->options = $this->add_border_radius_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_image_radius',
			'selector'	=> ".fe-extrainfo-container$box_selector .fe-image-holder img, .rhc.fe-extrainfo-container$box_selector .sws-gmap3-frame"
		));			
	endif;	
		//--  --------------------------------			
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= $box_prefix.'-main-title'; 
		$t[$i]->label 		= isset($label['main_title'])?$label['main_title']:__('Fonts','rhc');
		$t[$i]->options = array();	
		
		$t[$i]->options[] = (object)array(
				'id'				=> $box_prefix.'_line_height',
				'type'				=> 'css',
				'label'				=> isset($label['line_height'])?$label['line_height']:__('Line height','rhc'),
				'input_type'		=> 'number',
				'class'				=> 'input-mini',
				'min'				=> 0,
				'max'				=> 10,
				'step'				=> 0.01,
				'holder_class'		=> '',
				'selector'			=> "$box_selector .fe-extrainfo-holder .rhc-info-cell",
				'property'			=> 'line-height',		
				'real_time'			=> true
			);			
				
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> $box_prefix.'_main_title',
			'selector'	=> "$box_selector .fe-extrainfo-holder .fe-cell-label label.fe-extrainfo-label",
			'labels'	=> (object)array(
				'family'	=> __('Main title','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));		
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> $box_prefix.'_labels',
			'selector'	=> "$box_selector .fe-extrainfo-holder .rhc-info-cell:not(.fe-cell-label) label.fe-extrainfo-label",
			'labels'	=> (object)array(
				'family'	=> __('Labels','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));		
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> $box_prefix.'_values',
			'selector'	=> "$box_selector .fe-extrainfo-holder .rhc-info-cell:not(.fe-cell-label) .fe-extrainfo-value",
			'labels'	=> (object)array(
				'family'	=> __('Values (normal)','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> $box_prefix.'_links',
			'selector'	=> "$box_selector .fe-extrainfo-holder .rhc-info-cell:not(.fe-cell-label) .fe-extrainfo-value a",
			'labels'	=> (object)array(
				'family'	=> __('Values (links)','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> $box_prefix.'_description',
			'selector'	=> "$box_selector .fe-extrainfo-holder .dbox-description",
			'labels'	=> (object)array(
				'family'	=> __('Description','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));		

endif;		
?>