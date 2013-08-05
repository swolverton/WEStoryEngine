<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/
class rhc_layout_settings {
	function rhc_layout_settings($plugin_id='rhc'){
		//$this->id = $plugin_id.'-log';
		$this->id = $plugin_id;
		add_filter("pop-options_{$this->id}",array(&$this,'options'),10,1);			
		add_action('pop_handle_save',array(&$this,'pop_handle_save'),50,1);
		add_action("pop_admin_head_{$this->id}", array(&$this,'head'),10,1);
		add_action("pop_body_{$this->id}", array(&$this,'body'),10,1);
		
		add_action('wp_ajax_rhc_default_template', array(&$this,'wp_ajax_rhc_default_template'));
	}
	
	function pop_handle_save($pop){
		global $rhc_plugin;
		if($rhc_plugin->options_varname!=$pop->options_varname)return;
		update_option('rhc_flush_rewrite_rules',true);
	}
	
	function options($t){
	
		$pages = $this->get_pages_for_dropdown();	
		
		$i = count($t);
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-template'; 
		$t[$i]->label 		= __('Template Settings','rhc');
		$t[$i]->right_label	= __('Adjust Template Settings','rhc');
		$t[$i]->page_title	= __('Template Settings','rhc');
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		$t[$i]->options = array(
			(object)array(
				'type' 			=> 'subtitle',
				'label'			=> __('Template Integration','rhc'),
				'description'=>__('Use the latest available version.  Version 1 is the original template integration; provided for back compatibility on sites that have already customized their calendar templates but want to update the plugin. ','rhl'),
			),		
			(object)array(
				'id'		=> 'template_integration',
				'label'		=> __('Template Integration','rhc'),
				'type'		=> 'select',
				'default'	=> 'version2',
				'options'	=> array(
					'version1'	=> 'version 1',
					'version2'	=> 'version 2'
				),
				'hidegroup'	=> '#template_integration_meta',
				'hidevalues' => array('version2'),
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
			),	
			(object)array('type'	=> 'clear'),	
			(object)array(
				'id'	=> 'template_integration_meta',
				'type'=>'div_start'
			),		
			(object)array(
				'id'			=> 'event_template_page_id',
				'type' 			=> 'select',
				'label'			=> __('Detailed Event Page Template','rhc'),
				'description'	=> sprintf('<p>%s</p>',
					__('Select the page you want to use as a template for the Detailed Event Page and Detailed Venue Page.','rhc')
				),
				'el_properties'=>array('class'=>'widefat'),
				'options'=> $pages,
				'save_option'=>true,
				'load_option'=>true
			),
				
			(object)array(
				'id'			=> 'taxonomy_template_page_id',
				'type' 			=> 'select',
				'options'		=> $pages,
				'label'			=> __('Venue and Organizer Page Template','rhc'),
				'el_properties'=>array('class'=>'widefat'),
				'save_option'=>true,
				'load_option'=>true
			),
			/* nobody is really using the calendar endpoint, and this option only brings confusion.
			(object)array(
				'id'			=> 'calendar_template_page_id',
				'type' 			=> 'text',
				'label'			=> __('Calendar template page id','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			*/
			(object)array('type'	=> 'div_end'),	
			(object)array('type'	=> 'clear'),		
			(object)array(
				'id'			=> 'widget_link_template_page_id',
				'type' 			=> 'select',
				'options'		=> $pages,
				'label'			=> __('Calendar Widget links to Page','rhc'),
				'el_properties'=>array('class'=>'widefat'),
				'description'	=> sprintf('<p>%s</p>',
					__('Calendar widget: Selet a page, to which the calendar widget will take the user when clicked.  Usually a page containing the calendarizeit shortcode.','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),
							
			(object)array(
				'type' 			=> 'subtitle',
				'label'			=> __('Calendarize Templates','rhc'),
				'description'	=> __('Disable calendarize templates if you want to use the theme templates.  Observe that meta data like maps, venue and extra info will need to be added manually throught shortcodes.','rhc')
			),		
			(object)array(
				'id'		=> 'template_archive',
				'label'		=> __('Disable Archive Template','rhc'),
				'type'		=> 'yesno',
				'default'	=> '0',
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
			),			
			(object)array(
				'id'		=> 'template_single',
				'label'		=> __('Disable Event Template','rhc'),
				'type'		=> 'yesno',
				'default'	=> '0',
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
			),			
			(object)array(
				'id'		=> 'template_taxonomy',
				'label'		=> __('Disable Taxonomy Template','rhc'),
				'type'		=> 'yesno',
				'default'	=> '0',
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
			),	
								
			(object)array(
				'type' 			=> 'subtitle',
				'label'			=> __('Ajax based templates and sliders','rhc'),
				'description'	=> __('Some themes that load content with ajax, tabs, and sliders break the initial rendering of the calendar.  Choose yes to prevent this.  If not needed the recommended setting is to choose no.','rhc')
			),			
			(object)array(
				'id'		=> 'visibility_check',
				'label'		=> __('Check calendar visibility','rhc'),
				'type'		=> 'yesno',
				'default'	=> '0',
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
			),		
			(object)array('type'	=> 'clear'),	
			(object)array(
				'type' 			=> 'subtitle',
				'label'			=> __('Google map','rhc')
			),				
			(object)array(
				'id'		=> 'gmap3_scrollwheel',
				'label'		=> __('Enable mouse wheel google map zoom ','rhc'),
				'description'=> __('If disabled, the user can still zoom in or out using the zoom control buttons.','rhc'),
				'type'		=> 'yesno',
				'default'	=> '1',
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
			),	
			(object)array('type'	=> 'clear'),	
			(object)array(
				'type' 			=> 'subtitle',
				'label'			=> __('HTML wrapper','rhc')
			),		
			(object)array(
				'id'			=> 'rhc-before-content',
				'type' 			=> 'textarea',
				'label'			=> __('HTML Between header and content','rhc'),
				'description'	=> sprintf('<p>%s</p>',
					__('On some themes you may need to add additional html so the content is styled correctly by the theme.','rhc')
				),
				'el_properties' => array('rows'=>'15','cols'=>'50'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'rhc-after-content',
				'type' 			=> 'textarea',
				'label'			=> __('HTML Between content and footer','rhc'),
				'el_properties' => array('rows'=>'15','cols'=>'50'),
				'save_option'=>true,
				'load_option'=>true
			)/*,
			(object)array(
				'type' 			=> 'subtitle',
				'label'			=> __('Used templates','rhc'),
				'description'	=> __('Calendarize uses its own template file by default.  You can use the following options to disable the calendarize templates, and let the theme handle the layout.','rhc')
			),	
			*/		
		);	
		$t[$i]->options[]=(object)array(
				'type'=>'clear'
			);
		$t[$i]->options[]=(object)array(
				'type'	=> 'submit',
				'label'	=> __('Save','rhc'),
				'class' => 'button-primary'
			);		
			
		//----			
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-meda'; 
		$t[$i]->label 		= __('Media settings','rhc');
		$t[$i]->right_label	= __('Adjust media settings','rhc');
		$t[$i]->page_title	= __('Media settings','rhc');
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;	
		$t[$i]->options = array(
			(object)array(
				'id'		=> 'rhc_media_size',
				'label'		=> __('Event list/tooltip image size','rhc'),
				'type'		=> 'select',
				'default'	=> 'thumbnail',
				'options'	=> array(
					'thumbnail'	=> __('Thumbnail','rhc'),
					'medium'	=> __('Medium','rhc'),
					'large'		=> __('Large','rhc'),
					'full'		=> __('Full','rhc')
				),
				'description'	=> __('Please observe that this does NOT modifies the size of the image on screen wich is controlled by the stylesheet.  This is used to determine what image size to use as source.','rhc'),
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
			
		//----			
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-general'; 
		$t[$i]->label 		= __('General settings','rhc');
		$t[$i]->right_label	= __('General settings','rhc');
		$t[$i]->page_title	= __('General settings','rhc');
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;		
		$t[$i]->options = array(
			(object)array(
				'id'		=> 'disable_event_link',
				'label'		=> __('Disable event link','rhc'),
				'type'		=> 'yesno',
				'default'	=> '0',
				'description'	=> __('Check this option if you do not want the calendar events to link to a single event page.','rhc'),
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'		=> 'disable_event_search',
				'label'		=> __('Disable event search','rhc'),
				'type'		=> 'yesno',
				'default'	=> '0',
				'description'	=> __('Check this option if you do not want events to show in search results.','rhc'),
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'		=> 'disable_print_css',
				'label'		=> __('Disable print css','rhc'),
				'type'		=> 'yesno',
				'default'	=> '0',
				'description'	=> __('When printing a page with a calendar, by default only the calendar will be printed.  Check this option to disable print css.','rhc'),
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
				'class' => 'button-primary'
			)
		);					
					
		//-- default shortcode values --------------------------
		global $rhc_plugin; 
		
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-default-cal-settings'; 
		$t[$i]->label 		= __('Calendarize shortcode','rhc');
		$t[$i]->right_label	= __('Default calendar settings','rhc');
		$t[$i]->page_title	= __('Calendarize shortcode','rhc');
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		$t[$i]->options = array(
			(object)array(
				'id'			=> 'cal_theme',
				'type' 			=> 'select',
				'label'			=> __('Choose the default ui-theme to be used','rhc'),
				'description'	=> sprintf('<p>%s</p>',
					__('Leave empty for the default styles.','rhc')
				),
				'options'		=> apply_filters('rhc-ui-theme',array()),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_defaultview',
				'type' 			=> 'select',
				'label'			=> __('Default view','rhc'),
				'options'		=> array(
					''			=> __('--choose--','rhc'),
					//month, basicWeek, basicDay, agendaWeek, agendaDay
					'month'		=> __('Month','rhc'),
					'basicWeek'	=> __('Week','rhc'),
					'basicDay'	=> __('Day','rhc'),
					'agendaWeek'=> __('Agenda Week','rhc'),
					'agendaDay'	=> __('Agenda Day','rhc'),
					'rhc_event'	=> __('Events list','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_aspectratio',
				'type' 			=> 'text',
				'label'			=> __('Aspect ratio','rhc'),
				'default'		=> '1.35',
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_header_left',
				'type' 			=> 'text',
				'label'			=> __('Left header','rhc'),
				'default'		=> 'rhc_search prevYear,prev,next,nextYear today',
				'el_properties' => array('class'=>'widefat'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_header_center',
				'type' 			=> 'text',
				'label'			=> __('Center header','rhc'),
				'el_properties' => array('class'=>'widefat'),
				'default'		=> 'title',
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_header_right',
				'type' 			=> 'text',
				'label'			=> __('Right header','rhc'),
				'default'		=> 'month,agendaWeek,agendaDay,rhc_event',
				'description'	=> __('Defaults to: <b>month,agendaWeek,agendaDay,rhc_event</b>. Also available: basicWeek','rhc'),
				'el_properties' => array('class'=>'widefat'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_weekends',
				'type' 			=> 'yesno',
				'label'			=> __('Show weekends','rhc'),
				'default'		=> '1',
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_firstday',
				'type' 			=> 'select',
				'label'			=> __('Calendar First Day','rhc'),
				'default'		=> 0,
				'options'		=> array(
					'0'	=> __('Sunday','rhc'),
					'1'	=> __('Monday','rhc'),
					'2'	=> __('Tuesday','rhc'),
					'3'	=> __('Wednesday','rhc'),
					'4' => __('Thursday','rhc'),
					'5'	=> __('Friday','rhc'),
					'6'	=> __('Saturday','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),		
			(object)array(
				'id'			=> 'cal_loading_overlay',
				'type' 			=> 'yesno',
				'label'			=> __('Show loading overlay','rhc'),
				'default'		=> '0',
				'description'	=> __('Show a loading overlay on the calendar viewport when fetching events','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),	
			(object)array(
				'id'			=> 'cal_week_numbers',
				'type' 			=> 'yesno',
				'label'			=> __('Enable week numbers','rhc'),
				'default'		=> '0',
				'description'	=> sprintf("<p>%s</p><p>%s</p>",
					__('Enables displaying week numbers on the calendar views.','rhc'),
					__('<b>Week number label</b>: By default it is "W", this is the label shown on the week column in month view.  In agenda views it is shown in the top left corner.','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),		
			(object)array(
				'id'			=> 'cal_week_numbers_title',
				'type' 			=> 'text',
				'label'			=> __('Week number label','rhc'),
				'el_properties' => array('class'=>'widefat'),
				'save_option'=>true,
				'load_option'=>true
			),						
			(object)array(
				'type' 			=> 'subtitle',
				'label'			=> __('Calendar labels','rhc'),
				'description'	=> sprintf('<p>%s</p><p>%s</p><p><b>%s</b> %s</p><p><b>%s</b> %s</p><p><b>%s</b> %s</p><p><b>%s</b> %s</p>',
					__('Only use this options if you want to use diferent labels from the localized ones, or if the plugin is not providing localization at all.','rhc'),
					__('Write <b>comma separated</b> and <b>no space</b> labels on each setting in this section','rhc'),
					__('Default month names:','rhc'),
					__('January, February, March, April, May, June, July, August, September, October, November, December','rhc'),
					__('Default short month names:','rhc'),
					__('Jan, Feb, Mar, Apr, May, Jun, Jul, Aug, Sep, Oct, Nov, Dec','rhc'),
					__('Default day names:','rhc'),
					__('Sunday, Monday, Tuesday, Wednesday, Thursday, Friday, Saturday','rhc'),
					__('Default short day names:','rhc'),
					__('Sun, Mon, Tue, Wed, Thu, Fri, Sat','rhc')
				)
			),	
			(object)array(
				'id'			=> 'cal_monthnames',
				'type' 			=> 'text',
				'label'			=> __('Month names','rhc'),
				'el_properties' => array('class'=>'widefat'),
				'save_option'=>true,
				'load_option'=>true
			),	
			(object)array(
				'id'			=> 'cal_monthnamesshort',
				'type' 			=> 'text',
				'label'			=> __('Short month names','rhc'),
				'el_properties' => array('class'=>'widefat'),
				'save_option'=>true,
				'load_option'=>true
			),						
			(object)array(
				'id'			=> 'cal_daynames',
				'type' 			=> 'text',
				'label'			=> __('Day names','rhc'),
				'el_properties' => array('class'=>'widefat'),
				'save_option'=>true,
				'load_option'=>true
			),	
			(object)array(
				'id'			=> 'cal_daynamesshort',
				'type' 			=> 'text',
				'label'			=> __('Short day names','rhc'),
				'el_properties' => array('class'=>'widefat'),
				'save_option'=>true,
				'load_option'=>true
			),				
			(object)array(
				'type' 			=> 'subtitle',
				'label'			=> __('Button labels','rhc'),
				'description'	=> sprintf("%s<br /><img src=\"%s\" class=\"rhc-option-preview\"/>",
					__('Change the labels of the buttons in the calendar top controls.  Please observe that this will overwrite localization, so if you are using multiple languages, leave this fields empty.','rhc'),
					RHC_URL.'css/images/opt_preview_cal_button_labels.png'
				)
			),
			(object)array(
				'id'			=> 'cal_button_text_today',
				'type' 			=> 'text',
				'label'			=> __('Button today','rhc'),
				//'default'		=> __('today','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_button_text_month',
				'type' 			=> 'text',
				'label'			=> __('Button month','rhc'),
				//'default'		=> __('month','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_button_text_day',
				'type' 			=> 'text',
				'label'			=> __('Button day','rhc'),
				//'default'		=> __('day','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_button_text_week',
				'type' 			=> 'text',
				'label'			=> __('Button week','rhc'),
				//'default'		=> __('week','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_button_text_calendar',
				'type' 			=> 'text',
				'label'			=> __('Button Calendar','rhc'),
				//'default'		=> __('Calendar','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_button_text_event',
				'type' 			=> 'text',
				'label'			=> __('Button event','rhc'),
				//'default'		=> __('event','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_button_text_prev',
				'type' 			=> 'text',
				'label'			=> __('Button previous','rhc'),
				//'default'		=> __('event','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_button_text_next',
				'type' 			=> 'text',
				'label'			=> __('Button next','rhc'),
				//'default'		=> __('event','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'type' 			=> 'subtitle',
				'label'			=> __('Header icons','rhc')
			),
			(object)array(
				'id'			=> 'cal_buttonicons_prev',
				'type' 			=> 'text',
				'label'			=> __('Button previous','rhc'),
				'default'		=> 'circle-triangle-w',
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_buttonicons_next',
				'type' 			=> 'text',
				'label'			=> __('Button previous','rhc'),
				'default'		=> 'circle-triangle-e',
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'type' 			=> 'clear'
			),
			(object)array(
				'type' 			=> 'subtitle',
				'label'			=> __('Month view','rhc')
			),
			(object)array(
				'id'		=> 'cal_week_mode',
				'label'		=> __('Week mode','rhc'),
				'type'		=> 'select',
				'default'	=> 'fixed',
				'options'	=> array(
					'fixed'		=> __('Fixed','rhc'),
					'liquid'	=> __('Liquid','rhc'),
					'variable'	=> __('Variable','rhc')
				),
				'description'	=> __('Determines the number of weeks displayed in the calendar.','rhc'),
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
			),				
			(object)array(
				'type' 			=> 'clear'
			),
			(object)array(
				'type' 			=> 'subtitle',
				'label'			=> __('Agenda view (week and day view)','rhc')
			),
			(object)array(
				'id'		=> 'cal_alldayslot',
				'label'		=> __('Show all-day slot','rhc'),
				'type'		=> 'yesno',
				'default'	=> '1',
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
			),					
			(object)array(
				'id'			=> 'cal_alldaytext',
				'type' 			=> 'text',
				'label'			=> __('all-day label','rhc'),
				'el_properties' => array('class'=>'widefat'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'	=> 'cal_firsthour',
				'type'	=> 'range',
				'label'	=> __('First hour','rhc'),
				'min'	=> 0,
				'max'	=> 24,
				'step'	=> 1,
				'default'=> 6,
				'save_option'=>true,
				'load_option'=>true
			),	
			(object)array(
				'id'	=> 'cal_slotminutes',
				'type'	=> 'range',
				'label'	=> __('Slot minutes','rhc'),
				'min'	=> 0,
				'max'	=> 60,
				'step'	=> 1,
				'default'=> 30,
				'save_option'=>true,
				'load_option'=>true
			),						
			(object)array(
				'id'	=> 'cal_mintime',
				'type'	=> 'range',
				'label'	=> __('Minimun displayed time','rhc'),
				'min'	=> 0,
				'max'	=> 24,
				'step'	=> 1,
				'default'=> 0,
				'save_option'=>true,
				'load_option'=>true
			),						
			(object)array(
				'id'	=> 'cal_maxtime',
				'type'	=> 'range',
				'label'	=> __('Maximun displayed time','rhc'),
				'min'	=> 0,
				'max'	=> 24,
				'step'	=> 1,
				'default'=> 24,
				'save_option'=>true,
				'load_option'=>true
			),	
			(object)array(
				'type' 			=> 'clear'
			),			
			(object)array(
				'type' 			=> 'subtitle',
				'label'			=> __('Event list view','rhc'),
				'description'	=> sprintf('<p>%s</p><p>%s</p><p>%s</p><p>%s</p><p>%s</p><p>%s</p>',
					__('<b>Show same date header:</b>  choose yes to show a label with the date before events on the same date.','rhc'),
					__('<b>Upcoming only:</b>  choose yes if you only want to display upcoming events.','rhc'),
					__('<b>Reverse order:</b>  choose yes to invert the order of events','rhc'),
					__('<b>Months ahead:</b>  By default the events view show up to one month of upcoming events.  Use this option to show more events.','rhc'),
					__('<b>Max displayed events:</b>  Optionally limit the number of events displayed.','rhc'),
					__('<b>Show multi month events:</b> By default long spanning events that started on a diferent month will not be displayed in the event list.  Check yes to show them.','rhc')
				)				
			),
			(object)array(
				'id'			=> 'cal_eventlistshowheader',
				'type' 			=> 'yesno',
				'label'			=> __('Show same date header','rhc'),
				'default'		=> '1',
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_eventlistnoeventstext',
				'type' 			=> 'text',
				'label'			=> __('No events text','rhc'),
				'default'		=> __('No upcoming events in this date range','rhc'),
				'el_properties' => array('class'=>'widefat'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_eventlistupcoming',
				'type' 			=> 'yesno',
				'label'			=> __('Upcoming only','rhc'),
				'default'		=> '0',
				'save_option'=>true,
				'load_option'=>true
			),	
			(object)array(
				'id'			=> 'cal_eventlistreverse',
				'type' 			=> 'yesno',
				'label'			=> __('Reverse order','rhc'),
				'default'		=> '0',
				'save_option'=>true,
				'load_option'=>true
			),	
			(object)array(
				'id'			=> 'cal_eventlistoutofrange',
				'type' 			=> 'yesno',
				'label'			=> __('Show multi month events','rhc'),
				'default'		=> '0',
				'save_option'=>true,
				'load_option'=>true
			),			
			(object)array(
				'id'			=> 'cal_eventlistmonthsahead',
				'type' 			=> 'text',
				'label'			=> __('Months ahead to show(optional)','rhc'),
				'el_properties' => array('class'=>'widefat'),
				'save_option'=>true,
				'load_option'=>true
			),	
			(object)array(
				'id'			=> 'cal_eventlist_display',
				'type' 			=> 'text',
				'label'			=> __('Max displayed events(optional)','rhc'),
				'el_properties' => array('class'=>'widefat'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'type' 			=> 'clear'
			),
			(object)array(
				'type' 			=> 'subtitle',
				'label'			=> __('Tooltip behaviour','rhc')
			),
			(object)array(
				'id'			=> 'cal_tooltip_target',
				'type' 			=> 'select',
				'label'			=> __('Tooltip links target','rhc'),
				'default'		=> 0,
				'options'		=> array(
					'_self'		=> __('_self','rhc'),
					'_blank'	=> __('_blank','rhc'),
					'_top'		=> __('_top','rhc'),
					'_parent'	=> __('_parent','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_tooltip_disable_title_link',
				'type' 			=> 'yesno',
				'label'			=> __('Disable title link','rhc'),
				'default'		=> '0',
				'save_option'=>true,
				'load_option'=>true
			),			
			(object)array(
				'type' 			=> 'clear'
			),
			(object)array(
				'type' 			=> 'subtitle',
				'label'			=> __('icalendar button','rhc')
			),
			(object)array(
				'id'		=> 'cal_icalendar',
				'label'		=> __('Enable icalendar button','rhc'),
				'type'		=> 'yesno',
				//'hidegroup'	=> '#icalendar_group',
				'default'	=> '1',
				'el_properties'	=> array(),
				'save_option'=>true,
				'load_option'=>true
			),		
			(object)array(
				'id'	=> 'icalendar_group',
				'type'=>'div_start'
			),									
			(object)array(
				'id'	=> 'cal_icalendar_width',
				'type'	=> 'range',
				'label'	=> __('Dialog width','rhc'),
				'min'	=> 0,
				'max'	=> 1024,
				'step'	=> 1,
				'default'=> 460,
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_icalendar_button',
				'type' 			=> 'text',
				'label'			=> __('Button label','rhc'),
				'el_properties'	=> array('class'=>'widefat'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_icalendar_title',
				'type' 			=> 'text',
				'label'			=> __('Dialog title','rhc'),
				'el_properties'	=> array('class'=>'widefat'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_icalendar_description',
				'type' 			=> 'textarea',
				'el_properties'	=> array('class'=>'widefat'),
				'label'			=> __('Dialog description','rhc'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_icalendar_align',
				'type' 			=> 'select',
				'label'			=> __('Alignment','rhc'),
				'default'		=> 0,
				'options'		=> array(
					'left'		=> __('Left','rhc'),
					'center'	=> __('Center','rhc'),
					'right'		=> __('Right','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'type'=>'div_end'
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
		//-- List of events --------------------------
/*
		$i++;
		$t[$i]->id 			= 'rhc-events-list'; 
		$t[$i]->label 		= __('List of events','rhc');
		$t[$i]->right_label	= __('Layout settings, date format','rhc');
		$t[$i]->page_title	= __('List of events','rhc');
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		$t[$i]->options = array(
			(object)array(
				'id'			=> 'rhc-list-layout',
				'type' 			=> 'textarea',
				'label'			=> __('Event list layout','rhc'),
				'el_properties' => array('rows'=>'15','cols'=>'50'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'		=> 'rhc_load_default_list',
				'rel'		=> '#rhc-list-layout',
				'type'		=> 'callback',
				'callback'	=> array($this,'load_default'),
				'label'	=> __('Load default event list content template','rhc'),
				'class' => 'button-secondary rhc-load-default-layout'
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
*/			
		//-- Date formatting ----
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-shortcode-layout'; 
		$t[$i]->label 		= __('Date/time format','rhc');
		$t[$i]->right_label	= __('Customize date and time formats','rhc');
		$t[$i]->page_title	= __('Date/time format','rhc');
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;		
		$t[$i]->options = array(
			(object)array(
				'type'=>'preview',
				'path'=>RHC_URL.'images/preview/dateformat/',
				'items'=>array(
					(object)array(
						'src'=> 'titleformat_month.jpg',
						'focus_target'=>'#cal_titleformat_month',
						'label'=>'',
						'description'=>''
					),
					(object)array(
						'src'=> 'columnformat_month.jpg',
						'focus_target'=>'#cal_columnformat_month',
						'label'=>'',
						'description'=>''
					),
					(object)array(
						'src'=> 'timeformat_month.jpg',
						'focus_target'=>'#cal_timeformat_month',
						'label'=>'',
						'description'=>''
					)
				)
			),		
			(object)array(
				'type'=>'subtitle',
				'label'=>__('Calendar month view','rhc')
			),			
			(object)array(
				'id'			=> 'cal_titleformat_month',
				'type' 			=> 'text',
				'label'			=> __('Month view title','rhc'),
				'el_properties' => array(
					'class'=>'widefat rhc_dateformat',
					'rel'=> __('MMMM yyyy','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),			
			(object)array(
				'id'			=> 'cal_columnformat_month',
				'type' 			=> 'text',
				'label'			=> __('Column label','rhc'),
				'el_properties' => array(
					'class'=>'widefat rhc_dateformat',
					'rel'=> __('ddd','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),			
			(object)array(
				'id'			=> 'cal_timeformat_month',
				'type' 			=> 'text',
				'label'			=> __('Event time format','rhc'),
				'el_properties' => array(
					'class'=>'widefat rhc_dateformat',
					'rel'=> __('h(:mm)t','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),	
			(object)array(
				'type'=>'clear'
			),	
			(object)array(
				'type'=>'preview',
				'path'=>RHC_URL.'images/preview/dateformat/',
				'items'=>array(
					(object)array(
						'src'=> 'titleformat_week.jpg',
						'focus_target'=>'#cal_titleformat_week',
						'label'=>'',
						'description'=>''
					),
					(object)array(
						'src'=> 'columnformat_week.jpg',
						'focus_target'=>'#cal_columnformat_week',
						'label'=>'',
						'description'=>''
					),
					(object)array(
						'src'=> 'timeformat_week.jpg',
						'focus_target'=>'#cal_timeformat_week',
						'label'=>'',
						'description'=>''
					),
					(object)array(
						'src'=> 'axisformat.jpg',
						'focus_target'=>'#cal_axisformat',
						'label'=>'',
						'description'=>''
					)
				)
			),					
			(object)array(
				'type'=>'subtitle',
				'label'=>__('Calendar week view','rhc')
			),				
			(object)array(
				'id'			=> 'cal_titleformat_week',
				'type' 			=> 'text',
				'label'			=> __('Week view title','rhc'),
				'el_properties' => array(
					'class'=>'widefat rhc_dateformat',
					'rel'=> __("MMM d[ yyyy]{ '&#8212;'[ MMM] d yyyy}",'rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),	
			(object)array(
				'id'			=> 'cal_columnformat_week',
				'type' 			=> 'text',
				'label'			=> __('Week view column','rhc'),
				'el_properties' => array(
					'class'=>'widefat rhc_dateformat',
					'rel'=> __('ddd M/d','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),			
			(object)array(
				'id'			=> 'cal_timeformat_week',
				'type' 			=> 'text',
				'label'			=> __('Event time format','rhc'),
				'el_properties' => array(
					'class'=>'widefat rhc_dateformat',
					'rel'=> __('h:mm{ - h:mm}','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_axisformat',
				'type' 			=> 'text',
				'label'			=> __('Axis format (Also affects day view)','rhc'),
				'el_properties' => array(
					'class'=>'widefat rhc_dateformat',
					'rel'=>__('h(:mm)tt','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),				
			(object)array(
				'type'=>'clear'
			),	
			(object)array(
				'type'=>'preview',
				'path'=>RHC_URL.'images/preview/dateformat/',
				'items'=>array(
					(object)array(
						'src'=> 'titleformat_day.jpg',
						'focus_target'=>'#cal_titleformat_day',
						'label'=>'',
						'description'=>''
					),
					(object)array(
						'src'=> 'columnformat_day.jpg',
						'focus_target'=>'#cal_columnformat_day',
						'label'=>'',
						'description'=>''
					),
					(object)array(
						'src'=> 'timeformat_day.jpg',
						'focus_target'=>'#cal_timeformat_day',
						'label'=>'',
						'description'=>''
					)
				)
			),					
			(object)array(
				'type'=>'subtitle',
				'label'=>__('Calendar day view','rhc')
			),					
			(object)array(
				'id'			=> 'cal_titleformat_day',
				'type' 			=> 'text',
				'label'			=> __('Day view title','rhc'),
				'el_properties' => array(
					'class'=>'widefat rhc_dateformat',
					'rel'=> __('dddd, MMM d, yyyy','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			)	,					
			(object)array(
				'id'			=> 'cal_columnformat_day',
				'type' 			=> 'text',
				'label'			=> __('Day view column','rhc'),
				'el_properties' => array(
					'class'=>'widefat rhc_dateformat',
					'rel'=>__('dddd M/d','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),					
			(object)array(
				'id'			=> 'cal_timeformat_day',
				'type' 			=> 'text',
				'label'			=> __('Event time format','rhc'),
				'el_properties' => array(
					'class'=>'widefat rhc_dateformat',
					'rel'=>__('h:mm{ - h:mm}','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'type'=>'clear'
			),	
			
			(object)array(
				'type'=>'preview',
				'path'=>RHC_URL.'images/preview/dateformat/',
				'items'=>array(
					(object)array(
						'src'=> 'eventlistdateformat.jpg',
						'focus_target'=>'#cal_eventlistdateformat',
						'label'=>'',
						'description'=>''
					),
					(object)array(
						'src'=> 'eventliststartdateformat.jpg',
						'focus_target'=>'#cal_eventliststartdateformat',
						'label'=>'',
						'description'=>''
					)
				)
			),										
			(object)array(
				'type'=>'subtitle',
				'label'=>__('Calendar event list view','rhc')
			),					
			(object)array(
				'id'			=> 'cal_eventlistdateformat',
				'type' 			=> 'text',
				'label'			=> __('Main date format','rhc'),
				'el_properties' => array(
					'class'=>'widefat rhc_dateformat',
					'rel'=>__('dddd MMMM d, yyyy','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),					
			(object)array(
				'id'			=> 'cal_eventliststartdateformat',
				'type' 			=> 'text',
				'label'			=> __('Start/end date format','rhc'),
				'el_properties' => array(
					'class'=>'widefat rhc_dateformat',
					'rel'=>__('dddd MMMM d, yyyy. h:mmtt','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),		
			(object)array(
				'id'			=> 'cal_eventliststartdateformat_allday',
				'type' 			=> 'text',
				'label'			=> __('Start/end date format (All day)','rhc'),
				'el_properties' => array(
					'class'=>'widefat rhc_dateformat',
					'rel'=>__('dddd MMMM d, yyyy.','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'type'=>'clear'
			),		
			(object)array(
				'type'=>'preview',
				'path'=>RHC_URL.'images/preview/dateformat/',
				'items'=>array(
					(object)array(
						'src'=> 'tooltip_startdate.jpg',
						'focus_target'=>'#cal_tooltip_startdate',
						'label'=>'',
						'description'=>''
					),
					(object)array(
						'src'=> 'tooltip_enddate.jpg',
						'focus_target'=>'#cal_tooltip_enddate',
						'label'=>'',
						'description'=>''
					)
				)
			),													
			(object)array(
				'type'=>'subtitle',
				'label'=>__('Event popup (click on calendar event)','rhc')
			),
			(object)array(
				'id'			=> 'cal_tooltip_startdate',
				'type' 			=> 'text',
				'label'			=> __('Start date','rhc'),
				'el_properties' => array(
					'class'=>'widefat rhc_dateformat',
					'rel'=>__('ddd MMMM d, yyyy h:mm TT','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_tooltip_startdate_allday',
				'type' 			=> 'text',
				'label'			=> __('Start date(all-day)','rhc'),
				'el_properties' => array(
					'class'=>'widefat rhc_dateformat',
					'rel'=>__('ddd MMMM d, yyyy','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'			=> 'cal_tooltip_enddate',
				'type' 			=> 'text',
				'label'			=> __('End date','rhc'),
				'el_properties' => array(
					'class'=>'widefat rhc_dateformat',
					'rel'=>__('ddd MMMM d, yyyy h:mm TT','rhc')
				),
				'save_option'=>true,
				'load_option'=>true
			)	,
			(object)array(
				'id'			=> 'cal_tooltip_enddate_allday',
				'type' 			=> 'text',
				'label'			=> __('End date(all-day)','rhc'),
				'el_properties' => array(
					'class'=>'widefat rhc_dateformat',
					'rel'=>__('ddd MMMM d, yyyy','rhc')
				),
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
		//-- Permalink settings -----------------------		
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-shortcode-layout'; 
		$t[$i]->label 		= __('Shortcode templates','rhc');
		$t[$i]->right_label	= __('Customize shortcode output','rhc');
		$t[$i]->page_title	= __('Shortcode templates','rhc');
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		$t[$i]->options = array(
			/*
			(object)array(
				'id'			=> 'rhc-event-layout',
				'type' 			=> 'textarea',
				'label'			=> __('Event content layout','rhc'),
				'el_properties' => array('rows'=>'15','cols'=>'50'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'		=> 'rhc_load_default_event',
				'rel'		=> '#rhc-event-layout',
				'type'		=> 'callback',
				'callback'	=> array($this,'load_default'),
				'label'	=> __('Load default event content template','rhc'),
				'class' => 'button-secondary rhc-load-default-layout'
			),	
			*/
			(object)array(
				'id'			=> 'rhc-venue-layout',
				'type' 			=> 'textarea',
				'label'			=> __('Venue layout','rhc'),
				'description'	=> __('Customize the ouput of the <b>[venue]</b> shortcode.','rhc'),
				'el_properties' => array('rows'=>'15','cols'=>'50'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'		=> 'rhc_load_default_venue',
				'rel'		=> '#rhc-venue-layout',
				'type'		=> 'callback',
				'callback'	=> array($this,'load_default'),
				'label'	=> __('Load default venue template','rhc'),
				'class' => 'button-secondary rhc-load-default-layout'
			),
			(object)array(
				'id'			=> 'rhc-organizer-layout',
				'type' 			=> 'textarea',
				'description'	=> __('Customize the ouput of the <b>[organizer]</b> shortcode.','rhc'),				
				'label'			=> __('Organizer layout','rhc'),
				'el_properties' => array('rows'=>'15','cols'=>'50'),
				'save_option'=>true,
				'load_option'=>true
			),
			(object)array(
				'id'		=> 'rhc_load_default_organizer',
				'rel'		=> '#rhc-organizer-layout',
				'type'		=> 'callback',
				'callback'	=> array($this,'load_default'),
				'label'	=> __('Load default organizer template','rhc'),
				'class' => 'button-secondary rhc-load-default-layout'
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
	
	function head(){
		wp_print_scripts( 'fc_dateformat_helper' );
?>
<style>
.pt-option-load-default {
	height:30px;
}
.pt-option-load-default input {
	float:left;
}
.pt-option-load-default img {
	width:21px;
	height:21px;
}
.load-default-status {
	display:none;
}
.rc_dateformat_helper {
	display:block;
	position:absolute;
	left:40px;
}
.rc_dateformat_helper_content {
	width:350px;
	padding:10px;
	z-index:8
}

.helper-arrow-holder {
	position:relative;
	top:-20px;
	left:48px;
}
.helper-arrow,
.helper-arrow-border {
	border-color: transparent  transparent #f5f5f5 transparent ;
    border-style: solid;
    border-width: 12px;
    cursor: pointer;
    font-size: 0;
    left: 0;
    line-height: 0;
    margin: 0 auto;
    position: absolute;
    right: 0;
    top: 0;
    width: 0;
    z-index: 9;
	display:block;
}
.helper-arrow {
    left: -22px;
    right: auto;
}
.helper-arrow-border {
    left: -22px;
    top: -2px;
    border-style: solid;
    border-width: 12px;
    z-index: 5;	
	margin:0;
}
.rc_dateformat_footer {
	padding-top:5px;
	text-align:right;
}
.pt-option {
	position:relative;
}
.rc_dateformat_preview {
	padding:5px;
	margin:5px 0 5px 0;
	font-weight:bold;
	font-size:1.2em;
}
</style>
<script type='text/javascript'>

jQuery(document).ready(function($){ 
	$.fn.extend({
		insertAtCaret: function(myValue){
		  	var obj;
		  	if( typeof this[0].name !='undefined' ) obj = this[0];
		  	else obj = this;
		
		  	if ($.browser.msie) {
		    	obj.focus();
		    	sel = document.selection.createRange();
		    	sel.text = myValue;
		    	obj.focus();
		    }
		 	else if ($.browser.mozilla || $.browser.webkit) {
		    	var startPos = obj.selectionStart;
		    	var endPos = obj.selectionEnd;
		    	var scrollTop = obj.scrollTop;
		    	obj.value = obj.value.substring(0, startPos)+myValue+obj.value.substring(endPos,obj.value.length);
		    	obj.focus();
		    	obj.selectionStart = startPos + myValue.length;
		    	obj.selectionEnd = startPos + myValue.length;
		    	obj.scrollTop = scrollTop;
		  	} else {
		    	obj.value += myValue;
		    	obj.focus();
		   	}
			return this.each(function(){});
		}
	});
	$('.rhc-load-default-layout').live('click',function(e){
		$(this).parent().find('.load-default-status').fadeIn();
		var args = {
			action: 'rhc_default_template',
			id: $(this).attr('rel')
		};
		
		$.post(ajaxurl,args,function(data){
			if(data.R=='OK'){
				$(data.DATA.id).val(data.DATA.value);
			}else{
				alert('Error loading template');
			}
			$('.load-default-status').fadeOut();
		},'json');
	});
		
	$('.rhc_dateformat').each(function(i,inp){
		var _id = $(this).attr('id');
		$('#dateformat_helper_base')
			.clone()
			.attr('id', _id+'_helper' )
			.attr('rel',_id)
			.hide()
			.appendTo( $(this).parent() )
		;
		
		$(this).parent().find('.rhc_button').click(function(e){
			$('#'+_id).insertAtCaret( $(this).val() ).trigger('change');
		});
		
		$(this).parent().find('.rhc_button_default').click(function(e){
			$('#'+_id).val( $('#'+_id).attr('rel') ).trigger('change');	
		});
		
		$(this).parent().find('.rhc_button_space').click(function(e){
			$('#'+_id).insertAtCaret( ' ' ).trigger('change');
		});
		
		$(this).parent().find('.rhc_button_clear').click(function(e){
			$('#'+_id).val('').trigger('change');	
		});
		
		$(this).parent().find('.rhc_button_close').click(function(e){
			close_helper( $('.rc_dateformat_helper') );
		});
		
		$(this).change(function(e){
			var _now = new Date();
			var _formatted = $.fullCalendar.formatDate(_now,$(this).val());
			$(this).parent().find('.rc_dateformat_preview').html(_formatted);	
		});
		
		$(this).focus(function(e){
			close_helper( $('.rc_dateformat_helper') );
			open_helper( $(this).parent().find('.rc_dateformat_helper') );
			$(this).trigger('change');
		});
	});
	
});

function open_helper( helper ){
	
	helper
		//.css('opacity',0.2)
		//.css('margin-top',-10)
		.show()
		//.animate({opacity:1,'margin-top':0})
		;
}

function close_helper( helper ){
	helper.hide();
}
</script>
<?php	
	}
	
	function body(){
	//a template for the tooltip helper on date formats
		$formats = array(


			'd'		=> __('date number','rhc'),
			'dd'	=> __('date number, 2 digits','rhc'),
			'ddd'	=> __('date name, short','rhc'),
			'dddd'	=> __('date name, full','rhc'),
			'M'		=> __('month number','rhc'),
			'MM'	=> __('month number, 2 digits','rhc'),
			'MMM'	=> __('month name, short','rhc'),
			'MMMM'	=> __('month name, full','rhc'),
			'yy'	=> __('year, 2 digits','rhc'),
			'yyyy'	=> __('year, 4 digits','rhc'),
			'h'		=> __('hours, 12 hour format','rhc'),
			'hh'	=> __('hours, 12 hour format, 2 digits','rhc'),
			'H'		=> __('hours, 24 hour format','rhc'),
			'HH'	=> __('hours, 24 hour format, 2 digits','rhc'),		
			":"	=> __("colon",'rhc'),
			'm'		=> __('minutes','rhc'),
			'mm'	=> __('minutes, 2 digits','rhc'),	
			't'		=> sprintf(__("%s or %s",'rhc'),'a','p'),
			'tt'	=> sprintf(__("%s or %s",'rhc'),'am','pm'),
			'T'		=> sprintf(__("%s or %s",'rhc'),'A','P'),
			'TT'	=> sprintf(__("%s or %s",'rhc'),'AM','PM'),
			'u'		=> __("ISO8601 format",'rhc'),
			"''"	=> __("Single quote",'rhc'),
			","	=> __("comma",'rhc'),
			"/"	=> __("forward slash",'rhc')	,
			"."	=> __("dot",'rhc')
		);
?>
<div style="display:none;">
	<div id="dateformat_helper_base" class="rc_dateformat_helper">
		<div class="helper-arrow-holder">
			<div class="helper-arrow"></div>
			<div class="helper-arrow-border"></div>
		</div>
		
		<div class="rc_dateformat_helper_content postbox">
			<div class="rc_dateformat_preview_cont">
				<label class="rc_preview_label"><?php _e('Preview:','rhc')?></label>
				<div class="rc_dateformat_preview postbox"></div>
			</div>
			<div class="rc_dateformat_buttons">
				
				<?php foreach($formats as $format => $title):?>
				<input type="button" class="rhc_button rhc_<?php echo md5($format)?>"  title="<?php echo $title?>" value="<?php echo $format?>" rel="<?php echo $format?>" />
				<?php endforeach;?>
				<input type="button" class="rhc_button_default" title="<?php _e('default format','rhc')?>" value="<?php _e('default','rhc')?>"  />
				<input type="button" class="rhc_button_clear" title="<?php _e('clear value','rhc')?>" value="<?php _e('clear','rhc')?>"  />
				<input type="button" class="rhc_button_space" title="<?php _e('space','rhc')?>" value="&nbsp;&nbsp;&nbsp;<?php _e('space','rhc')?>&nbsp;&nbsp;&nbsp;" />
			</div>
			<div class="rc_dateformat_footer">
				<input type="button" class="button-secondary rhc_button_close" value="<?php _e('done','rhc')?>"  />
			</div>
		</div>
	</div>
</div>
<?php	
	}
	
	function load_default($tab,$i,$o){
		$id = $o->id;
		$load_image = RHC_URL.'options-panel/css/images/spinner_32x32.gif';
		return sprintf("<div class=\"pt-option pt-option-load-default\"><input rel=\"%s\" class=\"%s\" type=\"button\" id=\"%s\" name=\"%s\" value=\"%s\"  /><span class=\"load-default-status\"><img src=\"%s\" /></span></div>",$o->rel,$o->class, $id, $id, $o->label, $load_image );
	}		
	
	function wp_ajax_rhc_default_template(){
		$id = $_REQUEST['id'];
		$value = '';
		
		if($id=='#rhc-list-layout'){
			ob_start();
			require_once RHC_PATH.'templates/event_list_content.php';		
			$value = ob_get_contents();
			ob_end_clean();
		}else if($id=='#rhc-event-layout'){
			ob_start();
			require_once RHC_PATH.'templates/filter_event_content.php';		
			$value = ob_get_contents();
			ob_end_clean();
		}else if($id=='#rhc-venue-layout'){
			ob_start();
			require_once RHC_PATH.'templates/shortcode_venues_template_default.php';		
			$value = ob_get_contents();
			ob_end_clean();
		}else if($id=='#rhc-organizer-layout'){
			ob_start();
			require_once RHC_PATH.'templates/shortcode_organizers_template_default.php';		
			$value = ob_get_contents();
			ob_end_clean();
		}
		
		$r = array(
			'R'=>'OK',
			'MSG'=>'',
			'DATA'=> array(
				'id'=>$id,
				'value'=>$value
			)
		);
		die(json_encode($r));
	}
	
	function get_pages_for_dropdown(){
		global $wpdb;
		//allow drafts in the templates that can be chosen, so that the admin can use a page and leave it as draft.
		$sql = "SELECT ID as value, post_title as label FROM {$wpdb->posts} WHERE post_type='page' AND post_status IN ('draft','publish') ORDER BY post_title ASC";
		$wpdb->query($sql);
		if($wpdb->num_rows>0){
			$arr = array(''=>__('--choose--','rhc'));
			foreach($wpdb->last_result as $r){
				$arr[$r->value]=$r->label;
			}
			return $arr;
		}else{
			return array(''=>__('No options','rhc'));
		}
	}
}
?>