<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/

function generate_calendarize_shortcode($params=array()){
	//
	$args=array();
	if(is_tax()||is_category()):
		$term = get_queried_object();
		$args['taxonomy']=sprintf('taxonomy="%s"',$term->taxonomy);
		$args['terms']=sprintf('terms="%s"',$term->slug);
	elseif(is_archive()):
		$args['post_type']=sprintf('post_type="%s"',get_query_var( 'post_type' ));
	endif;
	//--load default values
	global $rhc_plugin;
	$field_option_map = array(
		"theme","defaultview","aspectratio","header_left","header_center","header_right","weekends",
		"firstday","titleformat_month","titleformat_week","titleformat_day","columnformat_month",
		"columnformat_week","columnformat_day","button_text_today","button_text_month",
		"button_text_day","button_text_week","button_text_calendar","button_text_event","button_text_prev","button_text_next","buttonicons_prev",
		"buttonicons_next","eventlistdateformat","eventliststartdateformat","eventliststartdateformat_allday","eventlistshowheader","eventlistnoeventstext","eventlistmonthsahead","eventlist_display","eventlistupcoming","eventlistreverse","eventlistoutofrange",
		"timeformat_month","timeformat_week","timeformat_day","timeformat_default","axisformat",
		"tooltip_startdate","tooltip_startdate_allday","tooltip_enddate","tooltip_enddate_allday","tooltip_disable_title_link",
		"alldayslot","alldaytext","firsthour","slotminutes","mintime","maxtime",
		"tooltip_target", "icalendar", "icalendar_width", "icalendar_button", "icalendar_title", "icalendar_description","icalendar_align",
		"monthnames","monthnamesshort","daynames","daynamesshort",
		"week_mode","loading_overlay",
		"week_numbers","week_numbers_title"
	);
	foreach($field_option_map as $field){
		$option = 'cal_'.$field;
		if(isset($params[$field]))continue;
		$value = $rhc_plugin->get_option($option);
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
			$args[$field]=sprintf('%s="%s"',$field,$value);
		}	
	}	
	return sprintf('[%s %s]',SHORTCODE_CALENDARIZE,implode(' ',$args));
}
?>