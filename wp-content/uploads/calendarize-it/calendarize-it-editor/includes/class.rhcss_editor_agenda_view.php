<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/

class rhcss_editor_agenda_view extends module_righthere_css{
	function rhcss_editor_agenda_view($args=array()){
		$args['cb_init']=array(&$this,'cb_init');
		return $this->module_righthere_css($args);
	}

	function cb_init(){
		//called on the head when editor is active.
		
?>
<script>
jQuery(document).ready(function($){
	if( $('.fc-button-agendaWeek').length>0 ){
		$('.fc-button-agendaWeek').trigger('click');
	}else if( $('.fc-button-agendaDay').length>0 ){
		$('.fc-button-agendaDay').trigger('click');
	}
});
</script>
<?php 
	}
	
	function options($t=array()){
		$i = count($t);
		//-- Background
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-agendaall-background'; 
		$t[$i]->label 		= __('Background','rhc');
		$t[$i]->options = array();	
		$t[$i]->options = $this->add_backgroud_options( $t[$i]->options, array(
			'label'		=> __('Background','rhc'),
			'prefix'	=> 'rhc_agendaview_bg',
			'selector'	=> '.rhcalendar.not-widget .fc-view.fc-agenda'	
		));			
			

		//-- Top cell --------------------------------			
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-agenda-top'; 
		$t[$i]->label 		= __('Day label','rhc');
		$t[$i]->options = array();		
			
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhc_month_header_font',
			//'selector'	=> '.rhcalendar.not-widget .fc-agenda .fc-widget-header',
			'selector'	=> '.rhcalendar.not-widget .fc-agenda.fc-view .fc-first .fc-widget-header',
			'labels'	=> (object)array(
				'family'	=> __('Day font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));	
		$t[$i]->options = $this->add_backgroud_options( $t[$i]->options, array(
			'label'		=> __('Cell Background','rhc'),
			'prefix'	=> 'rhc_agendaview_day_label_bg',
			'selector'	=> '.rhcalendar.not-widget .fc-view.fc-agenda thead .fc-first .fc-widget-header'/*,
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
		//-- Vertical axis
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-agenda-vaxis'; 
		$t[$i]->label 		= __('Vertical label','rhc');
		$t[$i]->options = array();	
		$t[$i]->options = $this->add_font_options( $t[$i]->options, array(
			'prefix'	=> 'rhc_agenda_vaxis_font',
			'selector'	=> '.rhcalendar.not-widget .fc-agenda .fc-widget-header.fc-agenda-axis',
			'labels'	=> (object)array(
				'family'	=> __('Vertical label font','rhc'),
				'size'		=> __('Size','rhc'),
				'color'		=> __('Color','rhc')				
			)
		));
		$t[$i]->options = $this->add_backgroud_options( $t[$i]->options, array(
			'label'		=> __('Background','rhc'),
			'prefix'	=> 'rhc_agenda_vaxis_bg',
			'selector'	=> '.rhcalendar.not-widget .fc-agenda .fc-widget-header.fc-agenda-axis'
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