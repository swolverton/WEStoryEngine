<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/

class rhc_event_image_metaboxes {
	var $uid=0;
	var $post_type;
	var $debug=false;
	function rhc_event_image_metaboxes($post_type=RHC_EVENTS,$debug=false){
		$this->debug = $debug;
		if(!class_exists('post_meta_boxes'))
			require_once('class.post_meta_boxes.php');		
		$this->post_type = $post_type;

		$this->post_meta_boxes = new post_meta_boxes(array(
			'post_type'=>$post_type,
			'options'=>$this->metaboxes(),
			'styles'=>array('rhc-admin'),
			'scripts'=>array('rhc-admin'),
			'metabox_meta_fields' =>  'image_meta_fields',
			'pluginpath'=>RHC_PATH
		));
		$this->post_meta_boxes->save_fields = array('rhc_top_image','rhc_dbox_image','rhc_tooltip_image');
	}
	
	function metaboxes($t=array()){
		$i = count($t);
		//------------------------------		
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc_tooltip_image_mbox'; 
		$t[$i]->label 		= __('Event Featured Image','rhc');
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		$t[$i]->context = 'side';
		$t[$i]->priority = 'low';
		$t[$i]->options = array(
			(object)array(
				'id'			=> 'rhc_tooltip_image',
				'type'			=> 'wp_uploader',
				'name'			=> 'rhc_tooltip_image',
				'set_label'		=>  __('Set Event Featured Image','rhc'),
				'unset_label'	=>  __('Remove Featured Event Image','rhc'),
				'modal_title'	=> __('Set Event Featured Image','rhc'),
				'modal_button'	=> __('Set Event Featrued Image','rhc'),
				'save_option'	=> true,
				'load_option'	=> true
			),		
			(object)array(
				'type'=>'clear'
			)
		);	
		//------------------------------		
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc_top_image'; 
		$t[$i]->label 		= __('Event Page Top Image','rhc');
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		$t[$i]->context = 'side';
		$t[$i]->priority = 'low';
		$t[$i]->options = array(
			(object)array(
				'id'			=> 'rhc_top_image',
				'type'			=> 'wp_uploader',
				'name'			=> 'rhc_top_image',
				'set_label'		=>  __('Set Event Page Top Image','rhc'),
				'unset_label'	=>  __('Remove Event Page Top Image','rhc'),
				'modal_title'	=> __('Set Event Page Top Image','rhc'),
				'modal_button'	=> __('Set Event Page Top Image','rhc'),
				'save_option'	=> true,
				'load_option'	=> true
			),		
			(object)array(
				'type'=>'clear'
			)
		);		
		//------------------------------
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc_dbox_image_mbox'; 
		$t[$i]->label 		= __('Event Detail Box Image','rhc');
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		$t[$i]->context = 'side';
		$t[$i]->priority = 'low';
		$t[$i]->options = array(
			(object)array(
				'id'			=> 'rhc_dbox_image',
				'type'			=> 'wp_uploader',
				'name'			=> 'rhc_dbox_image',
				'set_label'		=>  __('Set Event Detail Box Image','rhc'),
				'unset_label'	=>  __('Remove Event Detail Box Image','rhc'),
				'modal_title'	=> __('Set Event Detail Box Image','rhc'),
				'modal_button'	=> __('Set Event Detail Box Image','rhc'),
				'save_option'	=> true,
				'load_option'	=> true
			),		
			(object)array(
				'type'=>'clear'
			)
		);			
		//----- 
		return $t;
	}	
}
?>