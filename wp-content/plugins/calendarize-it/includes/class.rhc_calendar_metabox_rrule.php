<?php

class rhc_calendar_metabox {
	var $uid=0;
	var $post_type;
	var $debug=false;
	function rhc_calendar_metabox($post_type='rhcevents',$debug=false){
		$this->debug = $debug;
		if(!class_exists('post_meta_boxes'))
			require_once('class.post_meta_boxes.php');		
		$this->post_type = $post_type;
		$this->metabox_meta_fields = array("fc_allday","fc_start","fc_start_time","fc_end","fc_end_time","fc_interval","fc_rrule","fc_end_interval","fc_dow_except","fc_color","fc_text_color","fc_click_link","fc_click_target","fc_exdate","fc_rdate");
		$this->fc_intervals = plugin_righthere_calendar::get_rrule_freq();
		$this->post_meta_boxes = new post_meta_boxes(array(
			'post_type'=>$post_type,
			'options'=>$this->metaboxes(),
			'styles'=>array('post-meta-boxes','rhc-admin','rhc-jquery-ui','calendarize-metabox','farbtastic','rhc-options'),
			'scripts'=>array('calendarize','rhc-admin','fechahora','calendarize-metabox','farbtastic','pop'),
			'metabox_meta_fields' =>  'calendar_metabox_meta_fields',
			'pluginpath'=>RHC_PATH
		));
		$this->post_meta_boxes->save_fields = $this->metabox_meta_fields;
		add_action('wp_ajax_calendarize_'.$post_type, array(&$this,'ajax_calendarize'));
		//----
		add_action('save_post', array(&$this,'save_post') );
	}
	
	function save_post($post_id){
		if(isset($_REQUEST['action']) && $_REQUEST['action']=='autosave')return;//leave autosave unhandled. some problems from delete_post_meta where the main post was getting the meta data deleted.
		$fc_range_start = '';
		$date = get_post_meta($post_id,'fc_start',true);
		if(trim($date)!=''){
			global $wpdb;
			$time = get_post_meta($post_id,'fc_start_time',true);		
			$time = trim($time)==''?'12:00 am':$time;
			$time = $this->parseTime($time);
			$sql = "SELECT COALESCE((SELECT DATE_FORMAT(STR_TO_DATE('$date $time','%Y-%m-%d %H:%i:%s'), '%Y-%m-%d %H:%i:%s')),'')";
			$datetime = $wpdb->get_var($sql,0,0);
			if(trim($datetime)!=''){
				$fc_range_start = $datetime;
				update_post_meta($post_id,'fc_start_datetime',$datetime);
			}
		}
		//--
		$fc_range_end = '';
		$date = get_post_meta($post_id,'fc_end',true);
		if(trim($date)!=''){
			global $wpdb;
			$time = get_post_meta($post_id,'fc_end_time',true);		
			$time = trim($time)==''?'12:00 am':$time;
			$time = $this->parseTime($time);
			$sql = "SELECT COALESCE((SELECT DATE_FORMAT(STR_TO_DATE('$date $time','%Y-%m-%d %H:%i:%s'), '%Y-%m-%d %H:%i:%s')),'')";
			$datetime = $wpdb->get_var($sql,0,0);
			if(trim($datetime)!=''){
				$fc_range_end = $datetime;
				update_post_meta($post_id,'fc_end_datetime',$datetime);
			}
		}		
		
		$duration = 0;
		if(!empty($fc_range_start) && !empty($fc_range_end)){
			$duration = intval( strtotime($fc_range_end) - strtotime($fc_range_start) );
		}
		//-- save repeat individually for friendly query
		delete_post_meta($post_id, 'fc_rdatetime');
		delete_post_meta($post_id, 'fc_range_start');
		delete_post_meta($post_id, 'fc_range_end');

		$fc_rdate = get_post_meta($post_id,'fc_rdate',true);
		if(trim($fc_rdate)!=''){
			$array_of_repeat_dates = explode(',',$fc_rdate);
			foreach($array_of_repeat_dates as $rdate){
				$sql = "SELECT COALESCE((SELECT DATE_FORMAT(STR_TO_DATE('$rdate','%Y%m%dT%H%i%s'), '%Y-%m-%d %H:%i:%s')),'')";
				$datetime = $wpdb->get_var($sql,0,0);
				if(trim($datetime)!=''){
					//NOTE: In the DB, there must be multiple fc_rdatetime records, 1 per rdate.
					add_post_meta($post_id,'fc_rdatetime',$datetime);
					//----
					if( !empty($fc_range_start) ){
						if( strtotime($datetime) < strtotime($fc_range_start) ){
							$fc_range_start = $datetime;// handle an rdate that is before the start date; not standard, but people wants it.
						}
					}
					
					$end = strtotime($datetime)+$duration; 
					if(  empty($fc_range_end) || $end > strtotime($fc_range_end) ){
						$fc_range_end = date('Y-m-d H:i:s',$end);
					}
				}				
			}
			if(!empty($fc_range_start) && !empty($fc_range_end)){
				update_post_meta($post_id,'fc_range_start',$fc_range_start);
				update_post_meta($post_id,'fc_range_end',$fc_range_end);		
			}			
		}
	}

	function parseTime($timeString) {    
	    if ($timeString == '') return null;
	    if(preg_match("/(\d+)(:(\d\d))?\s*(p|a?)/i",$timeString,$time)){
			$str = $time[1].':'.str_pad($time[3],2,'0',STR_PAD_LEFT).' '.(strlen($time[4])>0?$time[4].'m':'');
			return date('H:i:s',strtotime($str));
		}else{
			return null;
		}  
	}	
	
	function ajax_calendarize(){
		$post_ID = intval($_POST['post_ID']);
		if('page' == $this->post_type) {
		    if (!current_user_can('edit_page', $post_ID)) {
		        die(json_encode(array('R'=>'ERR','MSG'=>__('No access','rhc') )));
		    }
		} elseif (!current_user_can('edit_post', $post_ID)) {
		    die(json_encode(array('R'=>'ERR','MSG'=>__('No access','rhc') )));
		}		
		
		$data = isset($_POST['data'])&&count($_POST['data'])>0?$_POST['data']:false;
		if(false===$data){
			die(json_encode(array('R'=>'ERR','MSG'=>__('Missing parameter.','rhc') )));
		}
		
		$allempty = true;
		foreach($data as $i => $arr){
			$arr = is_array($arr)?$arr:explode(',',$arr,2);
			if(!in_array($arr[0], $this->metabox_meta_fields ))continue;
			$var = $arr[0];
			$$var = trim($arr[1]);
			if(''!=trim($$var))$allempty=false;
		}
		
		if($allempty)
			return die(json_encode(array('R'=> 'OK','MSG'=> '','EVENTS' 	=> array())));		
		
		$fc_allday = ''==trim($fc_allday)?1:$fc_allday;
		$fc_start = ''==trim($fc_start)?date('Y-m-d'):$fc_start;
		$fc_start_time = ''==trim($fc_start_time)?'12:00 am':$fc_start_time;
		$fc_end = ''==trim($fc_end)?$fc_start:$fc_end;
		$fc_end_time = ''==trim($fc_end_time)?$fc_start_time:$fc_end_time;
	
		$fc_start_time 	= $this->parseTime($fc_start_time);
		$fc_end_time	= $this->parseTime($fc_end_time);	

		$events = array();
		
		$event = array(
				'id'	=> $post_ID,
				'title' => get_the_title($post_ID),
				'start' => date('Y-m-d H:i:s',strtotime(trim($fc_start.' '.$fc_start_time))),
				'end' 	=> date('Y-m-d H:i:s',strtotime(trim( $fc_end.' '.$fc_end_time ))),
				'allDay'=> $fc_allday==1?true:false
			);	
			
		foreach($this->metabox_meta_fields as $field){
			$event[$field] = $$field;
		}
		
		foreach(array('fc_color'=>'color','fc_text_color'=>'textColor') as $field => $event_field){
			if(!in_array( trim($event[$field]), array('','#') )){
				$event[$event_field]=$event[$field];
			}		
		}
		
		if(!in_array( trim($event['fc_color']), array('','#') )){
			$event['color']=$event['fc_color'];
		}
		
		if(""!=trim($fc_interval) && array_key_exists($fc_interval,$this->fc_intervals) ){
//--will do repeat on client
		}
		
		if(count($events)==0){
			$events[]=(object)$event;
		}
	
		$r = array(
			'R'			=> 'OK',
			'MSG'		=> '',
			'EVENTS' 	=> $events
		);
	
		return die(json_encode($r));
	}
	
	
	function _ajax_calendarize(){
		global $rhc_plugin;
		// check permissions
		$post_ID = intval($_POST['post_ID']);
		if('page' == $this->post_type) {
		    if (!current_user_can('edit_page', $post_ID)) {
		        die(json_encode(array('R'=>'ERR','MSG'=>__('No access','rhc') )));
		    }
		} elseif (!current_user_can('edit_post', $post_ID)) {
		    die(json_encode(array('R'=>'ERR','MSG'=>__('No access','rhc') )));
		}		
		
		$data = isset($_POST['data'])&&count($_POST['data'])>0?$_POST['data']:false;
		if(false===$data){
			die(json_encode(array('R'=>'ERR','MSG'=>__('Missing parameter.','rhc') )));
		}
		
		$allempty = true;
		foreach($data as $i => $arr){
			$arr = is_array($arr)?$arr:explode(',',$arr);
			if(!in_array($arr[0], $this->metabox_meta_fields ))continue;
			$var = $arr[0];
			$$var = trim($arr[1]);
			if(''!=trim($$var))$allempty=false;
		}
		
		if($allempty)
			return die(json_encode(array('R'=> 'OK','MSG'=> '','EVENTS' 	=> array())));	
						
		$events = $rhc_plugin->calendar_ajax->_get_calendar_items($post_ID);
		return die(json_encode(array('R'=> 'OK','MSG'=> '','EVENTS' 	=> $events)));	
	}
	
	
	function metaboxes($t=array()){
		$i = count($t);
		//------------------------------
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-calendarize'; 
		$t[$i]->label 		= __('Calendarize','rhc');
		$t[$i]->right_label	= __('Calendarize','rhc');
		$t[$i]->page_title	= __('Calendarize','rhc');
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		$t[$i]->options = array(
			(object)array(
				'id'=>'calendarize',
				'type'=>'callback',
				'callback'=> array(&$this,'fullcalendar')
			),
			(object)array(
				'type'=>'clear'
			)
		);
		
		if(RHC_EVENTS!=$this->post_type){
			return $t;
			//the rest of the options are only specific to rhc events post type.
		}
		//------------------------------
		$i++;
		$t[$i]=(object)array();
		$t[$i]->id 			= 'rhc-layout-options'; 
		$t[$i]->label 		= __('Layout Options','rhc');
		$t[$i]->right_label	= __('Layout Options','rhc');
		$t[$i]->page_title	= __('Layout Options','rhc');
		$t[$i]->theme_option = true;
		$t[$i]->plugin_option = true;
		$t[$i]->context = 'side';
		$t[$i]->priority = 'default';
		$t[$i]->options = array(
			(object)array(
				'id'			=> 'enable_featuredimage',
				'type'			=> 'onoff',
				'default'		=> '1',
				'label'			=>  __('Event Page Top Image','rhc'),
				'save_option'	=> true,
				'load_option'	=> true
			),		
			(object)array(
				'id'			=> 'enable_postinfo',
				'type'			=> 'onoff',
				'default'		=> '1',
				'label'			=>  __('Event Details Box','rhc'),
				'save_option'	=> true,
				'load_option'	=> true
			),	
			(object)array(
				'id'			=> 'enable_postinfo_image',
				'type'			=> 'onoff',
				'default'		=> '1',
				'label'			=>  __('Event Details Box Image','rhc'),
				'save_option'	=> true,
				'load_option'	=> true
			),
			(object)array(
				'id'			=> 'enable_venuebox',
				'type'			=> 'onoff',
				'default'		=> '1',
				'label'			=>  __('Venue Details Box','rhc'),
				'save_option'	=> true,
				'load_option'	=> true
			),
			(object)array(
				'id'			=> 'enable_venuebox_gmap',
				'type'			=> 'onoff',
				'default'		=> '1',
				'label'			=>  __('Venue Details Box Map','rhc'),
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

	function fullcalendar($tab,$i,$o){
		global $post;
		if($this->debug){
			echo sprintf('fc_range_start: %s fc_range_end: %s |',
				get_post_meta($post->ID,'fc_range_start',true),
				get_post_meta($post->ID,'fc_range_end',true)
			);
			return $this->fullcalendar_debug($tab,$i,$o);
		}
			
		//-----------------
		foreach($this->metabox_meta_fields as $meta_field){
		//	echo sprintf('%s <input id="%s" type="text" class="calendarize_meta_data" name="%s" value="%s" />',
		//		$meta_field,
			echo sprintf('<input id="%s" type="hidden" class="calendarize_meta_data" name="%s" value="%s" />',
				$meta_field,
				$meta_field,
				get_post_meta($post->ID,$meta_field,true)
			);
		//	echo "<br />";
		}	
?>
<div id="calendarize" class="calendarize"></div>
<?php		
		add_action('admin_footer',array(&$this,'admin_footer'));
	}

	function fullcalendar_debug($tab,$i,$o){
		global $post;
		foreach($this->metabox_meta_fields as $meta_field){
			echo sprintf('%s <input id="%s" type="text" class="calendarize_meta_data" name="%s" value="%s" />',
				$meta_field,
		//	echo sprintf('<input id="%s" type="hidden" class="calendarize_meta_data" name="%s" value="%s" />',
				$meta_field,
				$meta_field,
				get_post_meta($post->ID,$meta_field,true)
			);
		//	echo "<br />";
		}	
?>
<div id="calendarize" class="calendarize"></div>
<?php		
		add_action('admin_footer',array(&$this,'admin_footer'));
	}
	
	function admin_footer(){
		global $post;	
		$post_type = get_post_type_object( get_post_type($post) );
	
?>

	<div class="fc-dialog metabox-holder">
		<input type="hidden" class="clicked_start_date" name="clicked_start_date" value="" />
		<ul style="display:none;">
			<li id="prompt-remove"><?php _e('This will remove all calendarize date and recurring date settings.','rhc')?></li>
			<li id="prompt-overwrite"><?php _e('This will overwrite all calendarize date and recurring date settings.','rhc')?></li>
			<li id="prompt-allday"><?php _e('This is an all day event.','rhc')?></li>
		</ul>
		<div class="fc-arrow-holder">
			<div class="fc-arrow"></div>
			<div class="fc-arrow-border"></div>
		</div>
		<div id="fc-content-tabs" class="fc-widget postbox taxonomydiv">
			<h3 class="hndle"><?php echo sprintf(__('Calendarize %s','rhc'),$post_type->label)?></h3>

			<div class="inside  main-content">
				<div>&nbsp;</div>

				<div class="fc-dialog-content">
					<ul class="category-tabs wp-tab-bar">
						<li class="tabs"><a rel=".tab-date">Date</a></li>
						<li class="tabs"><a rel=".tab-color">Color</a></li>
						<li class="tabs"><a rel=".tab-calendar">Calendar</a></li>
						<li class="tabs tabs-rdate"><a rel=".tab-rdate">Repeat</a></li>
						<li class="tabs tabs-exclude"><a rel=".tab-exdate">Exclude</a></li>
					</ul>
					<div class="tab-date tabs-panel">
						<div class="fc-form-field">
							<label for="fc_allday" class="left-label"><?php _e('All-day','rhc')?></label>
							<input type="checkbox" class="fc_allday fc_input" name="fc_allday" value="1" />
							<div class="clear"></div>
						</div>
						<div class="fc-form-field">
							<div id="fc_start_fullcalendar_holder" class="fc_start_fullcalendar_holder postbox close-on-click" rel="fc_start">
								<div class="fc_start_fullcalendar"></div>
							</div>
							<label for="fc_start" class="left-label"><?php _e('Start','rhc')?></label>
							<div class="fc-date-time">
								<input type="text" class="fc_start fc_input" name="fc_start" value="" /><span class="fc-time-label"><?php _e('at','rhc')?></span>
								<input type="text" class="fc_start_time fc_input" name="fc_start_time" value="" placeholder="<?php _e('any time','rhc')?>" />					
							</div>
							<div class="clear"></div>
						</div>
						<div class="fc-form-field">
							<div id="fc_end_fullcalendar_holder" class="fc_end_fullcalendar_holder fc_start_fullcalendar_holder postbox close-on-click" rel="fc_end">
								<div class="fc_start_fullcalendar"></div>
							</div>
							<label for="fc_end" class="left-label"><?php _e('End','rhc')?></label>
							<div class="fc-date-time">
								<input type="text" class="fc_end fc_input" name="fc_end" value="" /><span class="fc-time-label"><?php _e('at','rhc')?></span>
								<input type="text" class="fc_end_time fc_input" name="fc_end_time" value="" placeholder="<?php _e('any time','rhc')?>" />
							</div>
							<div class="clear"></div>
						</div>
						<div class="fc-form-field">
							<label for="fc_repeat" class="left-label"><?php _e('Repeat','rhc')?></label>
							<select id='fc_interval' name="fc_interval" class="fc_input fc_interval">
							<?php foreach($this->fc_intervals as $value => $label):?>
								<option value="<?php echo $value?>"><?php echo $label?></option>
							<?php endforeach;?>
								<option value="rrule"><?php _e('More options','rhc')?></option>
							</select>
							<div class="clear"></div>
						</div>
						
						<?php $this->rrule_section();?>
						
						<div class="end-repeat-section">
							<div class="fc-form-field">
								<div id="fc_end_interval_fullcalendar_holder" class="fc_start_fullcalendar_holder fc_end_interval_fullcalendar_holder postbox close-on-click" rel="fc_end_interval">
									<div class="fc_start_fullcalendar"></div>
								</div>
								<label for="fc_end_interval" class="left-label"><?php _e('End repeat','rhc')?></label>
								<select id="rrule_repeat_end_type" name="rrule_repeat_end_type" class="rrule_inp_section">
									<option value="until"><?php _e('by date','rhc')?></option>
									<option value="count"><?php _e('by count','rhc')?></option>
									<option value="never"><?php _e('never','rhc')?></option>
								</select>
								<div class="clear"></div>
							</div>
							
							<div class="fc-form-field repeat_type repeat_type_until">
								<div id="fc_end_interval_fullcalendar_holder" class="fc_start_fullcalendar_holder rrule_inp_section fc_end_interval_fullcalendar_holder postbox close-on-click" rel="fc_end_interval">
									<div class="fc_start_fullcalendar"></div>
								</div>
								<label for="fc_end_interval" class="left-label"><?php _e('End date','rhc')?></label>
								<div class="fc-date-time">
									<input id="rrule_until" type="text" class="fc_end_interval fc_input" name="fc_end_interval" value="" />
								</div>
								<div class="clear"></div>
							</div>
							
							<div class="fc-form-field repeat_type repeat_type_count">
								<div id="fc_end_interval_fullcalendar_holder" class="fc_start_fullcalendar_holder rrule_inp_section fc_end_interval_fullcalendar_holder postbox close-on-click" rel="fc_end_interval">
									<div class="fc_start_fullcalendar"></div>
								</div>
								<label for="fc_end_interval" class="left-label"><?php _e('End count','rhc')?></label>
								<div class="fc-end-count">
									<input id="fc_end_count" type="text" class="fc_end_count fc_input" name="fc_end_count" value="" />
								</div>
								<div class="clear"></div>
							</div>
						</div>
					</div>			
					
					<div class="tab-color tabs-panel">
						<div class="fc-form-field">
							<label for="fc_color" class="left-label"><?php _e('Color','rhc')?></label>
							
							<div class="farbtastic-holder">
								<input id="frm_fc_color" type='text' class='fc_color fc_input fc_color_input' name='fc_color' value='<?Php echo get_post_meta($post->ID,'fc_color',true)?>' /><a title="<?PHP _e('Choose color','rhc')?>" class="farbtastic-choosecolor" href="javascript:void(0);" rel="<?PHP _e('Close','rhc')?>"><?PHP _e('Show','rhc')?></a>
								<div id="farbtastic_fc_color" rel="#frm_fc_color" class="pop-farbtastic"></div>
							</div>
							<div class="pop-float-separator">&nbsp;</div>
							
							<div class="clear"></div>
						</div>
						<div class="fc-form-field">
							<label for="fc_text_color" class="left-label"><?php _e('Text color','rhc')?></label>
							
							<div class="farbtastic-holder">
								<input id="frm_fc_text_color" type='text' class='fc_text_color fc_input fc_color_input' name='fc_text_color' value='<?Php echo get_post_meta($post->ID,'fc_text_color',true)?>' /><a title="<?PHP _e('Choose color','rhc')?>" class="farbtastic-choosecolor" href="javascript:void(0);" rel="<?PHP _e('Close','rhc')?>"><?PHP _e('Show','rhc')?></a>
								<div id="farbtastic_fc_text_color" rel="#frm_fc_text_color" class="pop-farbtastic"></div>
							</div>
							<div class="pop-float-separator">&nbsp;</div>							
							
							<div class="clear"></div>
						</div>
						<div class="clear"></div>
					</div>		
					
					<div class="tab-calendar tabs-panel">
						<div class="fc-form-field">
							<label for="fc_click_link" class="left-label"><?php _e('Click links to','rhc')?></label>
							<div class="fc-click-link">
								<select class="fc_click_link fc_input" name="fc_click_link">
									<option value="view"><?php _e('Tooltip','rhc')?></option>
									<option value="page"><?php _e('Page','rhc')?></option>
									<option value="none"><?php _e('No link','rhc')?></option>
								</select>
							</div>
							<div class="clear"></div>
						</div>
						<div class="fc-form-field">
							<label for="fc_click_target" class="left-label"><?php _e('Target','rhc')?></label>
							<div class="fc-click-target">
								<select class="fc_click_target fc_input" name="fc_click_target">
									<option value="_blank"><?php _e('_blank new window or tab','rhc')?></option>
									<option value="_self"><?php _e('_self same window or tab','rhc')?></option>
								</select>
							</div>
							<div class="clear"></div>
						</div>
						<div class="clear"></div>
					</div>		
					
					<div class="tab-rdate tabs-panel">
						<p><?php _e('In addition to recurring rules you can set arbitrary repeat dates.','rhc')?></p>
						<p><?php _e('After adding the initial event simply click on any date in order to add the arbitrary repeat date.','rhc')?></p>
					
						<div class="fc-form-field">
							<label for="fc_rdate" class="fullwidth-label"><?php _e('Repeat dates','rhc')?></label>
							<p class="fc-no-rdate"><?php _e("No repeat dates set.")?></p>
							<div class="fc-repeat-dates"></div>
							<div class="clear"></div>
						</div>									
					</div>		
					
					<div class="tab-exdate tabs-panel">
						<p><?php _e('Excluded dates are only applicable when using recurring events.','rhc')?></p>
						<div class="fc-form-field">
							<label for="fc_exdate" class="fullwidth-label"><?php _e('Excluded dates','rhc')?></label>
							<p class="fc-no-excluded"><?php _e("No excluded dates selected.  After setting up recurring events, click on a calendar event, then on the dialog press the exclude button to add the clicked date to the excluded dates list.")?></p>
							<div class="fc-excluded-dates"></div>
							<div class="clear"></div>
						</div>									
					</div>					
				</div>	
			</div>
			<div class="fc-dialog-controls main-content">
				<input type="button" class="button-primary fc-dg-ok" name="fc-dg-ok" value="<?php _e('Accept','rhc')?>" />
				<input type="button" class="button-secondary fc-dg-exclude" name="fc-dg-exclude" title="<?php _e('Exclude this date.  Only applicable with recurring events','rhc')?>" value="<?php _e('Exclude','rhc')?>" />
				<input type="button" class="button-secondary fc-dg-cancel" name="fc-dg-cancel" value="<?php _e('Cancel','rhc')?>" />
				<input type="button" class="button-secondary fc-dg-remove" name="fc-dg-remove" value="<?php _e('Reset settings','rhc')?>" />
				<div class="fc-status">
					<img src="<?php echo admin_url('/images/wpspin_light.gif')?>" alt="" />
				</div>
				<div class="clear"></div>
			</div>		
			
			<div class="inside secondary-content">			
				<p><?php _e('In addition to recurring rules you can set arbitrary repeat dates.  Click accept to save an arbitrary repeat date.','rhc')?></p>
				<p class="not-allday-input"><?php _e('The current calendarize settings require that you specify the repeat date time.','rhc')?></p>
					<div class="secondary-content-fields">
						<div class="fc-form-field not-allday-input">
							<label for="fc_rdate" class="left-label"><?php _e('Repeat time','rhc')?></label>
							<div class="fc-date-time">
								<input type="hidden" class="fc-selected-date-inp" value="" />
								<span class="fc-selected-date"></span><span class="fc-time-label"><?php _e('at','rhc')?></span>
								<input type="text" class="fc_rdate_time fc_input" name="fc_rdate_time" value="" placeholder="<?php _e('any time','rhc')?>" />					
							</div>
							<div class="clear"></div>
						</div>
					</div>	
			</div>
			<div class="fc-dialog-controls secondary-content">
				<input type="button" class="button-primary fc-dg-repeat" name="fc-dg-repeat" value="<?php _e('Accept','rhc')?>" />
				<input type="button" class="button-primary fc-dg-repeat-remove" name="fc-dg-repeat-remove" value="<?php _e('Remove','rhc')?>" />
				<input type="button" class="button-secondary fc-dg-cancel" name="fc-dg-cancel" value="<?php _e('Cancel','rhc')?>" />
				<input type="button" class="button-secondary fc-dg-main" name="fc-dg-main" value="<?php _e('Main settings','rhc')?>" />
				<div class="clear"></div>
			</div>	
			
			
			<div class="clear"></div>
		</div>
	</div>

<?php	
		$this->_calendar_options();
	}	//admin_footer
	
	function rrule_section(){	
		$byvals = array(
			array(
				'label'		=> __('months','rhc'),
				'name'		=> 'rrule_bymonth[]',
				'options'	=> array(
					array(
						1 => __('jan','rhc'),
						2 => __('feb','rhc'),
						3 => __('mar','rhc'),
						4 => __('apr','rhc'),
						5 => __('may','rhc'),
						6 => __('jun','rhc')
					),
					array(
						7 => __('jul','rhc'),
						8 => __('aug','rhc'),
						9 => __('sep','rhc'),
						10 => __('oct','rhc'),
						11 => __('nov','rhc'),
						12 => __('dec','rhc')
					)
				),
				'class'		=> 'rrule_bymonth',
				'visible_class'=> ' vis_YEARLY'
			),
			array(
				'label'		=> __('week of year','rhc'),
				'name'		=> 'rrule_byweekno[]',
				'options'	=> array(
					$this->_fill_numbers_array(1,8),
					$this->_fill_numbers_array(8,15),
					$this->_fill_numbers_array(15,22),
					$this->_fill_numbers_array(22,29),
					$this->_fill_numbers_array(29,36),
					$this->_fill_numbers_array(36,43),
					$this->_fill_numbers_array(43,50),
					$this->_fill_numbers_array(50,54)
				),
				'class'		=> 'rrule_byweekno',
				'visible_class'=> ' vis_YEARLY'
			),
			array(
				'label'		=> __('days of the month','rhc'),
				'name'		=> 'rrule_bymonthday[]',
				'options'	=> array(
					$this->_fill_numbers_array(1,8),
					$this->_fill_numbers_array(8,15),
					$this->_fill_numbers_array(15,22),
					$this->_fill_numbers_array(22,29),
					$this->_fill_numbers_array(29,32)
				),
				'class'		=> 'rrule_bymonthday',
				'visible_class'=> ' vis_YEARLY vis_MONTHLY'
			),
			array(
				'label'		=> __('days of the week','rhc'),
				'visible_class'=> ' vis_YEARLY vis_MONTHLY vis_WEEKLY'
			),
			array(
				'name'		=> 'rrule_bysetpos[]',
				'options'	=> array(
					array(
						'1'		=> __('1st','rhc'),
						'2'		=> __('2nd','rhc'),
						'3'		=> __('3rd','rhc'),
						'4'		=> __('4th','rhc'),
						'5'		=> __('5th','rhc'),
						'-1'	=> __('last','rhc')		
					)
				),
				'class'		=> 'rrule_bysetpos',
				'visible_class'=> ' vis_MONTHLY'
			),			
			array(
				//'label'		=> __('days of the week','rhc'),
				'name'		=> 'rrule_bywkst[]',
				'options'	=> array(
					array(
						'SU'	=> __('sun','rhc'),
						'MO'	=> __('mon','rhc'),
						'TU'	=> __('tue','rhc'),
						'WE'	=> __('wed','rhc'),
						'TH'	=> __('thu','rhc'),
						'FR'	=> __('fri','rhc'),
						'SA'	=> __('sat','rhc')					
					)
				),
				'class'		=> 'rrule_bywkst',
				'visible_class'=> ' vis_YEARLY vis_MONTHLY vis_WEEKLY'
			),
			array(
				'name'		=> 'rrule_several_hours',
				'options'	=> array(
					array(
						'1'=>__('Several times','rhc')
					)
				),
				'class'		=> 'rrule_several_hours',
				'visible_class'=> ' vis_YEARLY vis_MONTHLY vis_WEEKLY vis_DAILY vis_HOURLY'
			),
			array(
				'label'		=> __('hours','rhc').'<a id="rhc-switch-12h-format" class="button-secondary">12h</a>',
				'name'		=> 'rrule_byhour[]',
				'options'	=> array(
					$this->_fill_numbers_array(0,6),
					$this->_fill_numbers_array(6,12),
					$this->_fill_numbers_array(12,18),
					$this->_fill_numbers_array(18,24)
				),
				'class'		=> 'rrule_byhour',
				'visible_class'=> ' '
			),
			array(
				'label'		=> __('minutes','rhc'),
				'name'		=> 'rrule_byminute[]',
				'options'	=> array(
					$this->_fill_numbers_array(0,60)
				),
				'class'		=> 'rrule_byminute',
				'visible_class'=> ' '
			)
		);
?>
<div class="rrule_section rhcalendar">
	<div>
		Repeat every 
		<input id="rrule_interval" class="rrule_interval rrule_inp_section" type="text" name="rrule_interval" value="1" /> 
		<select id="rrule_freq" class="rrule_freq rrule_inp_section" name="rrule_freq">
			<option value="YEARLY"><?php _e('year(s)','rhc')?></option>
			<option value="MONTHLY"><?php _e('month(s)','rhc')?></option>
			<option value="WEEKLY"><?php _e('week(s)','rhc')?></option>
			<option value="DAILY"><?php _e('day(s)','rhc')?></option>
			<option value="HOURLY"><?php _e('hour(s)','rhc')?></option>
			<option value="MINUTELY"><?php _e('minute(s)','rhc')?></option>
		</select>	
	</div>
<?php foreach($byvals as $byval):  ?>
	<div class="<?php echo @$byval['class'];?>_holder rrule_holder <?php echo isset($byval['visible_class'])?$byval['visible_class']:'';?>">
		<?php if(isset($byval['label'])):?>
		<label class="<?php echo @$byval['class'];?>_label"><?php echo sprintf('On the following %s',$byval['label'])?></label>
		<?php endif; ?>
		<?php if(isset($byval['render'])):$method=$byval['render'];$this->$method($byval);else:?>
		<?php if(isset($byval['options'])): ?>
		<?php foreach($byval['options'] as $options): ?>
		<div class="<?php echo $byval['class'];?>_group">
			<?php foreach($options as $value => $label): $id=$this->uid++;?>
				<input id="rrule_input_<?php echo $id;?>" class="<?php echo $byval['class'];?>_inp rrule_input rrule_inp_section rrule_val_<?php echo $this->_class($value);?>" type="checkbox" name="<?php echo $byval['name'];?>" value="<?php echo $value?>" /><label for="rrule_input_<?php echo $id;?>"><?php echo $label?></label>
			<?php endforeach; ?>
		</div>
		<?php endforeach; ?>
		<?php endif;endif; ?>
	</div>
<?php endforeach; ?>
	<div class="rhc-clear"></div>
</div>
<div class="rhc-clear" style="clear:both;"></div>
<?php		
	}
	
	function _switch_timte_format(){
	echo "TODO";
	}
	
	function _fill_numbers_array($start,$end){
		$arr=array();
		for($a=$start;$a<$end;$a++){
			$arr[$a]=$a;
		}
		return $arr;
	}	
	
	function _class($val){
		return $val;
	}	
	
	function _calendar_options(){
		global $rhc_plugin;
		$field_option_map = array(
			"firstday" 				=> "1",
			"button_text_today"		=> __('today','rhc'),
			"button_text_month"		=> __('month','rhc'),
			"button_text_day"		=> __('day','rhc'),
			"button_text_week"		=> __("week",'rhc'),
			"button_text_calendar"	=> __('Calendar','rhc'),
			"button_text_event"		=> __('event','rhc')
		);
	
		foreach($field_option_map as $field => $default){
			$option = 'cal_'.$field;
			$$field = $rhc_plugin->get_option($option,$default,true);
		}
	//--	
		$monthnames = __('January,February,March,April,May,June,July,August,September,October,November,December','rhc');
		$monthnamesshort = __('Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec','rhc');
		$daynames = __('Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday','rhc');
		$daynamesshort = __('Sun,Mon,Tue,Wed,Thu,Fri,Sat','rhc');
		$options = (object)array(	
			'firstDay'			=> $firstday,
			'monthNames' 		=> explode(',',$monthnames),
			'monthNamesShort' 	=> explode(',',$monthnamesshort),
			'dayNames' 			=> explode(',',$daynames),
			'dayNamesShort'		=> explode(',',$daynamesshort),		
			'buttonText'		=> (object)array(
				'today'	=> $button_text_today,
				'month'	=> $button_text_month,
				'week'	=> $button_text_week,
				'day'	=> $button_text_day,
				'rhc_search'=> $button_text_calendar,
				'rhc_event' => $button_text_event
			),						
		);
?>
<script>
var rh_calendar_options = <?php echo json_encode($options)?>;
</script>
<?php	
	}
	
}
?>