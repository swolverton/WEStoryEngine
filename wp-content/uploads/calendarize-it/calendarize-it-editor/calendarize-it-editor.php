<?php

/**
Plugin Name: Calendarize It! CSS Editor
Plugin URI: http://plugins.righthere.com/calendarize-it/
Description: CSS Editor for Calendarize It!
Version: 1.0.1 rev 36681
Author: Alberto Lau (RightHere LLC)
Author URI: http://plugins.righthere.com
 **/

if(defined('RHCE_PATH')) throw new Exception( __('A duplicate of this addon/plugin is already active.','rhc') );
 
if(defined('RHC_ADDON_PATH')){
	define('RHCE_PATH', trailingslashit(RHC_ADDON_PATH . dirname($addon)) ); 
	define("RHCE_URL", trailingslashit(RHC_ADDON_URL . dirname($addon)) );
}else{
	define('RHCE_PATH', plugin_dir_path(__FILE__) ); 
	define("RHCE_URL", plugin_dir_url(__FILE__) );
} 
 
class plugin_calendarizeit_editor {
	var $id = 'rhce';
	var $righthere_css_version='1.0.0';
	var $options_varname = 'rhc_options';
	function plugin_calendarizeit_editor(){		
		// Integration point #1
		// Register the bundled righthere_css module.
		require_once RHCE_PATH.'righthere-css/load.php';//this file contains the same as load.pop.php from options panel, so only one needs be loaded.
		rh_register_php('righthere-css',RHCE_PATH.'righthere-css/class.module_righthere_css.php', $this->righthere_css_version);		
		rh_register_php('righthere-css-frontend',RHCE_PATH.'righthere-css/class.righthere_css_frontend.php', $this->righthere_css_version);	
		rh_register_php('rh-google-fonts-admin',RHCE_PATH.'righthere-css/class.google_web_fonts_admin.php', '1.0.0');
		rh_register_php('rh-functions', RHCE_PATH.'righthere-css/rh-functions.php', '1.0.0');
		rh_register_php('rh-edit-admin-bar',RHCE_PATH.'righthere-css/class.admin_bar_editor_access.php', $this->righthere_css_version);	
		rh_register_php('rhcss-options',RHCE_PATH.'righthere-css/class.rhcss_pop_options.php', $this->righthere_css_version);		
		//-----------
		$this->options = get_option($this->options_varname);
		$this->options = is_array($this->options)?$this->options:array();				
		//-----------
		
		add_action('plugins_loaded',array(&$this,'plugins_loaded'),9);
		
	}
	
	function plugins_loaded(){
		if(!defined('RHC_VERSION'))return '';//calendarize-it is not active. do nothing.
		global $rhc_plugin;
		// Integration point #2
		//usually por loading pop-panel, but now also loads the css editor module.
		do_action('rh-php-commons');	
	
		new google_web_fonts_admin(array(
			'path' 	=> RHCE_PATH.'righthere-css/',
			'url'	=> RHCE_URL.'righthere-css/'		
		));
		
		//load the frontend output
		new righthere_css_frontend();	
	
		$editor_enabled = $rhc_plugin->get_option('enable_css_editor','1',true);
		$editor_debug = $rhc_plugin->get_option('enable_css_editor_debug','',true);
		$editor_debug = '1'==$editor_debug?true:false;
		
		$bootstrap_in_footer = $rhc_plugin->get_option('bootstrap_in_footer','',true);
		$bootstrap_in_footer = '1'==$bootstrap_in_footer ? true : false;
		
		if($editor_enabled){
			// Integration point #3
			// Include the integration class by the current plugin
			require_once RHCE_PATH.'includes/class.rhcss_editor_calendar_frame.php';		
			$settings = array(
				'url'						=> RHCE_URL.'righthere-css/',
				'path'						=> RHCE_PATH.'righthere-css/',
				'plugin_id'					=> 'rhc',
				'version'					=> '1.0.0',
				'capability'				=> 'manage_options',
				'options_varname'			=> $this->options_varname,
				'cb_get_option'				=> array(&$this,'get_option'),
				'resources_path'			=> 'calendarize-it',			
				'file_queue_options_name' 	=> 'rhc_queue',
				'upload_limit_per_index'	=> 20,		
				'debug'						=> $editor_debug,
				'detect_selector'			=> '.rhcalendar.not-widget',
				//--
				'id'						=> 'rhc',
				'trigger_var'				=> 'rhc_edit',
				'trigger_val'				=> 'calendar',
				'bootstrap_in_footer'		=> $bootstrap_in_footer
			);
			new rhcss_editor_calendar_frame($settings);		
			
			//----- Include a second editable content
			require_once RHCE_PATH.'includes/class.rhcss_editor_all_views.php';	
			//$settings['id']			='all_views';
			$settings['section']	='all_views';
			$settings['trigger_val']='all_views';
			new rhcss_editor_all_views($settings);
			
			//----- Include a second editable content
			require_once RHCE_PATH.'includes/class.rhcss_editor_month_view.php';	
			//$settings['id']			='month_view';
			$settings['section']	='month_view';
			$settings['trigger_val']='month_view';
			new rhcss_editor_month_view($settings);
			
			//------
			require_once RHCE_PATH.'includes/class.rhcss_editor_agenda_view.php';	
			//$settings['id']			='agenda_view';
			$settings['section']	='agenda_view';
			$settings['trigger_val']='agenda_view';
			new rhcss_editor_agenda_view($settings);
			
			//------
			require_once RHCE_PATH.'includes/class.rhcss_editor_event_list.php';	
			//$settings['id']			='agenda_view';
			$settings['section']	='event_list';
			$settings['trigger_val']='event_list';
			new rhcss_editor_event_list($settings);
			
			//------
			require_once RHCE_PATH.'includes/class.rhcss_editor_detail_box.php';	
			$settings['section']		='detail_box';
			$settings['trigger_val']	='detail_box';
			$settings['detect_selector']='body';
			new rhcss_editor_detail_box($settings);
			
			//------
			require_once RHCE_PATH.'includes/class.rhcss_editor_event_page.php';	
			$settings['section']		='event_page';
			$settings['trigger_val']	='event_page';
			$settings['detect_selector']='body';
			new rhcss_editor_event_page($settings);
			
			//------
			require_once RHCE_PATH.'includes/class.rhcss_editor_event_page_dbox.php';	
			$settings['section']		='event_page_dbox';
			$settings['trigger_val']	='event_page_dbox';
			$settings['detect_selector']='body';
			new rhcss_editor_event_page_dbox($settings);
			
			//------
			require_once RHCE_PATH.'includes/class.rhcss_editor_event_page_vbox.php';	
			$settings['section']		='event_page_vbox';
			$settings['trigger_val']	='event_page_vbox';
			$settings['detect_selector']='body';
			new rhcss_editor_event_page_vbox($settings);
			
			//------
			require_once RHCE_PATH.'includes/class.rhcss_editor_venue_page.php';	
			//$settings['id']			='agenda_view';
			$settings['section']	='venue_page';
			$settings['trigger_val']='venue_page';
			$settings['detect_selector']='body';
			new rhcss_editor_venue_page($settings);
			
			//------
			require_once RHCE_PATH.'includes/class.rhcss_editor_organizer_page.php';	
			//$settings['id']			='agenda_view';
			$settings['section']	='organizer_page';
			$settings['trigger_val']='organizer_page';
			$settings['detect_selector']='body';
			new rhcss_editor_organizer_page($settings);
			
			//------
			require_once RHCE_PATH.'includes/class.rhcss_widget_upcoming_default.php';	
			//$settings['id']			='agenda_view';
			$settings['section']	='rhcw_upcoming_default';
			$settings['trigger_val']='rhcw_upcoming_default';
			$settings['detect_selector']='body';
			new rhcss_widget_upcoming_default($settings);
			
			//------
			require_once RHCE_PATH.'includes/class.rhcss_widget_upcoming_agenda.php';	
			$settings['section']	='rhcw_upcoming_agenda';
			$settings['trigger_val']='rhcw_upcoming_agenda';
			$settings['detect_selector']='body';
			new rhcss_widget_upcoming_agenda($settings);
			
			//------
			require_once RHCE_PATH.'includes/class.rhcss_widget_upcoming_agenda_b.php';	
			$settings['section']	='rhcw_upcoming_agenda_b';
			$settings['trigger_val']='rhcw_upcoming_agenda_b';
			$settings['detect_selector']='body';
			new rhcss_widget_upcoming_agenda_b($settings);
			
			
			
		}	

		//option fields and admin bar links
		require_once RHCE_PATH.'includes/class.rhc_css_options.php';
		new rhc_css_options(array('plugin_id'=>'rhc-css','admin_bar'=>$editor_enabled));			


		
		// Integration point #5
		// add tab to options panel
		if(is_admin()){
			//Creates the CSS Editor tab in the calendarize-it options
			new rhcss_pop_options('rhc-css','manage_options',true);
			
			//--- create a separate menu for CSS Editor
			$settings = array(				
				'id'					=> 'rhc-css',
				'plugin_id'				=> 'rhc',
				'capability'			=> 'manage_options',
				'options_varname'		=> $rhc_plugin->options_varname,
				'menu_id'				=> 'rhc-css-options',
				'page_title'			=> __('CSS Editor','rhc'),
				'menu_text'				=> __('CSS Editor','rhc'),
				'option_menu_parent'	=> 'edit.php?post_type='.RHC_EVENTS,
				//'option_menu_parent'	=> $this->id,
				'notification'			=> (object)array(
					'plugin_version'=> RHC_VERSION,
					'plugin_code' 	=> 'RHC',
					'message'		=> __('Calendar plugin update %s is available! <a href="%s">Please update now</a>','rch')
				),
				'theme'					=> false,
				'stylesheet'			=> 'rhc-options',
				'option_show_in_metabox'=> true,
				'path'			=> RHC_PATH.'options-panel/',
				'url'			=> RHC_URL.'options-panel/',
				'pluginslug'	=> RHC_SLUG,
				//'api_url' 		=> "http://localhost"
				'api_url' 		=> "http://plugins.righthere.com"
			);			
			new PluginOptionsPanelModule($settings);			
		}
		
	}

	function get_option($name,$default='',$default_if_empty=false){
		$value = isset($this->options[$name])?$this->options[$name]:$default;
		if($default_if_empty){
			$value = ''==$value?$default:$value;
		}
		return $value;
	}	
	
	function update_options($options){
		update_option($this->options_varname,$options);
	}		
}

new plugin_calendarizeit_editor();
?>