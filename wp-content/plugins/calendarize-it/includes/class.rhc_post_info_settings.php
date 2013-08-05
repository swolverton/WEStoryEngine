<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/
class rhc_post_info_settings {
	var $post_type = RHC_EVENTS;
	function rhc_post_info_settings($plugin_id='rhc'){
		$this->id = $plugin_id;
		add_filter("pop-options_{$this->id}",array(&$this,'options'),10,1);		
		add_action('pop_handle_save',array(&$this,'pop_handle_save'),50,1);	
	}

	function pop_handle_save($pop){
		global $rhc_plugin;
		if($rhc_plugin->options_varname!=$pop->options_varname)return;
		
		if(isset($_POST['pinfo_restore'])){
			if(current_user_can($rhc_plugin->options_capability)){
				include 'bundle_default_custom_fields.php';
				if(!empty($postinfo_boxes)){
					//--save:
					$options = get_option($rhc_plugin->options_varname);
					$options = is_array($options)?$options:array();
					$options['postinfo_boxes']=$postinfo_boxes;
					//--
					update_option($rhc_plugin->options_varname,$options);	
				}			
			}
		}
	}
	
	function options($t){
		$i = count($t);
		//-- Permalink settings -----------------------		
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-post-info-settings'; 
		$t[$i]->label 		= __('Default event fields','rhc');
		$t[$i]->right_label	= __('Customize event fields','rhc');
		$t[$i]->page_title	= __('Default event fields','rhc');
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		$t[$i]->options = array(
			(object)array(
				'type' 			=> 'subtitle',
				'label'			=> __('Default post info fields','rhc')
			),			
			(object)array(
				'id'			=> 'datetime_format',
				'type' 			=> 'text',
				'default'		=> get_option('links_updated_date_format', __('F j, Y','rhc').' '.__('g:i a','rhc') ),
				'description'	=> __("Datetime, date and time format in this tab only applies to the start and end meta fields (start datetime, start date, start time, end datetime, end date, end time).  Because this values are rendered server side they follow the php <a href=\"http://php.net/manual/en/function.date.php\">date formatting syntax</a>.","rhc"),
				'label'			=> __('Datetime format','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'date_format',
				'type' 			=> 'text',
				'default'		=> get_option('date_format',__('F j, Y','rhc')),
				'label'			=> __('Date format','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'time_format',
				'type' 			=> 'text',
				'default'		=> get_option('time_format',__('g:i a','rhc')),
				'label'			=> __('Date format','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'		=> 'taxonomy_links',
				'label'		=> __('Taxonomies are links','rhc'),
				'description' => __('Choose yes if you want to make taxonomies hyperlinks.  Example venue will be a link to the venue page.','wlb'),
				'type'		=> 'yesno',
				'default'	=> '0',
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'type' 			=> 'subtitle',
				'label'			=> __('Restore custom fields','rhc'),
				'description'	=> sprintf('<p>%s</p><p>%s</p>',
					__('If you have overwritten the default custom fields, use this button to restore the original set.','rhc'),
					__('Observe that the default set is only used when creating new events.  Existing events will keep their current custom field layout.','rhc')
				)
			),			
			(object)array(
				'id'		=> 'pinfo_default_set',
				'label'		=> __('Default','wlb'),
				'type'		=> 'callback',
				'callback'	=> array(&$this,'render_default_set'),
				'el_properties'	=> array(),
				'save_option'=>false,
				'load_option'=>false
			)					
		);
		
		$t[$i]->options[]=(object)array(
				'type'=>'clear'
			);
		
		$t[$i]->options[]=(object)array(
				'type'	=> 'submit',
				'label'	=> __('Save','rhc'),
				'class' => 'button-primary'
			);
		//-------------------------		
		return $t;
	}
	
	function render_default_set(){
		global $rhc_plugin;
		$postinfo_boxes		= $rhc_plugin->get_option('postinfo_boxes',false,true);
		$out = sprintf('<input type="submit" name="pinfo_restore" value="%s" class="button-primary" />',htmlspecialchars(__('Restore custom fields','rhc')));
		$out.= '<div style="display:none;"><textarea>'.json_encode($postinfo_boxes).'</textarea></div>';
		return $out;
	}
}
?>