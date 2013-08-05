<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/
class rhc_settings {
	var $added_rules;
	function rhc_settings($plugin_id='rhc'){
		//$this->id = $plugin_id.'-log';
		$this->id = $plugin_id;
		add_filter("pop-options_{$this->id}",array(&$this,'options'),10,1);			
		add_action('pop_handle_save',array(&$this,'pop_handle_save'),50,1);
		add_action('pop_body_'.$this->id,array(&$this,'flush_rewrite_rules'));
				
		add_action('init',array(&$this,'admin_init'));
		
	}
	
	function admin_init(){
		$this->add_rhc_rules(false);
		//observe that this isnt flushing rules. just adding them to wp_rewrite in case some 
		//other plugins flushes them, and calendarize are not included.
	}
	
	function add_rhc_rules($flush_rules=true){
		global $wp_rewrite,$rhc_plugin;
		//Todo: change to rewrite endpoints when they work with archives.
		//-----
		$visual_calendar_slug = $rhc_plugin->get_option('rhc-visualcalendar-slug',RHC_VISUAL_CALENDAR, true);
		//-----
		$forced_rewrite_rule = $rhc_plugin->get_option('forced_rewrite_rules', '0', true);
		$forced_rewrite_rule = $forced_rewrite_rule=='1'?true:false;
		// note: why forced? some plugins seem to be removing cal permalinks.
		
		$post_types=array();
		$rhc_rules = array();
		foreach(get_post_types(array(/*'public'=> true,'_builtin' => false*/),'objects','and') as $post_type => $pt){
			if(in_array($post_type,array('revision','nav_menu_item')))continue;
			$post_types[$post_type]=$pt;
		} 
		//-----
		if( '1'==$rhc_plugin->get_option('enable_post_type_endpoint','1',true) ){
			$calendarize_post_types = array(
				'rhc-events-slug' => RHC_EVENTS
			);
			foreach($calendarize_post_types as $slug => $post_type){
				$regex = sprintf('(%s)/(%s)/?$',$rhc_plugin->get_option($slug,$post_type,true),$visual_calendar_slug);
				$redirect = sprintf('index.php?post_type=%s&%s=$matches[2]',$post_type,RHC_DISPLAY);
				if($forced_rewrite_rule){
					$rhc_rules[$regex]=$redirect;
				}else{
					add_rewrite_rule($regex, $redirect	, 'top');
				}
			}		
			/*
			$additional_post_types = $rhc_plugin->get_option('post_types',array(),true);
			if(is_array($additional_post_types)&&count($additional_post_types)>0){
				foreach($additional_post_types as $post_type){
					if(isset($post_types[$post_type]) && $post_types[$post_type]->has_archive){
						$slug = isset($post_types[$post_type]->rewrite['slug'])?$post_types[$post_type]->rewrite['slug']:$post_type;
						$regex = sprintf('(%s)/(%s)/?$',$slug,$visual_calendar_slug);
						$redirect = sprintf('index.php?post_type=%s&%s=$matches[2]',$post_type,RHC_DISPLAY);
						if($forced_rewrite_rule){
							$rhc_rules[$regex]=$redirect;
						}else{
							add_rewrite_rule($regex, $redirect	, 'top');
						}
						//---todo: add calendar endpoint to taxonomies	in $post_types[$post_type]->taxonomies				
					}
				}
			}
			*/
		}
		//----
		/*
		//venue and organizer already display a calendar so this isnt really needed.
		$event_taxonomies = array(
			'rhc-calendar-slug' 	=> RHC_CALENDAR,
			'rhc-venues-slug'		=> RHC_VENUE,
			'rhc-organizers-slug'	=> RHC_ORGANIZER
		);
		foreach($event_taxonomies as $id => $taxonomy){
			$regex = sprintf('%s/([^/]+)/(%s)/?$', $rhc_plugin->get_option($id,$taxonomy,true), $visual_calendar_slug );
			$redirect = sprintf('index.php?%s=$matches[1]&%s=$matches[2]',$taxonomy,RHC_DISPLAY);
			if($forced_rewrite_rule){
				$rhc_rules[$regex]=$redirect;
			}else{
				add_rewrite_rule($regex, $redirect	, 'top');
			}			
		}	
		*/

		if( $forced_rewrite_rule && !empty($rhc_rules) ){		
			$wp_rewrite->extra_rules_top = array_merge( $rhc_rules, $wp_rewrite->extra_rules_top );
		}
		
		if($flush_rules){	
			flush_rewrite_rules(false);		
		}
	}
	
	function flush_rewrite_rules(){
		if( get_option('rhc_flush_rewrite_rules',false) ){
			delete_option('rhc_flush_rewrite_rules');
			$this->add_rhc_rules();
		}
	}
	
	function pop_handle_save($pop){
		global $rhc_plugin;
		if($rhc_plugin->options_varname!=$pop->options_varname)return;
		update_option('rhc_flush_rewrite_rules',true);
	}
	
	function options($t){
		$i = count($t);
		//-- Permalink settings -----------------------		
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-permalinks'; 
		$t[$i]->label 		= __('Permalink settings','rhc');
		$t[$i]->right_label	= __('Modify permalinks','rhc');
		$t[$i]->page_title	= __('Permalink settings','rhc');
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		$t[$i]->options = array(
			(object)array(
				'id'			=> 'rhc-events-slug',
				'type' 			=> 'text',
				'label'			=> __('Events post type slug','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'rhc-calendar-slug',
				'type' 			=> 'text',
				'label'			=> __('Calendar category slug','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'rhc-venues-slug',
				'type' 			=> 'text',
				'label'			=> __('Venues slug','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'rhc-organizers-slug',
				'type' 			=> 'text',
				'label'			=> __('Organizers slug','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'rhc-visualcalendar-slug',
				'type' 			=> 'text',
				'default'		=> RHC_VISUAL_CALENDAR,
				'label'			=> __('Visual calendar slug','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'		=> 'forced_rewrite_rules',
				'label'		=> __('Forced rewrite rules','rhc'),
				'type'		=> 'yesno',
				'description'=> __('Choose yes if permalinks are not working.  It will attempt an alternative method of adding rewrite rules.','rhc'),
				'default'	=> '0',
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'		=> 'enable_post_type_endpoint',
				'label'		=> __('Enable calendar end point','rhc'),
				'description'=> sprintf('<p>%s</p><p>%s</p>',
					__('If permalinks are active, choose yes to be able to append /calendar/ to the url to load a calendar for that particular post type, example yourdomain.com/events/calendar/ will display the calendar without the need to setup the shortcode on a page.','rhc'),
					__('If you have a page with permalink /events/calendar/ you may need to disable this, as it takes precedence over the page permlink.','rhc')
				),
				'type'		=> 'yesno',
				'default'	=> '1',
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
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
		//--Custom Post Types -----------------------		
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-custom-types'; 
		$t[$i]->label 		= __('Custom Post Types','rhc');
		$t[$i]->right_label	= __('Enable calendar metabox for other post types.','rhc');
		$t[$i]->page_title	= __('Custom Post Types','rhc');
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		$t[$i]->options = array();
		
		//--------------
		$post_types=array();
		foreach(get_post_types(array(/*'public'=> true,'_builtin' => false*/),'objects','and') as $post_type => $pt){
			if(in_array($post_type,array(RHC_EVENTS, 'revision','nav_menu_item')))continue;
			$post_types[$post_type]=$pt;
		} 
		//--------------		
		if(count($post_types)==0){
			$t[$i]->options[]=(object)array(
				'id'=>'no_ctypes',
				'type'=>'description',
				'label'=>__("There are no additional Post Types to enable.",'rhc')
			);
		}else{
			$j=0;
			foreach($post_types as $post_type => $pt){
				$tmp=(object)array(
					'id'	=> 'post_types_'.$post_type,
					'name'	=> 'post_types[]',
					'type'	=> 'checkbox',
					'option_value'=>$post_type,
					'label'	=> (@$pt->labels->name?$pt->labels->name:$post_type),
					'el_properties' => array(),
					'save_option'=>true,
					'load_option'=>true
				);
				if($j==0){
					$tmp->description = __("Calendarizer metabox can be enabled for other post types.  Check the post types, where you want the calendar metabox to be displayed.",'rhc');
					$tmp->description_rowspan = count($post_types);
				}
				$t[$i]->options[]=$tmp;
				$j++;
			}
		}

		$t[$i]->options[]=(object)array(
				'type'=>'clear'
			);	
			
		$t[$i]->options[]=(object)array(
				'type'=>'subtitle',
				'label'=>__('Other integration settings','rhc')
			);				
			
		$t[$i]->options[]=(object)array(
				'id'		=> 'show_all_post_types',
				'label'		=> __('Show all post types in calendar','rhc'),
				'description'=> __('By default the calendarizeit shortcode only displays one custom post type (events by default), and you need to set the post_type value to show a diferent post type.  Choose yes if you want to display all enabled post types by default.','rhc'),
				'type'		=> 'yesno',
				'default'	=> '0',
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
			);
					
		$t[$i]->options[]=(object)array(
				'type'=>'clear'
			);
		$t[$i]->options[]=(object)array(
				'type'	=> 'submit',
				'label'	=> __('Save','rhc'),
				'class' => 'button-primary'
			);
		//--Advanced settings-----------------------		
/*
		$i++;
		$t[$i]->id 			= 'advanced'; 
		$t[$i]->label 		= __('Advanced Settings','rhc');
		$t[$i]->right_label	= __('Advanced Settings','rhc');
		$t[$i]->page_title	= __('Advanced Settings','rhc');
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		$t[$i]->options = array(
			(object)array(
				'id'	=> 'todo',
				'type'	=> 'label',
				'label'	=> __('TODO','rhc'),
				'save_option'=>false,
				'load_option'=>false
			),	
			(object)array(
				'type'=>'clear'
			)	,
			(object)array(
				'type'	=> 'submit',
				'label'	=> __('Save','rhc'),
				'class' => 'button-primary',
				'save_option'=>false,
				'load_option'=>false
			)	
		);		
*/		
		//-------------------------		
		if(current_user_can('manage_options')){
			$i++;
			$t[$i]=(object)array();
			$t[$i]->id 			= 'troubleshooting'; 
			$t[$i]->label 		= __('Troubleshooting','rhc');
			$t[$i]->right_label	= __('Troubleshooting','rhc');
			$t[$i]->page_title	= __('Troubleshooting','rhc');
			$t[$i]->theme_option = true;
			$t[$i]->plugin_option = true;
			$t[$i]->options = array(
				(object)array(
					'id'		=> 'ignore_wordpress_standard',
					'label'		=> __('Ignore WordPress Standard','rhc'),
					'type'		=> 'yesno',
					'description'=> sprintf('<p>%s</p><p>%s</p>',
						__('Choose yes only if you are getting a 404 page when trying to get an event page.','rhc'),
						__('If you choose yes and the event starts showing, it means that the theme or a plugin is not following a standard in regards to register_post_type and flush_rewrite_rules.  Under certain circumstances it could also affecting website performance.','rhc')
					),
					'default'	=> '0',
					'el_properties'	=> array(),
					'save_option'=>true,
					'load_option'=>true
				),	
				(object)array(
					'id'		=> 'enable_theme_thumb',
					'label'		=> __('Enable thumbnail support','rhc'),
					'type'		=> 'yesno',
					'description'=> __('Choose yes only if the thumbnail metabox is not showing when you edit event.  Usually themes enable this.','rhc'),
					'default'	=> '0',
					'el_properties'	=> array(),
					'save_option'=>true,
					'load_option'=>true
				),	
				(object)array(
					'id'		=> 'enable_debug',
					'label'		=> __('Enable debug','rhc'),
					'type'		=> 'yesno',
					'description'=> __('Choose yes to display a debug menu.  This provide technical information that support can use to troubleshoot problems.','rhc'),
					'default'	=> '0',
					'el_properties'	=> array(),
					'save_option'=>true,
					'load_option'=>true
				),	
				(object)array(
					'id'			=> 'rhc-api-url',
					'type' 			=> 'text',
					'label'			=> __('Api url','rhc'),
					'description'	=> __('On some setups, wordpress is installed in a non-standard way and causes the site_url() function to return a value that is diferent from the real url, causing the browser to reject the ajax.  You need to add rhc_action=get_calendar_events to the query string.','rhc'),
					'el_properties' => array('class'=>'widefat'),
					'save_option'=>true,
					'load_option'=>true
				),				
				(object)array(
					'id'		=> 'in_footer',
					'label'		=> __('Scripts in footer','rhc'),
					'type'		=> 'yesno',
					'description'=> sprintf('<p>%s</p>',
						__('Choose yes if you want this plugin scripts loaded in the footer.','rhc')
					),
					'default'	=> '0',
					'el_properties'	=> array(),
					'save_option'=>true,
					'load_option'=>true
				),						
				(object)array(
					'type'=>'clear'
				),
				(object)array(
					'type' 			=> 'subtitle',
					'label'			=> __('jQuery UI','rhc')
				),	
					
				(object)array(
					'id'			=> 'frontend_jquery_ui',
					'label'			=> __('Frontend jQuery UI version','rhc'),
					'description'	=> sprintf("<p>%s</p><p>%s</p>",
						__('Specify the jQuery UI version to load in the frontend and backend.  By default it loads 1.9.0 on WP3.5 and higher and 1.8.22 on pre WP3.5','rhc'),
						__('If you choose to skip loading the bundled jQuery UI, you need to make sure that the theme or plugin loads it.','rhc')
					),
					'type'			=> 'select',
					'default'		=> '',
					'options'		=> array(
						''			=> __('Auto','rhc'),
						'rhc-jquery-ui-1-9-0'	=> __('jQuery UI 1.9.0','rhc'),
						'rhc-jquery-ui-1-8-22'	=> __('jQuery UI 1.8.22','rhc'),
						'none'		=> __('Do not load bundled jQuery UI','rhc')
					),
					'el_properties'	=> array(),
					'save_option'=>true,
					'load_option'=>true
				),	
					
				(object)array(
					'id'			=> 'backend_jquery_ui',
					'label'			=> __('Backend jQuery UI version','rhc'),
					'type'			=> 'select',
					'default'		=> '',
					'options'		=> array(
						''			=> __('Auto','rhc'),
						'rhc-jquery-ui-1-9-0'	=> __('jQuery UI 1.9.0','rhc'),
						'rhc-jquery-ui-1-8-22'	=> __('jQuery UI 1.8.22','rhc'),
						'none'		=> __('Do not load bundled jQuery UI','rhc')
					),
					'el_properties'	=> array(),
					'save_option'=>true,
					'load_option'=>true
				),					
										
				(object)array(
					'type'=>'clear'
				),
				(object)array(
					'type'	=> 'submit',
					'label'	=> __('Save','rhc'),
					'class' => 'button-primary',
					'save_option'=>false,
					'load_option'=>false
				)								
			);
		}
				
		return $t;
	}
}
?>