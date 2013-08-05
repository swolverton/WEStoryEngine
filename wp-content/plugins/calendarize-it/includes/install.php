<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/
function handle_rhc_install(){
	//----for taxonomy metadata support
	global $wpdb;
	$charset_collate = '';  
	if ( ! empty($wpdb->charset) )$charset_collate = "DEFAULT CHARACTER SET $wpdb->charset";
	if ( ! empty($wpdb->collate) )$charset_collate .= " COLLATE $wpdb->collate";
	$tables = $wpdb->get_results("show tables like '{$wpdb->prefix}taxonomymeta'");
	if (!count($tables))
	 $wpdb->query("CREATE TABLE {$wpdb->prefix}taxonomymeta (
	   meta_id bigint(20) unsigned NOT NULL auto_increment,
	   taxonomy_id bigint(20) unsigned NOT NULL default '0',
	   meta_key varchar(255) default NULL,
	   meta_value longtext,
	   PRIMARY KEY  (meta_id),
	   KEY taxonomy_id (taxonomy_id),
	   KEY meta_key (meta_key)
	 ) $charset_collate;");
	 //---- Capabilities for the rhcvents custom post type
	$WP_Roles = new WP_Roles();	
	foreach(array(
		'calendarize_author',
		'edit_'.RHC_CAPABILITY_TYPE,
		'read_'.RHC_CAPABILITY_TYPE,
		'delete_'.RHC_CAPABILITY_TYPE,
		'edit_'.RHC_CAPABILITY_TYPE.'s',
		'edit_others_'.RHC_CAPABILITY_TYPE.'s',
		'edit_published_'.RHC_CAPABILITY_TYPE.'s',
		'delete_published_'.RHC_CAPABILITY_TYPE.'s',
		'delete_private_'.RHC_CAPABILITY_TYPE.'s',
		'delete_others_'.RHC_CAPABILITY_TYPE.'s',
		'publish_'.RHC_CAPABILITY_TYPE.'s',
		'read_private_'.RHC_CAPABILITY_TYPE.'s',
		
		'manage_'.RHC_VENUE,
		'manage_'.RHC_CALENDAR,
		'manage_'.RHC_ORGANIZER,
		
		'rhc_options',
		'rhc_license'
		) as $cap){
		$WP_Roles->add_cap( RHC_ADMIN_ROLE, $cap );
	}	
	//----
	global $rhc_plugin;
	include RHC_PATH.'includes/bundle_default_custom_fields.php';
	if(isset($postinfo_boxes)){
		//--save:
		$options = get_option($rhc_plugin->options_varname);
		$options = is_array($options)?$options:array();
		if( !isset($options['postinfo_boxes']) ){
			$options['postinfo_boxes']=$postinfo_boxes;
			update_option($rhc_plugin->options_varname,$options);
		}
		//--
	}		 
}


function handle_rhc_uninstall(){
	$WP_Roles = new WP_Roles();
	foreach(array(
		'calendarize_author',
		'edit_'.RHC_CAPABILITY_TYPE,
		'read_'.RHC_CAPABILITY_TYPE,
		'delete_'.RHC_CAPABILITY_TYPE,
		'edit_'.RHC_CAPABILITY_TYPE.'s',
		'edit_others_'.RHC_CAPABILITY_TYPE.'s',
		'edit_published_'.RHC_CAPABILITY_TYPE.'s',
		'delete_published_'.RHC_CAPABILITY_TYPE.'s',
		'delete_private_'.RHC_CAPABILITY_TYPE.'s',
		'delete_others_'.RHC_CAPABILITY_TYPE.'s',		
		'publish_'.RHC_CAPABILITY_TYPE.'s',
		'read_private_'.RHC_CAPABILITY_TYPE.'s',
		
		'manage_'.RHC_VENUE,
		'manage_'.RHC_CALENDAR,
		'manage_'.RHC_ORGANIZER
		) as $cap){
		$WP_Roles->remove_cap( RHC_ADMIN_ROLE, $cap );
	}
	//-----
	delete_site_transient('update_plugins');
}

?>