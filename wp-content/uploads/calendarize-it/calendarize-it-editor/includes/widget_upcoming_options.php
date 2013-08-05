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
		$t[$i]->id 			= $box_prefix.'-title'; 
		$t[$i]->label 		= __('Title','rhc');
		$t[$i]->options = array();	

		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> $box_prefix.'_title_font_',
			'selector'	=> $item_selector.' a.rhc-title-link',
			'labels'	=> (object)array(
				'family'	=> __('Title font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	

		$t[$i]->options[] = (object)array(
				'id'				=> $box_prefix.'_title_line_height',
				'type'				=> 'css',
				'label'				=> isset($label['title_line_height'])?$label['title_line_height']:__('Title line height','rhc'),
				'input_type'		=> 'number',
				'class'				=> 'input-mini',
				'unit'				=> '%',
				'min'				=> 0,
				'max'				=> 100,
				'step'				=> 1,
				'holder_class'		=> '',
				'selector'			=> $item_selector.' a.rhc-title-link',
				'property'			=> 'line-height',
				'other_options'		=> array(
					'transparent'	=> 'transparent'
				),				
				'real_time'			=> true
			);	
		
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= $box_prefix.'-date'; 
		$t[$i]->label 		= __('Date','rhc');
		$t[$i]->options = array();	
	if($use_date_options):		
		$t[$i]->options[]=(object)array(
				'id'				=> $box_prefix.'_date_show_datetime',
				'type'				=> 'css',
				'label'				=> __('Date & time visibility','rhcss'),
				'input_type'		=> 'select',
				'class'				=> 'input-small',
				'holder_class'		=> '',				
				'selector'			=> $item_selector.' .rhc-widget-date-time',
				'options'			=> array(
					''				=> '',
					'inline-block'	=> __('Show','rhc'),
					'none'			=> __('Hide','rhc')
				),
				'property'			=> 'display',
				'real_time'			=> true
			);	
		
		$t[$i]->options[]=(object)array(
				'id'				=> $box_prefix.'_date_show_date',
				'type'				=> 'css',
				'label'				=> __('Date visibility','rhcss'),
				'input_type'		=> 'select',
				'class'				=> 'input-small',
				'holder_class'		=> '',				
				'selector'			=> $item_selector.' .rhc-widget-date',
				'options'			=> array(
					''				=> '',
					'inline-block'	=> __('Show','rhc'),
					'none'			=> __('Hide','rhc')
				),
				'property'			=> 'display',
				'real_time'			=> true
			);	
		
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> $box_prefix.'_date_date_',
			'selector'	=> $item_selector.' .rhc-widget-date',
			'labels'	=> (object)array(
				'family'	=> __('Date font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	
	endif;	
		$t[$i]->options[]=(object)array(
				'id'				=> $box_prefix.'_date_show_time',
				'type'				=> 'css',
				'label'				=> __('Time visibility','rhcss'),
				'input_type'		=> 'select',
				'class'				=> 'input-small',
				'holder_class'		=> '',				
				'selector'			=> $item_selector.' .rhc-widget-time',
				'options'			=> array(
					''				=> '',
					'inline-block'	=> __('Show','rhc'),
					'none'			=> __('Hide','rhc')
				),
				'property'			=> 'display',
				'real_time'			=> true
			);	
					
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> $box_prefix.'_date_time_',
			'selector'	=> $item_selector.' .rhc-widget-time',
			'labels'	=> (object)array(
				'family'	=> __('Time font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	

		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= $box_prefix.'-description'; 
		$t[$i]->label 		= __('Description','rhc');
		$t[$i]->options = array();	
		
		$t[$i]->options[]=(object)array(
				'id'				=> $box_prefix.'_desc_show',
				'type'				=> 'css',
				'label'				=> __('Description visibility','rhcss'),
				'input_type'		=> 'select',
				'class'				=> 'input-small',
				'holder_class'		=> '',				
				'selector'			=> $item_selector.' .rhc-description',
				'options'			=> array(
					''				=> '',
					'inline-block'	=> __('Show','rhc'),
					'none'			=> __('Hide','rhc')
				),
				'property'			=> 'display',
				'real_time'			=> true
			);			

		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> $box_prefix.'_desc_font_',
			'selector'	=> $item_selector.' .rhc-description',
			'labels'	=> (object)array(
				'family'	=> __('Description font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));		
		


//---------
		
		$label = array(
		
		);
		$selector = $item_selector.' .rhc-widget-upcoming-featured-image img';
//---------
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= $box_prefix.'-image'; 
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
				'selector'			=> $selector,
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
				'selector'			=> $selector,
				'property'			=> 'background-color',
				'other_options'		=> array(
					'transparent'	=> 'transparent'
				),				
				'real_time'			=> true
			);		
			
		$t[$i]->options = $this->add_border_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_image_border',
			'selector'	=> $selector
		));		
		
		$t[$i]->options = $this->add_border_radius_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_image_radius',
			'selector'	=> $selector
		));		
//---------
		
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= $box_prefix.'-item'; 
		$t[$i]->label 		= __('Item','rhc');
		$t[$i]->options = array();			
		$t[$i]->options = $this->add_padding_options($t[$i]->options,array(
			'prefix'	=> $box_prefix.'_item_pad',
			'selector'	=> $item_selector
		));	

endif;
?>