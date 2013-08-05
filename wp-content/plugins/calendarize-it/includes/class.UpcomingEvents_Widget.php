<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/

class UpcomingEvents_Widget extends WP_Widget {
	function __construct() {
		parent::__construct(
	 		'upcoming_events_widget', 
			__('Calendarize (Upcoming Events)','rhc'), 
			array( 'description' => __( 'Upcoming events', 'rhc' ), ) 
		);
	}

	function widget( $args, $instance ) {
		extract( $args );
		global $post,$rhc_plugin;
		$tmp_post = $post;
		//----
		foreach(array('title','number') as $field ){
			$$field = $instance[$field];
		}
		if(intval($number)==0)return;		
		echo $before_widget;
		echo trim($title)==''?'':$before_title.$title.$after_title;

		$sel = 'rhc-upcoming-'.$rhc_plugin->uid++;
		echo sprintf("<div id=\"%s\"></div>",$sel);
		$upcoming = $this->get_upcoming($instance,$sel);
		echo apply_filters('rhc_after_upcoming_events_widget','',$args,$instance);
		echo $after_widget;				
		//-----
		$post = $tmp_post;	
	}
	
	function get_upcoming($widget_args,$sel){
		global $rhc_plugin;
		
		foreach(array('premiere', 'loading_method', 'template', 'post_type','calendar','venue','organizer','taxonomy','terms','auto','horizon','number','showimage','words','fcdate_format','fctime_format','calendar_url') as $field ){
			$$field = isset($widget_args[$field])?$widget_args[$field]:'';
			//echo $field.": ".$$field."<BR />";
		}		

		$start = date('Y-m-d 00:00:00');
		
		$end = date('Y-m-d 23:59:59',mktime(0,0,0,date('m')+12,date('d'),date('Y')));
	
		$number = intval($number);
		$number = $number==0?5:$number;

		$post_type = is_array($post_type)&&!empty($post_type)?$post_type:array(RHC_EVENTS);
		
		if(is_tax()){
			$is_tax = true;
			$o = get_queried_object();
			$args = array(
				'post_type' 	=> $post_type,
				'start'		=> $start,
				'end'		=> $end,
				'taxonomy'	=> $o->taxonomy,
				'terms'		=> $o->slug,
				'calendar'	=> false,
				'venue'		=> false,
				'organizer'	=> false,
				'author'	=> false,
				'author_name'=>false,
				'tax'		=> false,
				'numberposts' => $number
			);			
		}else{
			$is_tax = false;
			$args = array(
				'post_type' 	=> $post_type,
				'start'		=> $start,
				'end'		=> $end,
				'taxonomy'	=> $taxonomy==''?false:$taxonomy,
				'terms'		=> $terms==''?false:$terms,
				'calendar'	=> $calendar==''?false:$calendar,
				'venue'		=> $venue==''?false:$venue,
				'organizer'	=> $organizer==''?false:$organizer,
				'author'	=> false,
				'author_name'=>false,
				'tax'		=> false,
				'tax_by_id' => true,
				'numberposts' => $number
			);
			
			if($args['taxonomy']!==false && $args['terms']!==false){
				$args['tax_by_id']=false;
			}
		}

		if($loading_method=='ajax'){
			$default_events_source = $rhc_plugin->get_option( 'rhc-api-url', '', true );
			if(''==trim($default_events_source)){
				$default_events_source = site_url('/?rhc_action=get_calendar_events');
			}	
			$events = (object)array(
				'ajax_url' 		=> $default_events_source.'&uew=1',
				'args'			=> $args,
				'is_tax'		=> $is_tax,
				'words'			=> $words,
				'calendar_url'	=> $calendar_url
			);		
		}else{
			$events = $rhc_plugin->calendar_ajax->get_events_set($args);			
			if(empty($events))return '';
			$using_calendar_url = false;
			if($calendar_url!=''){
				$using_calendar_url = true;
				foreach($events as $index => $e){
					$events[$index]['url']=$calendar_url;
				}
			}			
		}
	
		if('1'==$premiere && is_array($events)&&count($events)>0){
			foreach($events as $i => $e){
				$events[$i]['fc_rrule']="FREQ=DAILY;INTERVAL=1;COUNT=1";
			}
		}
	
		return $this->render_events($start,$end,$sel,$events,$number,$showimage,$words,$fcdate_format,$fctime_format,$horizon,$using_calendar_url,$template,$loading_method);
	}

	
	function render_events($start,$end,$sel,$events,$number,$showimage,$description_words=10,$fcdate_format='',$fctime_format='',$horizon='day',$using_calendar_url=false,$template_filename,$loading_method='server'){
		global $rhc_plugin;
		$description_words = is_numeric($description_words)?$description_words:10;
		$count = 0;

		$template_filename = ''==$template_filename?'widget_upcoming_events.php':$template_filename;
		$template_filename = $rhc_plugin->get_template_path($template_filename);
		$template_filename = file_exists($template_filename)?$template_filename:$rhc_plugin->get_template_path('widget_upcoming_events.php');	
		$template = file_get_contents($template_filename);
		
		if($loading_method=='server'){
			foreach($events as $i => $e){			
				$description = '';
				$drr = explode(' ',$e['description']);
				for($a=0;$a<$description_words;$a++){
					if(isset($drr[$a]))
						$description.=" ".$drr[$a];
				}
				
				if(count($drr)>$description_words)
				$description.="<a href=\"".$e['url']."\">...</a>";
				
				$events[$i]['description']=$description;
			}
			
			if(empty($events))return '';		
		}
		
		$args = (object)array(
			'sel'=>$sel,
			'number'=>$number,
			'showimage'=>$showimage,
			'fcdate_format'=>$fcdate_format,
			'fctime_format'=>$fctime_format,
			'start'=>$start,
			'end'=>$end,
			'horizon'=>$horizon,
			'using_calendar_url'=>$using_calendar_url,
			'loading_method'=>$loading_method
		);
		
		//-- fill day and month names
		//-------- this portion is based on the code used on function.generate_calendarize_shortcode.php, TODO: simplify with a function
		global $rhc_plugin;
		
		$defaults = array(
			"monthnames"		=> __('January,February,March,April,May,June,July,August,September,October,November,December','rhc'),
			"monthnamesshort"	=> __('Jan,Feb,Mar,Apr,May,Jun,Jul,Aug,Sep,Oct,Nov,Dec','rhc'),
			"daynames"			=> __('Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday','rhc'),
			"daynamesshort"		=> __('Sun,Mon,Tue,Wed,Thu,Fri,Sat','rhc')
		);
		
		$options = (object)array();
		$field_option_map = array(
			"monthnames"=>"monthNames",
			"monthnamesshort"=>"monthNamesShort",
			"daynames"=>"dayNames",
			"daynamesshort"=>"dayNamesShort"
		);
		foreach($field_option_map as $field => $js_field){
			$option = 'cal_'.$field;
			if(isset($params[$field]))continue;
			$value = $rhc_plugin->get_option($option,$defaults[$field],true);
			if(trim($value)!=''){
				$params[$field]=$value;
			}
		}
		//--
		if(is_array($params) && count($params)>0){
			foreach($params as $field => $value){
				foreach(array('['=>'&#91;',']'=>'&#93;') as $replace => $with){
					$value = str_replace($replace,$with,$value);
				}
				$options->$field_option_map[$field]=explode(',',str_replace(' ','',$value));
			}	
		}			
		//--------		
		
		echo "<div class=\"rhc-widget-template\" style=\"display:none;\">".$template."</div>";
		echo sprintf("<script>jQuery(document).ready(function($){try{render_upcoming_events(%s,%s,%s);}catch(error){}});</script>",
			json_encode($args),
			json_encode($events),
			json_encode($options)
		);
//		echo "<pre>";
//		print_r($events);
//		echo "</prE>";

		//echo $sel;		
	}

	function get_template_parts(){
		global $rhc_plugin;
		$template = file_get_contents($rhc_plugin->get_template_path('widget_upcoming_events.php'));
		$parts = (object)array(
			'holder'=>$template,
			'featured'=>''
		);
		if(preg_match('/<!--featured-->(.*)<!--featured-->/si',$template,$matches)){
			$parts->featured = $matches[1];
			$parts->holder = str_replace('<!--featured-->'.$parts->featured.'<!--featured-->','<!--featured-->',$parts->holder);
		}	
		return $parts;	
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = array();
		foreach(array('taxonomy', 'terms', 'loading_method', 'template', 'post_type','calendar','venue','organizer','auto','premiere','title','fcdate_format','fctime_format','horizon','number','showimage','words','calendar_url') as $field){
			$instance[$field] = $new_instance[$field];
		}
		$instance = apply_filters('rhc_widget_upcoming_events_update',$instance,$new_instance,$old_instance);
		return $instance;
	}

	function form( $instance ) {
		$taxmap = array('venue'=>RHC_VENUE,'organizer'=>RHC_ORGANIZER,'calendar'=>RHC_CALENDAR);
		foreach(array('taxonomy'=>'', 'terms'=>'', 'loading_method'=>'server', 'calendar_url'=>'', 'auto'=>0, 'premiere'=>0,'title'=>'','horizon'=>'hour','number'=>5,'showimage'=>0,'words'=>10, 'fcdate_format'=>'MMM d, yyyy','fctime_format'=>'h:mmtt') as $field =>$default){
			$$field = isset( $instance[$field] )?$instance[$field]:$default;		
		}
		//---
		global $rhc_plugin;
		$post_types = $rhc_plugin->get_option('post_types',array(),true);
		array_unshift($post_types,RHC_EVENTS);	
		$checked = isset($instance['post_type'])&&is_array($instance['post_type'])&&count($instance['post_type'])>0?$instance['post_type']:array(RHC_EVENTS);
		//----
		require_once RHC_PATH.'includes/class.rh_templates.php';
		$t = new rh_templates( array('template_directory'=>$rhc_plugin->get_template_path()) );
		$templates = $t->get_template_files('widget_upcoming_events');
		$templates = is_array($templates)&&count($templates)>0?$templates:array('widget_upcoming_events.php');		
		$current_template = isset($instance['template'])?$instance['template']:'widget_upcoming_events.php';	
?>
<div>
	<div class="" style="margin-top:10px;">
		<label><?php _e('Title','rhc')?></label>
		<input type="text" id="<?php echo $this->get_field_id('title')?>" class="widefat" name="<?php echo $this->get_field_name('title')?>" value="<?php echo $title?>" />
	</div>
	<div class="" style="margin-top:10px;">
		<?php _e('Date format','rhc')?>
		<input type="text" class="widefat" value="<?php echo $fcdate_format ?>" id="<?php echo $this->get_field_id('fcdate_format')?>" name="<?php echo $this->get_field_name('fcdate_format')?>" />
	</div>	
	<div class="" style="margin-top:10px;">
		<?php _e('Time format','rhc')?>
		<input type="text" class="widefat" value="<?php echo $fctime_format ?>" id="<?php echo $this->get_field_id('fctime_format')?>" name="<?php echo $this->get_field_name('fctime_format')?>" />
	</div>
	<div class="" style="margin:10px 0 10px 0;">
		<label><?php _e('Post type','rhc')?></label>
		<div class="widefat">
			<?php foreach($post_types as $post_type):?>
			<input type="checkbox" <?php echo in_array($post_type,$checked)?'checked="checked"':''?> name="<?php echo $this->get_field_name('post_type')?>[]" value="<?php echo $post_type ?>" />&nbsp;<?php echo $post_type ?><br />
			<?php endforeach;?>		
		</div>
	</div>		
	<div class="" style="margin:10px 0 10px 0;">
		<?php _e('Template','rhc')?>
		<select id="<?php echo $this->get_field_id('template')?>" name="<?php echo $this->get_field_name('template')?>" class="widefat">
			<?php foreach($templates as $value => $label):?>
			<option <?php echo $current_template==$value?'selected="selected"':''?> value="<?php echo $value?>"><?php echo $label?></option>
			<?php endforeach; ?>
		</select>
	</div>	
	<label><?php _e('Event taxonomies:','rhc')?></label>
<?php foreach(array('calendar'=>__('Calendar','rhc'),'venue'=>__('Venue','rhc'),'organizer'=>__('Organizer','rhc')) as $field => $label):$$field = isset( $instance[$field] )?$instance[$field]:'';?>	
	<div class="tax-events tax-field" style="margin-top:10px;">
	<label for="<?php echo $field ?>"><?php echo $label?></label>
	<?php $this->taxonomy_dropdown($taxmap[$field],$this->get_field_id($field),$this->get_field_name($field),(isset( $instance[$field] )?$instance[$field]:''))?>
	</div>
<?php endforeach;?>
	

	<div class="tax-custom tax-field" style="margin-top:10px;">
		<label><?php _e('Custom taxonomies:','rhc')?></label>
		<?php _e('Taxonomy','rhc')?>
		<input type="text" class="widefat" value="<?php echo $taxonomy ?>" id="<?php echo $this->get_field_id('taxonomy')?>" name="<?php echo $this->get_field_name('taxonomy')?>" />
	</div>
	<p style="margin-top:3px;">
		<?php _e('*Overwrites event taxonomies filter.','rhc')?>
	</p>	
	<div class="tax-custom tax-field" style="margin-top:10px;">
		<?php _e('Terms','rhc')?>
		<input type="text" class="widefat" value="<?php echo $terms ?>" id="<?php echo $this->get_field_id('terms')?>" name="<?php echo $this->get_field_name('terms')?>" />
	</div>
	
	<div class="" style="margin-top:10px;">
		<?php _e('Max number of posts','rhc')?>
		<input type="text" class="widefat" value="<?php echo $number ?>" id="<?php echo $this->get_field_id('number')?>" name="<?php echo $this->get_field_name('number')?>" />
	</div>
	
	<div class="" style="margin-top:10px;">
		<?php _e('Max description word count','rhc')?>
		<input type="text" class="widefat" value="<?php echo $words ?>" id="<?php echo $this->get_field_id('words')?>" name="<?php echo $this->get_field_name('words')?>" />
	</div>
	
	<div class="" style="margin-top:10px;">
		<?php _e('Remove event by','rhc')?>
		<select id="<?php echo $this->get_field_id('horizon')?>" name="<?php echo $this->get_field_name('horizon')?>" class="widefat">
			<option value="hour" <?php echo $horizon=='hour'?'selected="selected"':''?> ><?php _e('Hour','rhc')?></option>
			<option value="day" <?php echo $horizon=='day'?'selected="selected"':''?> ><?php _e('Day','rhc')?></option>
		</select>
	</div>
	
	<div class="" style="margin-top:10px;">
		<?php _e('Show featured image','rhc')?>
		<select id="<?php echo $this->get_field_id('showimage')?>" name="<?php echo $this->get_field_name('showimage')?>" class="widefat">
			<option value="0" <?php echo $showimage=='0'?'selected="selected"':''?> ><?php _e('No image','rhc')?></option>
			<option value="1" <?php echo $showimage=='1'?'selected="selected"':''?> ><?php _e('Show image','rhc')?></option>
		</select>
	</div>	
	
	<div class="" style="margin-top:10px;">
		<?php _e('Loading method','rhc')?>
		<select id="<?php echo $this->get_field_id('loading_method')?>" name="<?php echo $this->get_field_name('loading_method')?>" class="widefat">
			<option value="server" <?php echo $loading_method=='server'?'selected="selected"':''?> ><?php _e('Server side','rhc')?></option>
			<option value="ajax" <?php echo $loading_method=='ajax'?'selected="selected"':''?> ><?php _e('Ajax','rhc')?></option>
		</select>
	</div>
	
	<div class="" style="margin-top:10px;">
		<input type="checkbox" id="<?php echo $this->get_field_id('auto')?>" name="<?php echo $this->get_field_name('auto')?>" <?php echo $auto==1?'checked="checked"':''?> value=1 />&nbsp;*<?php _e('Only related events.','rhc')?>
	</div>
	<p style="margin-top:3px;">*<?php _e('If the loaded page is a calendar, venue or organizer (taxonomy), only show events from the same taxonomy.')?></p>

	<div class="" style="margin-top:10px;">
		<input type="checkbox" id="<?php echo $this->get_field_id('premiere')?>" name="<?php echo $this->get_field_name('premiere')?>" <?php echo $premiere=='1'?'checked="checked"':''?> value=1 />&nbsp;*<?php _e('Only premiere events.','rhc')?>
	</div>	
	<p style="margin-top:3px;">*<?php _e('Check if you only want premiere events displayed. (the first date on recurring events)')?></p>

	<div class="" style="margin-top:10px;">
		<?php _e('Calendar url(optional)','rhc')?>
		<input type="text" class="widefat" value="<?php echo $calendar_url ?>" id="<?php echo $this->get_field_id('calendar_url')?>" name="<?php echo $this->get_field_name('calendar_url')?>" />
	</div>
	<?php do_action('rhc_widget_upcoming_events_form',$this,$instance)?>
</div>
<?php
	}
	
	function taxonomy_dropdown($taxonomy,$id,$name,$posted_value){
		$terms = get_terms($taxonomy);
?>
<select id="<?php echo $id?>" name="<?php echo $name?>" class="widefat upcoming-<?php echo $taxonomy?>">
<?php if(is_array($terms)&&count($terms)>0):?>
<option value=""><?php _e('--any--','rhc')?></option>
<?php foreach($terms as $t):?>
<option value="<?php echo $t->term_id?>" <?php echo $posted_value==$t->term_id?'selected="selected"':''?> ><?php echo $t->name?></option>
<?php endforeach;?>
<?php else: ?>
<option value=""><?php _e('--no options--','rhc')?></option>
<?php endif;?>
</select>
<?php		
	}
}
?>