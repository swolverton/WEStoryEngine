<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/
class rhc_css_options {
	function rhc_css_options($args=array()){
		add_filter('rhcss-editor-options-'.$args['plugin_id'], array(&$this,'pop_tab_css_editor_options'),10,1);
		if($args['admin_bar']){
			add_action('init',array(&$this,'init'),9999 );
		}
		add_action('admin_head-events_page_rhc-css-options',array(&$this,'admin_head_options'));
	}

	function admin_head_options(){
?>
<style>
.pt-option-yesno .pt-label {
display:inline-block;
min-width:220px;
}
</style>
<?php
	}
	
	
	function pop_tab_css_editor_options($options){
		//add more options to the CSS Editor Tab
		//calendar page
		//venue page
		//organizer page
		//upcoming events widget url
		global $wpdb,$rhc_plugin;
		$options[]=(object)array(
						'type'			=> 'clear'
					);	
		$options[]=(object)array(
						'type'			=> 'subtitle',
						'label'			=> __('Editable sections urls','rhc')
					);	
		//-- Calendar url ----------------------------------------------------------------------		
		$options[]=	(object)array(
				'id'	=> 'rhc_css_calendar_url',
				'type'	=>'text',
				'label'	=> __('Calendar URL','rhc'),
				'el_properties'	=> array('class'=>'widefat'),
				'description' => __('Specify the url of a page where the calendarizeit shortcode have been implemented.  If left blank the editor will try to determine it automatically.','rhc'),
				'default' => $this->get_calendar_url(),
				'save_option'=>true,
				'load_option'=>true
			);
		//------------------------------------------------------------------------

		$options[]=	(object)array(
				'id'	=> 'rhc_css_event_url',
				'type'	=>'text',
				'label'	=> __('Single event URL','rhc'),
				'el_properties'	=> array('class'=>'widefat'),
				'description' => __('URL of a single event page.  If left blank the editor will try to determine it automatically.','rhc'),
				'default' => $this->get_event_url(),
				'save_option'=>true,
				'load_option'=>true
			);	
			
		$options[]=	(object)array(
				'id'	=> 'rhc_css_venue_url',
				'type'	=>'text',
				'label'	=> __('Venue URL','rhc'),
				'el_properties'	=> array('class'=>'widefat'),
				'description' => __('URL of a venue page.  If left blank the editor will try to determine it automatically.','rhc'),
				'default' => $this->get_venue_url(),
				'save_option'=>true,
				'load_option'=>true
			);	
			
		$options[]=	(object)array(
				'id'	=> 'rhc_css_organizer_url',
				'type'	=>'text',
				'label'	=> __('Organizer URL','rhc'),
				'el_properties'	=> array('class'=>'widefat'),
				'description' => __('URL of a organizer page.  If left blank the editor will try to determine it automatically.','rhc'),
				'default' => $this->get_organizer_url(),
				'save_option'=>true,
				'load_option'=>true
			);	
			
		$options[]=	(object)array(
				'id'	=> 'rhcw_upcoming_default',
				'type'	=>'text',
				'label'	=> __('Upcoming Events Default URL','rhc'),
				'el_properties'	=> array('class'=>'widefat'),
				'description' => __('Specify the URL of a page containing the Upcoming Events widget "Default" model.','rhc'),
				'default' => '',
				'save_option'=>true,
				'load_option'=>true
			);	
			
		$options[]=	(object)array(
				'id'	=> 'rhcw_upcoming_agenda',
				'type'	=>'text',
				'label'	=> __('Upcoming Events Agenda Like URL','rhc'),
				'el_properties'	=> array('class'=>'widefat'),
				'description' => __('Specify the URL of a page containing the Upcoming Events widget "Agenda Like" model.','rhc'),
				'default' => '',
				'save_option'=>true,
				'load_option'=>true
			);	
			
		$options[]=	(object)array(
				'id'	=> 'rhcw_upcoming_agenda_b',
				'type'	=>'text',
				'label'	=> __('Upcoming Events Agenda Like B URL','rhc'),
				'el_properties'	=> array('class'=>'widefat'),
				'description' => __('Specify the URL of a page containing the Upcoming Events widget "Agenda Like B" model.','rhc'),
				'default' => '',
				'save_option'=>true,
				'load_option'=>true
			);		
		return $options;
	}
		
	function init(){
		global $rhc_plugin;
		if( '1'!=$rhc_plugin->get_option('enable_css_editor','1',true) )return;//editor is not enabled. do not add items to the menu.
	
		// Provide access to the css editor
		//-- create editor quick access links
		$venue_url=$this->get_venue_url();
		if(trim($venue_url)!=''){
			$venue_url = $this->addURLParameter($this->get_venue_url(), 'rhc_edit', 'venue_page');
		}	
		
		new admin_bar_editor_access(array(
			'nodes'=>array(
				array(
					'id' => 'calendarize-it', 
					'title' => 'Calendarize it!', 
					'parent' => 'rh-css-editor-root',
					'href'		=> '#',
					'meta'		=> array('onclick'=>'javascript:jQuery(this).parent().toggleClass("hover");')
				),
				array(
				 	'id' 		=> 'calendarize-it-general', 
					'title' 	=> __('Calendar','rhc'), 
					'parent' 	=> 'calendarize-it',
					'href'		=> '#',
					'meta'		=> array('onclick'=>'javascript:jQuery(this).parent().toggleClass("hover");')
				),
				array(
				 	'id' 		=> 'calendarize-it-frame', 
					'title' 	=> __('Calendar frame','rhc'), 
					'parent' 	=> 'calendarize-it-general',
					'href'		=> $this->addURLParameter($this->get_calendar_url(), 'rhc_edit', 'calendar') 
				),
				array(
					'id' 		=> 'calendarize-it-all-views', 
					'title' 	=> __('All views','rhc'), 
					'parent' 	=> 'calendarize-it-general',
					'href'		=> $this->addURLParameter($this->get_calendar_url(), 'rhc_edit', 'all_views') 
				),
				array(
					'id' 		=> 'calendarize-it-month', 
					'title' 	=> __('Calendar month view','rhc'), 
					'parent' 	=> 'calendarize-it-general',
					'href'		=> $this->addURLParameter($this->get_calendar_url(), 'rhc_edit', 'month_view') 
				),
				array(
					'id' => 'calendarize-it-agenda', 
					'title' => __('Agenda views','rhc'), 
					'parent' => 'calendarize-it-general',
					'href'		=> $this->addURLParameter($this->get_calendar_url(), 'rhc_edit', 'agenda_view') 
				),
								
				array(
					'id' => 'calendarize-it-list', 
					'title' => __('Calendar event list view','rhc'), 
					'parent' => 'calendarize-it-general',
					'href'		=> $this->addURLParameter($this->get_calendar_url(), 'rhc_edit', 'event_list') 
				),
								
				array(
					'id' => 'calendarize-it-detail-box', 
					'title' => __('Detail boxes (default)','rhc'), 
					'parent' => 'calendarize-it',
					'href'	=> $this->addURLParameter($this->get_event_url(), 'rhc_edit', 'detail_box')
				),

				array(
					'id' => 'calendarize-it-page-event', 
					'title' => __('Single event page','rhc'), 
					'parent' => 'calendarize-it',
					'href'	=> '#',
					'meta'		=> array('onclick'=>'javascript:jQuery(this).parent().toggleClass("hover");')
				),

				array(
					'id' => 'calendarize-it-page-event-general', 
					'title' => __('Image and image frame','rhc'), 
					'parent' => 'calendarize-it-page-event',
					'href'	=> $this->addURLParameter($this->get_event_url(), 'rhc_edit', 'event_page')
				),

				array(
					'id' => 'calendarize-it-page-event-dbox', 
					'title' => __('Event Details Box','rhc'), 
					'parent' => 'calendarize-it-page-event',
					'href'	=> $this->addURLParameter($this->get_event_url(), 'rhc_edit', 'event_page_dbox')
				),				
				
				array(
					'id' => 'calendarize-it-page-event-vbox', 
					'title' => __('Venue Details box','rhc'), 
					'parent' => 'calendarize-it-page-event',
					'href'	=> $this->addURLParameter($this->get_event_url(), 'rhc_edit', 'event_page_vbox')
				),				
				
				array(
					'id' => 'calendarize-it-page-venue', 
					'title' => __('Venue page','rhc'), 
					'parent' => 'calendarize-it',
					'href'	=> $this->addURLParameter($this->get_venue_url(), 'rhc_edit', 'venue_page')
				),
				
				array(
					'id' => 'calendarize-it-page-organizer', 
					'title' => __('Organizer page','rhc'), 
					'parent' => 'calendarize-it',
					'href'	=> $this->addURLParameter($this->get_organizer_url(), 'rhc_edit', 'organizer_page')
				),
				
				array(
					'id' => 'rhcw-upcoming-root', 
					'title' => __('Upcoming events widget','rhc'), 
					'parent' => 'calendarize-it',
					'href'	=> '#',
					'meta'		=> array('onclick'=>'javascript:jQuery(this).parent().toggleClass("hover");')
				),
				
				array(
					'id' 		=> 'rhcw-upcoming-default', 
					'title' 	=> __('Default','rhc'), 
					'parent' 	=> 'rhcw-upcoming-root',//'rhc-widget-upcoming',
					'href'		=> $this->get_upcoming_widget_url('rhcw_upcoming_default')
				),
				
				array(
					'id' 		=> 'rhc-widget-upcoming-agenda', 
					'title' 	=> __('Agenda Like','rhc'), 
					'parent' 	=> 'rhcw-upcoming-root',
					'href'		=> $this->get_upcoming_widget_url('rhcw_upcoming_agenda')
				),
				
				array(
					'id' 		=> 'rhc-widget-upcoming-agenda-b', 
					'title' 	=> __('Agenda Like B','rhc'), 
					'parent' 	=> 'rhcw-upcoming-root',
					'href'		=> $this->get_upcoming_widget_url('rhcw_upcoming_agenda_b')
				)
				
			)
		));		
	}
	
	function get_upcoming_widget_url($model='rhcw_upcoming_default'){
		global $rhc_plugin;
		$url = $rhc_plugin->get_option($model,'',true);
		if($url!=''){
			return $this->addURLParameter($url, 'rhc_edit', $model);
		}
		return '';
	}
	
	function get_calendar_url(){
		global $wpdb,$rhc_plugin;
		$url = $rhc_plugin->get_option('rhc_css_calendar_url','',true);
		if( ''==$url ){
			$sql = "SELECT ID  FROM $wpdb->posts WHERE post_status=\"publish\" AND post_content LIKE \"%[calendarizeit%\" ORDER BY ID DESC LIMIT 1";
			$id = $wpdb->get_var($sql,0,0);						
			$url = get_permalink( $id );	
		}
		return $url;
	}
	
	function get_venue_url(){
		global $wpdb,$rhc_plugin;
		$url = $rhc_plugin->get_option('rhc_css_venue_url','',true);	
		if( ''==$url ){
			$terms = get_terms(RHC_VENUE,array('hide_empty'=>0));	
			if(is_array($terms) && count($terms)>0){
				$url = get_term_link($terms[0]);			
			}
		}
		return $url;
	}
	
	function get_organizer_url(){
		global $wpdb,$rhc_plugin;
		$url = $rhc_plugin->get_option('rhc_css_organizer_url','',true);	
		if( ''==$url ){
			$terms = get_terms(RHC_ORGANIZER,array('hide_empty'=>0));	
			if(is_array($terms) && count($terms)>0){
				$url = get_term_link($terms[0]);			
			}
		}
		return $url;
	}	
	
	function get_event_url(){
		global $wpdb,$rhc_plugin;
		$url = $rhc_plugin->get_option('rhc_css_event_url','',true);	
		if(''==trim($url)){
			$sql = "SELECT P.ID, P.post_content, P.post_excerpt FROM {$wpdb->posts} P WHERE P.post_type='".RHC_EVENTS."' AND P.post_status='publish' ORDER BY RAND() LIMIT 10";
			if($wpdb->query($sql) && $wpdb->num_rows>0){
				$o = (object)array(
					'post_ID'=>0,
					'score'=>0
				);
				foreach($wpdb->last_result as $row){
					$score = 0;
					if(trim($row->post_content)!=''){
						$score++;
					}				
					if(trim($row->post_excerpt)!=''){
						$score++;
					}	
					
					$boxes = get_post_meta($row->ID, 'postinfo_boxes', true);
					if( is_array($boxes) && count($boxes)>0 ){
						if( '1' == get_post_meta($row->ID, 'enable_postinfo', true) ){
							$score+=2;
						}
					}
					
					$venues = get_the_terms($row->ID, RHC_EVENTS);
					if( is_array($venues) && count($venues)>0 ){
						if( '1' == get_post_meta($row->ID, 'enable_venuebox', true) ){
							$score+=2;
						}
					}
					//enable_featuredimage
					//rhc_top_image
					if( intval(get_post_meta($row->ID, 'rhc_top_image', true)) > 0 ){
						if( '1' == get_post_meta($row->ID, 'enable_featuredimage', true) ){
							$score++;
						}					
					}					
					
					if( $score > $o->score ){
						$o->post_ID = $row->ID;
						$o->score = $score;
					}
				}
				
				if($o->post_ID>0){
					$url = get_permalink($o->post_ID);
				}
			}
		}
		return $url;	
	}
	
	function addURLParameter($url, $paramName, $paramValue) {
	     if(trim($url)=='')return '';
		 $url_data = parse_url($url);
	     if(!isset($url_data["query"])){
		 	$url_data["query"]="";
		 }
	     $params = array();
	     parse_str($url_data['query'], $params);
	     $params[$paramName] = $paramValue;
	     $url_data['query'] = http_build_query($params);
	     return $this->build_url($url_data);
	}

	function build_url($url_data) {
	    $url="";
	    if(isset($url_data['host']))
	    {
	        $url .= $url_data['scheme'] . '://';
	        if (isset($url_data['user'])) {
	            $url .= $url_data['user'];
	                if (isset($url_data['pass'])) {
	                    $url .= ':' . $url_data['pass'];
	                }
	            $url .= '@';
	        }
	        $url .= $url_data['host'];
	        if (isset($url_data['port'])) {
	            $url .= ':' . $url_data['port'];
	        }
	    }
	    $url .= $url_data['path'];
	    if (isset($url_data['query'])) {
	        $url .= '?' . $url_data['query'];
	    }
	    if (isset($url_data['fragment'])) {
	        $url .= '#' . $url_data['fragment'];
	    }
	    return $url;
	}		
}

?>