<?php

class plugin_righthere_calendar {
	var $id;
	var $tdom;
	var $plugin_code;
	var $options_varname;
	var $options;
	var $calendar_ajax;
	var $uid=0;
	var $debug_menu = false;
	var $in_footer = false;
	function plugin_righthere_calendar($args=array()){
		//------------
		$defaults = array(
			'id'				=> 'rhc',
			'tdom'				=> 'rhc',
			'plugin_code'		=> 'RHC',
			'options_varname'	=> 'rhc_options',
			'options_parameters'=> array(),
			'options_capability'=> 'manage_options',
			'license_capability'=> 'manage_options',
			'resources_path'	=> 'calendarize-it',
			'options_panel_version'	=> '2.3.2',
			'post_info_shortcode'=> 'post_info',
			'debug_menu'		=> false,
			'autoupdate'		=> true
		);
		foreach($defaults as $property => $default){
			$this->$property = isset($args[$property])?$args[$property]:$default;
		}
		//-----------
		$this->options = get_option($this->options_varname);
		$this->options = is_array($this->options)?$this->options:array();
		//-----------
		$plugins_loaded_hook = '1'==$this->get_option('ignore_wordpress_standard',false,true)?'plugins_loaded':'after_setup_theme';		
		add_action($plugins_loaded_hook,array(&$this,'plugins_loaded'));
		add_action('init',array(&$this,'init'));

		//--- taxonomy metadata support based on code by mitcho (Michael Yoshitaka Erlewine), sirzooro
		add_action('init',array(&$this,'taxonomy_metadata_wpdbfix') );
		add_action('switch_blog',array(&$this,'taxonomy_metadata_wpdbfix'));		
		//--------
		if(is_admin()){
			require_once RHC_PATH.'options-panel/load.pop.php';
			rh_register_php('options-panel',RHC_PATH.'options-panel/class.PluginOptionsPanelModule.php', $this->options_panel_version);
			rh_register_php('rh-functions', RHC_PATH.'options-panel/rh-functions.php', $this->options_panel_version);
		}
		
		add_filter('rhc-ui-theme',array(&$this,'rhc_ui_theme'),10,1);
		//--
		if('1'==$this->get_option('enable_debug',false,true)){
			$this->debug_menu = true;
		}
		//--
		add_action('plugins_loaded',array(&$this,'handle_addons_load'),5);
//--
		if('1'==$this->get_option('in_footer',false,true)){
			$this->in_footer = true;
		}		
	}
	
	function handle_addons_load(){
		$upload_dir = wp_upload_dir();
		$addons_path = $upload_dir['basedir'].'/'.$this->resources_path.'/';	
		$addons_url = $upload_dir['baseurl'].'/'.$this->resources_path.'/';	
		$addons = $this->get_option('addons',array(),true);
		if(is_array($addons)&&!empty($addons)){
			define('RHC_ADDON_PATH',$addons_path);
			define('RHC_ADDON_URL',$addons_url);
			foreach($addons as $addon){
				try {
					@include_once $addons_path.$addon;
				}catch(Exception $e){
					$current = get_option( $this->options_varname, array() );
					$current = is_array($current) ? $current : array();
					$current['addons'] = is_array($current['addons']) ? $current['addons'] : array() ;
					//----
					$current['addons'] = array_diff($current['addons'], array($addon))  ;
					update_option($this->options_varname, $current);					
				}
			}
		}
	}
	
	function rhc_ui_theme($t){
		$t = array_merge($t,array(''=>'no ui-theme','default'	=> 'Default','smoothness'=> 'UI-Smoothness','sunny'		=> 'UI-Sunny'));
		return $t ;	
	}
	
	function taxonomy_metadata_wpdbfix() {
	  global $wpdb;
	  $wpdb->taxonomymeta = "{$wpdb->prefix}taxonomymeta";
	}
	
	function init(){
		global $wp_version;
		//3.5-beta1-22133
		$version = substr($wp_version,0,3);
		$jquery_ui_option_name = is_admin() ? 'backend_jquery_ui' : 'frontend_jquery_ui';			
		
		$jquery_ui = $this->get_option($jquery_ui_option_name,'',true);
		if(''==$jquery_ui){
			if($version>=3.5){
				$jquery_ui = 'rhc-jquery-ui-1-9-0';
			}else{
				$jquery_ui = 'rhc-jquery-ui-1-8-22';
			}
		}	
		
		wp_register_script( 'rhc-jquery-ui-1-9-0', RHC_URL.'js/jquery-ui-1.9.0.custom.min.js', array('jquery'),'1.9.0');
		wp_register_script( 'rhc-jquery-ui-1-8-22', RHC_URL.'js/jquery-ui-1.8.22.custom.min.js', array('jquery'),'1.8.22');
		
		wp_enqueue_style( 'fullcalendar-theme', RHC_URL.'ui-themes/default/style.css', array(),'1.8.17');
		wp_enqueue_style( 'calendarize', RHC_URL.'style.css', array(),'2.0.0.4');
		wp_register_script( 'jquery-easing', RHC_URL.'js/jquery.easing.1.3.js', array('jquery'),'1.3.0',$this->in_footer);
		wp_register_script( 'rrecur-parser', RHC_URL.'js/rrecur-parser.js', array('jquery'),'1.1.0.2',$this->in_footer);	
		wp_register_script( 'fullcalendar-gcal', RHC_URL.'fullcalendar/fullcalendar/gcal.js', array('fullcalendar'),'1.6.1.1',$this->in_footer);	
		wp_register_script( 'fullcalendar', RHC_URL.'fullcalendar/fullcalendar/fullcalendar.custom.js', array('jquery','rrecur-parser'),'1.6.1.1',$this->in_footer);	
		wp_register_script( 'fechahora', RHC_URL.'js/fechahora.js', array('jquery'),'1.0.0',$this->in_footer);
		wp_register_script( 'fc_dateformat_helper', RHC_URL.'js/fc_dateformat_helper.js', array('fullcalendar'),'1.0.0',$this->in_footer);
		wp_register_script( 'calendarize-fcviews', RHC_URL.'js/fullcalendar_custom_views.js', array(),'1.1.3.5',$this->in_footer);	
		//-------
		$dependency = array('jquery','fullcalendar','jquery-easing','calendarize-fcviews','fullcalendar-gcal');
		if('none'!=$jquery_ui){
			$dependency[]=$jquery_ui;
		}
		wp_register_script( 'calendarize', RHC_URL.'js/calendarize.js', $dependency,'2.0.0.4',$this->in_footer);
		//-------
		wp_register_script( 'rhc-upcoming-widget', RHC_URL.'js/widget_upcoming_events.js', array('jquery','fullcalendar'),'1.1.0.7',$this->in_footer);	
		
		wp_register_script( 'google-api3', 'http://maps.google.com/maps/api/js?sensor=false&libraries=places', array('jquery'),'3.0',$this->in_footer);
		wp_register_script( 'rhc_gmap3', RHC_URL.'js/rhc_gmap3.js', array('google-api3'), '1.0.1',$this->in_footer );
		
		if('1'==$this->get_option('visibility_check','0',true)){
			wp_enqueue_script( 'rhc-visibility-check', RHC_URL.'js/visibility_check.js', array('jquery'),'1.0.0',$this->in_footer);
		}
		
		if('0'==$this->get_option('disable_print_css','0',true)){
			wp_enqueue_style( 'rhc-print-css', RHC_URL.'css/print.css', array(),'1.0.0');
		}
		
		if(is_admin()){
			//wp_register_style( 'rhc-options', RHC_URL.'css/pop.css', array(),'1.0.0');
			wp_register_style( 'post-meta-boxes', RHC_URL.'css/post_meta_boxes.css', array(),'1.0.0');
			wp_register_style( 'rhc-admin', RHC_URL.'css/admin_rhc.css', array(),'1.0.0');
			//wp_register_style( 'rhc-jquery-ui', RHC_URL.'css/jquery-ui/righthere-calendar/jquery-ui-1.8.14.custom.css', array(),'1.8.14');
			wp_register_style( 'rhc-jquery-ui', RHC_URL.'ui-themes/default/style.css', array(),'1.8.14');
			wp_register_style( 'calendarize-metabox', RHC_URL.'css/calendarize_metabox.css', array(),'1.0.4');			
			$dependency = array();
			if('none'!=$jquery_ui){
				$dependency[]=$jquery_ui;
			}
			wp_register_script( 'rhc-jquery-ui-timepicker', RHC_URL.'js/jquery-ui-timepicker-addon.js', $dependency,'0.9.5');
			wp_register_script( 'rhc-admin', RHC_URL.'js/admin_rhc.js', array('rhc-jquery-ui-timepicker'),'1.0.0');				
			//wp_register_script( 'calendarize-metabox', RHC_URL.'js/calendarize_metabox.js', array('jquery'),'1.0.1');			
			wp_register_script( 'calendarize-metabox', RHC_URL.'js/calendarize_metabox_rrule.js', array('jquery'),'1.2.6.1');				
			wp_register_script( 'postinfo-metabox', RHC_URL.'js/post_info_metabox.js', array('jquery'),'1.2.6.1');	
		}else{
			wp_enqueue_script('rhc-upcoming-widget');
			wp_enqueue_script('calendarize');
		}
	}
	
	function plugins_loaded(){
		require_once RHC_PATH.'includes/class.ui_themes_for_calendarize_it.php';
		require_once RHC_PATH.'includes/functions.template.php';
		require_once RHC_PATH.'includes/function.generate_calendarize_shortcode.php';
		//frontend
		require_once RHC_PATH.'custom-taxonomy-with-meta/taxonomy-metadata.php';  
		require_once RHC_PATH.'custom-taxonomy-with-meta/taxonomymeta_shortcode.php';

		require_once RHC_PATH.'includes/class.shortcode_calendarize.php';
		new shortcode_calendarize();
		
		require_once RHC_PATH.'includes/class.rhc_post_info_shortcode.php';
		new rhc_post_info_shortcode($this->post_info_shortcode);
		
		require_once RHC_PATH.'includes/class.calendar_ajax.php';
		$this->calendar_ajax = new calendar_ajax();

		//widgets
		require_once RHC_PATH.'includes/class.UpcomingEvents_Widget.php';
		add_action( 'widgets_init', create_function( '', 'register_widget( "UpcomingEvents_Widget" );' ) );
		require_once RHC_PATH.'includes/class.EventsCalendar_Widget.php';
		add_action( 'widgets_init', create_function( '', 'register_widget( "EventsCalendar_Widget" );' ) );
		
		//shortcodes
		require_once RHC_PATH.'shortcodes/venues.php';
		new shortcode_venues(RHC_VENUE);
		require_once RHC_PATH.'shortcodes/organizers.php';
		new shortcode_organizers(RHC_ORGANIZER);
		require_once RHC_PATH.'shortcodes/single.php';
		new rhc_single_shortcoes();		

		if('version1'==$this->get_option('template_integration','version2',true)){
			require_once RHC_PATH.'includes/class.rhc_template_frontend_old.php';
		}else{
			require_once RHC_PATH.'includes/class.rhc_template_frontend.php';
		}
		new rhc_template_frontend();
		
		require_once RHC_PATH.'includes/class.load_event_template.php';
		new load_event_template();
		
		require_once RHC_PATH.'includes/class.rhc_custom_field_filters.php';
		new rhc_custom_field_filters();
		
		if(is_admin()){								
			require_once RHC_PATH.'includes/class.rhc_layout_settings.php';
			new rhc_layout_settings($this->id);	

			require_once RHC_PATH.'includes/class.rhc_post_info_settings.php';
			new rhc_post_info_settings($this->id);	
			
			require_once RHC_PATH.'includes/class.rhc_settings.php';
			new rhc_settings($this->id);	
			
			require_once RHC_PATH.'includes/class.rhc_tax_settings.php';
			new rhc_tax_settings($this->id);		
			
			$license_keys = $this->get_option('license_keys',array());
			$license_keys = is_array($license_keys)?$license_keys:array();
			
			$dc_options = array(
				'id'			=> $this->id.'-dc',
				'plugin_id'		=> $this->id,
				'capability'	=> $this->options_capability,
				'resources_path'=> $this->resources_path,
				'parent_id'		=> 'edit.php?post_type='.RHC_EVENTS,
				'menu_text'		=> __('Downloads','rhc'),
				'page_title'	=> __('Downloadable content - Calendarize it! for WordPress','rhc'),
				'license_keys'	=> $license_keys,
				'plugin_code'	=> $this->plugin_code,
				//'api_url'		=> 'http://dev.lawley.com/',
				'product_name'	=> __('Calendarize-it','rhc'),
				'options_varname' => $this->options_varname,
				'tdom'			=> 'rhc'
			);
			
			$ad_options = array(
				'id'			=> $this->id.'-addons',
				'plugin_id'		=> $this->id,
				'capability'	=> $this->options_capability,
				'resources_path'=> $this->resources_path,
				'parent_id'		=> 'edit.php?post_type='.RHC_EVENTS,
				'menu_text'		=> __('Add-ons','rhc'),
				'page_title'	=> __('Calendarize it! add-ons','rhc'),
				'options_varname' => $this->options_varname
			);
			
			$settings = array(				
				'id'					=> $this->id,
				'plugin_id'				=> $this->id,
				'capability'			=> $this->options_capability,
				'capability_license'	=> $this->license_capability,
				'options_varname'		=> $this->options_varname,
				'menu_id'				=> 'rhc-options',
				'page_title'			=> __('Options','rhc'),
				'menu_text'				=> __('Options','rhc'),
				'option_menu_parent'	=> 'edit.php?post_type='.RHC_EVENTS,
				//'option_menu_parent'	=> $this->id,
				'notification'			=> (object)array(
					'plugin_version'=> RHC_VERSION,
					'plugin_code' 	=> 'RHC',
					'message'		=> __('Calendar plugin update %s is available! <a href="%s">Please update now</a>','rhc')
				),
				'ad_options'			=> $ad_options,
				//'addons'				=> is_array($license_keys)&&count($license_keys)>0?true:false,
				'addons'				=> $this->debug_menu,
				'dc_options'			=> $dc_options,
				'theme'					=> false,
				'stylesheet'			=> 'rhc-options',
				'option_show_in_metabox'=> true,
				'path'			=> RHC_PATH.'options-panel/',
				'url'			=> RHC_URL.'options-panel/',
				'pluginslug'	=> RHC_SLUG,
				//'api_url' 		=> "http://localhost"
				'api_url' 		=> "http://plugins.righthere.com"
			);
			
			do_action('rh-php-commons');	
			
			$settings['id'] 		= $this->id;
			$settings['menu_id'] 	= $this->id;
			$settings['menu_text'] 	= __('Options','rhc');
			$settings['import_export'] = false;
			$settings['import_export_options'] =false;
			$settings['registration'] = true;
			$settings['downloadables'] = true;
			
			new PluginOptionsPanelModule($settings);

			//--------
			//require_once RHC_PATH.'includes/class.rhc_calendar_metabox.php';
			require_once RHC_PATH.'includes/class.rhc_calendar_metabox_rrule.php';
			new rhc_calendar_metabox(RHC_EVENTS,$this->debug_menu);
			$post_types = $this->get_option('post_types',array());
			if(is_array($post_types)&&count($post_types)>0){
				foreach($post_types as $post_type){
					new rhc_calendar_metabox($post_type,$this->debug_menu);
				}
			}
			//---	
			require_once RHC_PATH.'includes/class.rhc_post_info_metabox.php';
			new rhc_post_info_metabox(RHC_EVENTS,'edit_'.RHC_CAPABILITY_TYPE);	
			
			if($this->debug_menu){
				require_once RHC_PATH.'includes/class.debug_calendarize.php';
				new debug_calendarize('edit.php?post_type='.RHC_EVENTS);
			}
			
			//--adds metabox for choosing template. not supported yet.
			//require_once RHC_PATH.'includes/class.rhc_event_template_metabox.php';
			//new rhc_event_template_metabox(RHC_EVENTS,$this->debug_menu);
	
			require_once RHC_PATH.'includes/class.rhc_event_image_metaboxes.php';
			new rhc_event_image_metaboxes();
		}
		
		require_once RHC_PATH.'includes/class.righthere_calendar.php';
		new righthere_calendar(array(
			'show_in_menu'=>true,
			'menu_position'=>null
		));
		
		if('1'==$this->get_option('enable_theme_thumb','0',true)){
			add_action('init',array(&$this,'add_events_featured_image'));	
		}
	}
	
	function add_events_featured_image(){
		add_theme_support( 'post-thumbnails' );
	}
	
	function get_option($name,$default='',$default_if_empty=false){
		$value = isset($this->options[$name])?$this->options[$name]:$default;
		if($default_if_empty){
			$value = ''==$value?$default:$value;
		}
		return $value;
	}	
	
	function get_intervals(){//deprecated
		return array(
					''			=> __('Never(Not a recurring event)','rhc'),
					'1 DAY'		=> __('Every day','rhc'),
					'1 WEEK'	=> __('Every week','rhc'),
					'2 WEEK'	=> __('Every 2 weeks','rhc'),
					'1 MONTH'	=> __('Every month','rhc'),
					'1 YEAR'	=> __('Every year','rhc')
				);
	}	
	
	function get_rrule_freq(){
		return apply_filters('get_rrule_freq',array(
					''							=> __('Never(Not a recurring event)','rhc'),
					/*'FREQ=DAILY;INTERVAL=1;COUNT=1'	=> __('Arbitrary repeat dates','rhc'),*/
					'FREQ=DAILY;INTERVAL=1'	=> __('Every day','rhc'),
					'FREQ=WEEKLY;INTERVAL=1'	=> __('Every week','rhc'),
					'FREQ=WEEKLY;INTERVAL=2'	=> __('Every 2 weeks','rhc'),
					'FREQ=MONTHLY;INTERVAL=1'	=> __('Every month','rhc'),
					'FREQ=YEARLY;INTERVAL=1'	=> __('Every year','rhc')
				));
	}
	
	function get_template_path($file=''){
		$path = RHC_PATH.'templates/default/'.$file;
		return apply_filters('rhc_template_path',$path,$file);
	}
	
	function get_settings_path($file=''){
		$path = RHC_PATH.'settings/default/'.$file;
		return apply_filters('rhc_settings_path',$path,$file);
	}
}
?>