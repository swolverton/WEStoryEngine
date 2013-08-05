<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/

class rhc_custom_field_filters {
	function rhc_custom_field_filters(){
		add_filter('rhc_post_info_value',array(&$this,'handle_custom_field_filter'),10,2);
	}
	function handle_custom_field_filter($value,$o){
		if($o->type=='taxonomymeta' && $o->taxonomymeta_field=='email'){
			if (preg_match('/^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$/',$value)) { 
				$value = sprintf('<a href="mailto:%s">%s</a>',$value,$value);
			}				
		}
		if($o->type=='taxonomymeta' && $o->taxonomymeta_field=='website'){
			$label = $value;
			$label = str_replace('https://','',$label);
			$label = str_replace('http://','',$label);
			if($label==$value){
				$value = 'http://'.$value;
			}
			
			
			$value = sprintf('<a href="%s">%s</a>',htmlspecialchars($value),$label);		
		}
		if($o->type=='taxonomymeta' && $o->taxonomymeta_field=='gaddress'){
			$url = "http://maps.google.com/?q=".urlencode($value);
			$value = sprintf('<a href="%s" target="_BLANK">%s</a>',$url,$o->label);			
		}	
			
		return $value;
	}
}
/*
rhc_post_info_field Object
(
    [id] => 
    [type] => taxonomymeta
    [label] => Email
    [value] => 
    [taxonomy] => 
    [taxonomy_links] => 1
    [postmeta] => 
    [taxonomymeta] => venue
    [taxonomymeta_field] => email
    [render_cb] => 
    [post_ID] => 38
    [date_format] => 
    [column] => 0
    [span] => 12
    [offset] => 0
    [index] => 2
    [frontend] => 1
)
*/
?>