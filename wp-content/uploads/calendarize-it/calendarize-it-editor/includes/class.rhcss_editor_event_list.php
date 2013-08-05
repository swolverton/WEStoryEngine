<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/

class rhcss_editor_event_list extends module_righthere_css{
	function rhcss_editor_event_list($args=array()){
		$args['cb_init']=array(&$this,'cb_init');
		return $this->module_righthere_css($args);
	}
	
	function cb_init(){
		//called on the head when editor is active.
		
?>
<script>
jQuery(document).ready(function($){
	if( $('.fc-button-rhc_event').length>0 ){
		$('.fc-button-rhc_event').trigger('click');
	}
});

get_css_value_callbacks.rhcel_dl_align = function(inp, sel, arg){
	var value = '';
	var found = false;
	jQuery.rule(sel).each(function(i,o){
		if( !found && o.style[0]=='margin-top' ){
			found=true;
			value = (o.style.marginTop=='auto'?'auto':parseInt(o.style.marginTop)) + ' ' + (o.style.marginRight=='auto'?'auto':parseInt(o.style.marginRight)) + ' ' + (o.style.marginBottom=='auto'?'auto':parseInt(o.style.marginBottom)) + ' ' + (o.style.marginLeft=='auto'?'auto':parseInt(o.style.marginLeft));
		}
	});
	return value;
}
</script>
<?php 
	}
	
	function options($t=array()){
		$i = count($t);

		//-- Date label side
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhce_dlside'; 
		$t[$i]->label 		= __('Date label sides','rhc');
		$t[$i]->options = array(
			(object)array(//This is needed because we are unable to read the css value from :after and :before
				'input_type' => 'raw_html',
				'html'=> '<div id="rhce_dlside_border_style_helper" ></div>'
			),	
			(object)array(
				'id'				=> 'rhce_dlside_border_color',
				'type'				=> 'css',
				'label'				=> __('Border color','rhc'),
				'input_type'		=> 'color_or_something_else',
				'holder_class'		=> '',
				'opacity'			=> true,
				'btn_clear'			=> true,
				'selector'			=> '.fc-event-list-date:before,.fc-event-list-date:after,#rhce_dlside_border_style_helper',
				'property'			=> 'border-bottom-color',
				'other_options'		=> array(
					'transparent'	=> 'transparent'
				),				
				'real_time'			=> true
			),			
			(object)array('input_type'=>'grid_start'),
			(object)array(
				'id'				=> 'rhce_dlside_border_style',
				'type'				=> 'css',
				'label'				=> __('Border style','rhcss'),
				'input_type'		=> 'select',
				'class'				=> 'input-small',
				'holder_class'		=> 'span6 ',				
				'selector'			=> '.fc-event-list-date:before,.fc-event-list-date:after,#rhce_dlside_border_style_helper',
				'options'			=> array(
					''		=> '',
					'none'	=> 'none',
					'solid'	=> 'solid',
					'double'=> 'double',
					'dotted'=> 'dotted',
					'groove'=> 'groove',
					'inset'=> 'inset',
					'outset'=> 'outset',
					'ridge'=> 'ridge'
				),
				'property'			=> 'border-bottom-style',
				'real_time'			=> true
			),		
			(object)array(
				'id'				=> 'rhce_dlside_border_size',
				'type'				=> 'css',
				'label'				=> __('Width','rhc'),
				'input_type'		=> 'number',
				//'input_type'		=> 'element_size',
				'unit'				=> 'px',
				'class'				=> '',
				'holder_class'		=> 'span6',
				'class'				=> 'input-mini',
				'min'				=> '0',
				'max'				=> '10',
				'step'				=> '1',
				'selector'			=> '.fc-event-list-date:before,.fc-event-list-date:after,#rhce_dlside_border_style_helper',
				'property'			=> 'border-bottom-width',
				'real_time'			=> true
			),			
			(object)array('input_type'=>'grid_end')	
		);	
		//-- Day labels --------------------------------			
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhcel_date_label'; 
		$t[$i]->label 		= __('Date label','rhc');
		$t[$i]->options = array();		
			
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhc_event_list_date_label',
			'selector'	=> '.rhcalendar.not-widget .fc-events-holder .fc-event-list-holder h3.fc-event-list-date-header',
			'labels'	=> (object)array(
				'family'	=> __('Font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	
		$t[$i]->options[]=(object)array('input_type'=>'grid_start');
		$t[$i]->options[]=	(object)array(
				'id'				=> 'rhcel_date_label_width',
				'type'				=> 'css',
				'label'				=> __('Width','rhc'),
				'input_type'		=> 'number',
				//'input_type'		=> 'element_size',
				'unit'				=> '%',
				'class'				=> '',
				'holder_class'		=> 'span6',
				'class'				=> 'input-mini',
				'min'				=> '35',
				'max'				=> '100',
				'step'				=> '1',
				'selector'			=> '.rhcalendar.not-widget .fc-events-holder .fc-event-list-holder h3.fc-event-list-date-header',
				'property'			=> 'width',
				'real_time'			=> true
			);		
			
		$t[$i]->options[]=(object)array(
				'id'				=> 'rhcel_dl_align',
				'type'				=> 'css',
				'label'				=> __('Position','rhcss'),
				'input_type'		=> 'select',
				'class'				=> 'input-small',
				'holder_class'		=> 'span6',				
				'selector'			=> '#rhcel_dl_align_helper,.fc-events-holder .fc-event-list-holder h3.fc-event-list-date-header',
				'options'			=> array(
					''				=>	'',
					'0 auto 0 auto'	=>	__('Center','rhc'),
					'0 0 0 auto'	=>__('Right','rhc'),
					'0 0 0 0'		=> __('Left','rhc')
				),
				'property'			=> 'margin',
				'cb_get_css_value'	=> 'rhcel_dl_align',
				'real_time'			=> true
			);
		/*	
		$t[$i]->options[]=(object)array(//for some reason $.rule is not returning margin
				'input_type' => 'raw_html',
				'html'=> '<div id="rhcel_dl_align_helper" style="display:none;"></div>'
			);
		*/				
		$t[$i]->options[]=(object)array('input_type'=>'grid_end');
//-----------		
		$t[$i]->options = $this->add_padding_options($t[$i]->options,array(
			'prefix'	=> 'rhcel_date_label',
			'selector'	=> '.rhcalendar.not-widget .fc-events-holder .fc-event-list-holder h3.fc-event-list-date-header'
		));					
//----------------

	
		//-- Date label border
		$prefix = 'rhcel_dl_border';
		$label=array(
			'color'	=> __('Border color','rhc'),
			'style' => __('Border style','rhc'),
			'size'	=> __('Width','rhc')
		);	
		$selector = '.rhcalendar.not-widget .fc-events-holder .fc-event-list-holder h3.fc-event-list-date-header';
		$style_options = array(
					''		=> '',
					'none'	=> 'none',
					'solid'	=> 'solid',
					'double'=> 'double',
					'dotted'=> 'dotted',
					'groove'=> 'groove',
					'inset'=> 'inset',
					'outset'=> 'outset',
					'ridge'=> 'ridge'
				);
		$property = array(
			'color'	=> 'border-color',
			'style' => 'border-style',
			'size'	=> 'border-width'
		);
				
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhcel_dl_border'; 
		$t[$i]->label 		= __('Date label border','rhc');
		$t[$i]->options = array(
			(object)array(
				'id'				=> $prefix.'_color',
				'type'				=> 'css',
				'label'				=> $label['color'],
				'input_type'		=> 'color_or_something_else',
				'holder_class'		=> '',
				'opacity'			=> true,
				'btn_clear'			=> true,
				'selector'			=> $selector,
				'property'			=> $property['color'],
				'other_options'		=> array(
					'transparent'	=> 'transparent'
				),				
				'real_time'			=> true
			),			
			(object)array('input_type'=>'grid_start'),
			(object)array(
				'id'				=> $prefix.'_style',
				'type'				=> 'css',
				'label'				=> $label['style'],
				'input_type'		=> 'select',
				'class'				=> 'input-small',
				'holder_class'		=> 'span6 ',				
				'selector'			=> $selector,
				'options'			=> $style_options,
				'property'			=> $property['style'],
				'real_time'			=> true
			),		
			(object)array(
				'id'				=> $prefix.'_size',
				'type'				=> 'css',
				'label'				=> $label['size'],
				'input_type'		=> 'number',
				'unit'				=> 'px',
				'class'				=> '',
				'holder_class'		=> 'span6',
				'class'				=> 'input-mini',
				'min'				=> '0',
				'max'				=> '100',
				'step'				=> '1',
				'selector'			=> $selector,
				'property'			=> $property['size'],
				'real_time'			=> true
			),			
			(object)array('input_type'=>'grid_end')	
		);		
		$t[$i]->options = $this->add_border_radius_options($t[$i]->options,array(
			'prefix'	=> 'rhcel_dlabel',
			'selector'	=> '.rhcalendar.not-widget .fc-events-holder .fc-event-list-holder h3.fc-event-list-date-header'
		));
		//-- Day labels bg--------------------------------			
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhcel_dlbg'; 
		$t[$i]->label 		= __('Date label background','rhc');
		$t[$i]->options = array();		

//----------------
		$t[$i]->options = $this->add_backgroud_options( $t[$i]->options, array(
			'label'		=> __('Cell Background','rhc'),
			'prefix'	=> 'rhcel_dl_bg_bg',
			'selector'	=> '.rhcalendar.not-widget .fc-events-holder .fc-event-list-holder h3.fc-event-list-date-header'	
		));				
			
		//-- Event title
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc_event_list_etitle'; 
		$t[$i]->label 		= __('Event title','rhc');
		$t[$i]->options = array();		
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhc_event_list_etitle_font',
			'selector'	=> '.rhcalendar.not-widget .fc-event-list-content h4 .fc-event-list-title',
			'labels'	=> (object)array(
				'family'	=> __('Font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	
		$t[$i]->options = $this->add_margin_options($t[$i]->options,array(
			'prefix'	=> 'rhcel_etitle',
			'selector'	=> '.fc-event-list-holder .fc-event-list-content h4'
		));				
		//-- Event container
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhcel_event_cont'; 
		$t[$i]->label 		= __('Event container','rhc');
		$t[$i]->options = array();	
		$t[$i]->options = $this->add_padding_options($t[$i]->options,array(
			'prefix'	=> 'rhcel_event_cont',
			'selector'	=> '.fc-events-holder .fc-event-list-holder .fc-event-list-item'
		));	
/*			
		//-- Detail box image
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhcel_dbox'; 
		$t[$i]->label 		= __('Details box','rhc');
		$t[$i]->options = array();
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhcel_dbox_title_',
			'selector'	=> '.rhcalendar.not-widget .fe-extrainfo-holder .fe-cell-label',
			'labels'	=> (object)array(
				'family'	=> __('Title font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhcel_dbox_labels_',
			'selector'	=> '.rhcalendar.not-widget .fc-event-list-content .rhc-info-cell .fe-extrainfo-label',
			'labels'	=> (object)array(
				'family'	=> __('Labels font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhcel_dbox_values_',
			'selector'	=> '.rhcalendar.not-widget .fc-event-list-content .rhc-info-cell .fe-extrainfo-value',
			'labels'	=> (object)array(
				'family'	=> __('Values font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));		
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhcel_dbox_link_values_',
			'selector'	=> '.rhcalendar.not-widget .fc-event-list-content .rhc-info-cell a',
			'labels'	=> (object)array(
				'family'	=> __('Values link font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));		
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhcel_dbox_desc_',
			'selector'	=> '.rhcalendar.not-widget .fc-event-list-content .fc-event-list-description',
			'labels'	=> (object)array(
				'family'	=> __('Description font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));			
		//-- Detail box image
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhcel_dbox_img'; 
		$t[$i]->label 		= __('Details box image','rhc');
		$t[$i]->options = array();
		$t[$i]->options[] = (object)array('input_type'=>'grid_start');
		$t[$i]->options[] = (object)array(
				'id'				=> 'rhcel_dbox_img_margin_top',
				'type'				=> 'css',
				'label'				=> __('Margin top','rhcss'),
				'input_type'		=> 'number',
				'unit'				=> 'px',
				'class'				=> 'input-mini',
				'holder_class'		=> 'span6',
				'min'				=> 0,
				'max'				=> 100,
				'step'				=> 1,
				'selector'			=> '.rhcalendar.not-widget .fc-event-list-holder .fc-event-list-featured-image',
				'property'			=> 'margin-top',
				'real_time'			=> true
			);
		$t[$i]->options[] = (object)array(
				'id'				=> 'rhcel_dbox_img_margin_right',
				'type'				=> 'css',
				'label'				=> __('Margin right','rhcss'),
				'input_type'		=> 'number',
				'unit'				=> 'px',
				'class'				=> 'input-mini',
				'holder_class'		=> 'span6',
				'min'				=> 0,
				'max'				=> 100,
				'step'				=> 1,
				'selector'			=> '.rhcalendar.not-widget .fc-event-list-holder .fc-event-list-featured-image',
				'property'			=> 'margin-right',
				'real_time'			=> true
			);
		$t[$i]->options[] = (object)array('input_type'=>'grid_end');
		$t[$i]->options[] = (object)array(
				'id'				=> 'rhcel_dbox_img_border_color',
				'type'				=> 'css',
				'label'				=> __('Border color','rhc'),
				'input_type'		=> 'color_or_something_else',
				'holder_class'		=> '',
				'opacity'			=> true,
				'btn_clear'			=> true,
				'selector'			=> '.rhcalendar.not-widget .fc-event-list-holder .fe-extrainfo-container .fe-image-holder img',
				'property'			=> 'border-color',
				'other_options'		=> array(
					'transparent'	=> 'transparent'
				),				
				'real_time'			=> true
			);
		$t[$i]->options[] = (object)array(
				'id'				=> 'rhcel_dbox_img_bg_color',
				'type'				=> 'css',
				'label'				=> __('Background color','rhc'),
				'input_type'		=> 'color_or_something_else',
				'holder_class'		=> '',
				'opacity'			=> true,
				'btn_clear'			=> true,
				'selector'			=> '.rhcalendar.not-widget .fc-event-list-holder .fe-extrainfo-container .fe-image-holder img',
				'property'			=> 'background-color',
				'other_options'		=> array(
					'transparent'	=> 'transparent'
				),				
				'real_time'			=> true
			);
				
		//-- Detail box background
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhcel_dbox_bg'; 
		$t[$i]->label 		= __('Details box background','rhc');
		$t[$i]->options = array();	
		$t[$i]->options = $this->add_backgroud_options( $t[$i]->options, array(
			'label'		=> __('Detail box background','rhc'),
			'prefix'	=> 'rhcel_dbox_bg_',
			'selector'	=> '.rhcalendar.not-widget .fc-event-list-content .fe-extrainfo-container2',
			'derived_color'=> array(
						array(
							'type'	=> 'color_darken',
							'val'	=> '1',
							'sel'	=> ".rhcalendar.not-widget .fc-event-list-content .fe-extrainfo-container",
							'arg'	=> array(
								(object)array(
									'name' => 'border-color',
									'tpl'	=>'__value__'
								)
							)
						),
						array(
							'type'	=> 'color_darken',
							'val'	=> '-12',
							'sel'	=> ".rhcalendar.not-widget .fc-event-list-content .fe-extrainfo-container",
							'arg'	=> array(
								(object)array(
									'name' => 'background-color',
									'tpl'	=>'__value__'
								)
							)
						)
					)				
		));		
*/	
		//---- organizer box -----	
		$box_prefix = 'rhcdb';
		$box_selector = '.elist-dbox';	
		$with_image = true;
		$label = array(
			'outer'=>__('Detail box container','rhc')
		);
		include 'dbox_options.php';	
		//------
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