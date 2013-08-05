<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/
class rhc_tax_settings {
	function rhc_tax_settings($plugin_id='rhc'){
		//$this->id = $plugin_id.'-log';
		$this->id = $plugin_id;
		add_filter("pop-options_{$this->id}",array(&$this,'options'),10,1);			
	}
	
	function options($t){
		$i = count($t);
		//-------------------------
		$default_taxonomies = array(
			RHC_CALENDAR 	=> __('Calendar','rhc'),
			RHC_ORGANIZER	=> __('Organizer','rhc'),
			RHC_VENUE		=> __('Venues','rhc')
		);
		
		$taxonomies = apply_filters('rhc-taxonomies',$default_taxonomies);
		//-------------------------	
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'cal-tax'; 
		$t[$i]->label 		= __('Calendarize taxonomies','rhc');
		$t[$i]->right_label	= __('Calendarize taxonomies','rhc');
		$t[$i]->page_title	= __('Calendarize taxonomies','rhc');
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		$t[$i]->options = array();		

		$t[$i]->options[]=(object)array(
			'id'		=> 'tax-description',
			'type'		=> 'subtitle',
			'label'		=> __('Enable calendarize-it taxonomies','rhc'),
			'description'=> __("Check the post type name for wich you want to enable the custom taxonomy.",'rhc')
		);
		
		//--------------
		$post_types=array();
		foreach(get_post_types(array(/*'public'=> true,'_builtin' => false*/),'objects','and') as $post_type => $pt){
			if(in_array($post_type,array('revision','nav_menu_item')))continue;
			$post_types[$post_type]=$pt;
		} 
		$post_types = apply_filters('calendarize_taxonomy_post_type_options',$post_types);
		//--------------	

		if(count($post_types)==0){
			$t[$i]->options[]=(object)array(
				'id'=>'no_ctypes',
				'type'=>'description',
				'description'=>__("There are no additional Post Types to enable.",'rhc')
			);
		}else{
			foreach($taxonomies as $taxonomy => $taxonomy_label){
				$t[$i]->options[]=(object)array(
					'type'=>'subtitle',
					'label'=> $taxonomy_label
				);
				
				$j=0;
				foreach($post_types as $post_type => $pt){
					$tmp=(object)array(
						'id'	=> $taxonomy.'_post_types_'.$post_type,
						'name'	=> $taxonomy.'_post_types[]',
						'type'	=> 'checkbox',
						'option_value'=>$post_type,
						'label'	=> (@$pt->labels->name?$pt->labels->name:$post_type),
						'el_properties' => array(),
						'save_option'=>true,
						'load_option'=>true
					);
//					if($j==0){
//						$tmp->description = __("Calendarize taxonomies (Calendar, Organizer, Venues, Addons)can be enabled for other post types.  Check the post types, where you want the taxonomy to be enabled.",'rhc');
//						$tmp->description_rowspan = count($post_types);
//					}
					$t[$i]->options[]=$tmp;
					$j++;
				}			
			}

		}

		$t[$i]->options[]=(object)array(
				'type'=>'clear'
			);
			
		$t[$i]->options[]=(object)array(
			'type'=>'subtitle',
			'label'=> __('Disable calendarize taxonomies','rhc'),
			'description'=>__('Use this options if you want to disable some of the calendarize-it built in taxonomies.','rhc')
		);
		
		foreach($default_taxonomies as $taxonomy => $label){
			$t[$i]->options[] = (object)array(
				'id'		=> 'disable_'.$taxonomy,
				'label'		=> sprintf(__('Disable %s taxonomy','wlb'),$label),
				'type'		=> 'yesno',
				'default'	=> '0',
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
			);	
		
		}

		$t[$i]->options[]=(object)array(
				'type'=>'clear'
			);
			
		$t[$i]->options[]=(object)array(
				'type'	=> 'submit',
				'label'	=> __('Save','rhc'),
				'class' => 'button-primary'
			);
				
		return $t;
	}
}
?>