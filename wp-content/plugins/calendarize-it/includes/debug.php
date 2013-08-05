<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/

if('debug_calendarize'!=get_class($this))die('No access');

function debug_wrap_textarea($text,$properties='class="widefat" rows="10"'){
	return sprintf("<textarea %s>%s</textarea>",$properties,$text);
}

function debug_wordpress_version(){
	global $wp_version;
	return $wp_version;
}

function debug_cal_version(){
	return RHC_VERSION;
}

function debug_template_path(){
	global $rhc_plugin;
	return $rhc_plugin->get_template_path();
}

function debug_saved_options(){
	global $rhc_plugin;
	$options = get_option($rhc_plugin->options_varname);
	return debug_wrap_textarea(print_r($options,true));
}

function debug_loaded_options(){
	global $rhc_plugin;
	return debug_wrap_textarea(print_r($rhc_plugin->options,true));
}

function debug_saved_rewrite_rules(){
	$options = get_option( 'rewrite_rules' );
	return debug_wrap_textarea(print_r($options,true));
}

function debug_loaded_rewrite_rules(){
	global $wp_rewrite;
	return debug_wrap_textarea(print_r($wp_rewrite,true));
}

function debug_wprewrite_rewrite_rules(){
	global $wp_rewrite;
	return debug_wrap_textarea(print_r($wp_rewrite->rewrite_rules(),true));
}

function debug_htaccess(){
	if( file_exists(ABSPATH.'.htaccess') ){
		$ht = file_get_contents(ABSPATH.'.htaccess');
		return debug_wrap_textarea($ht);
	}
	return '.htaccess not found';
}

function debug_implemented_shortcode(){
	global $wpdb;
	$sql = "SELECT ID, post_title FROM $wpdb->posts WHERE post_status=\"publish\" AND post_content LIKE \"%[calendarizeit%\" LIMIT 100";
	$ids = $wpdb->query($sql);
	if($wpdb->num_rows>0){
		foreach($wpdb->last_result as $id){
			echo $id->post_title . "<br />&nbsp;&nbsp;" . site_url('/?p='.$id->ID) . "<br />";
		}
	}else{
		return 'none';
	}
}

$items = array(
	'debug_wordpress_version' => __('WordPress version','rhc'),
	'debug_cal_version'	=> __('Calendarize It version','rhc'),
	'debug_template_path'  => __('Template path','rhc'),
	'debug_saved_options'	=> __('Saved options','rhc'),
	'debug_loaded_options'	=> __('Loaded options','rhc'),
	'debug_saved_rewrite_rules'	=> __('Saved Rewrite rules','rhc'),
	'debug_loaded_rewrite_rules'	=> __('Loaded $wp_rewrite','rhc'),
	'debug_wprewrite_rewrite_rules'	=> __('Rewrite rules as returned by $wp_rewrite->rewrite_rules()','rhc'),
	'debug_htaccess' => __('.htaccess content','rhc'),
	'debug_implemented_shortcode' => __('Published calendar (pages containing calendarizeit shortcode)','rhc')
);


$items = apply_filters('rhc_debug_items',$items);
?>
<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div>
	<h2>Debugging info</h2>
	<div class="debug-cont">
		<?php foreach($items as $method => $label):?>
		<div class="item">
			<h3><?php echo $label?></h3>
			<div class="widefat">
				<?php echo function_exists($method)?$method():sprintf(__('Unknown function %s','rhc'),$method)?>
			</div>
		</div>
		<?php endforeach; ?>
	</div>
</div>
