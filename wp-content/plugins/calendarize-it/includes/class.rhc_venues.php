<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/

 
if(defined('RHC_PATH')):

function venue_html_description_input($tab,$i,$o,$r){
	ob_start();
	wp_editor($r->get_value($tab,$i,$o),$r->get_id($tab,$i,$o),array(
		'textarea_name' => $r->get_name($tab,$i,$o)
	));
	
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
} 
		//-- Venues --------------------------------------
		//-- included by class.righthere_calendar.php
		
		require 'venue_meta_fields.php';    
		
		$labels = array(
					'name' 				=> __( 'Venues', 'rhc' ),
					'singular_name' 	=> __( 'Venue', 'rhc' ),
					'search_items' 		=> __( 'Search Venue', 'rhc' ),
					'popular_items' 	=> __( 'Popular Venue', 'rhc' ),
					'all_items' 		=> __( 'All venues', 'rhc' ),
					'parent_item' 		=> null,
					'parent_item_colon' => null,
					'edit_item' 		=> __( 'Edit venue', 'rhc' ), 
					'update_item' 		=> __( 'Update venue', 'rhc' ),
					'add_new_item' 		=> __( 'Add venue', 'rhc' ),
					'new_item_name' 	=> __( 'New venue', 'rhc' )
				);
		global $rhc_plugin;
		new custom_taxonomy_with_meta(
			RHC_VENUE,
			array(RHC_EVENTS),
			array(
		    	'hierarchical' => true,
		    	'labels' => $labels,
		    	'show_ui' => true,
		    	'query_var' => true,
		    	'rewrite' => array( 'slug' => $rhc_plugin->get_option('rhc-venues-slug',RHC_VENUE,true) ),
				'capabilities'	=> array(
					'manage_terms'	=> 'manage_'.RHC_VENUE,
					'edit_terms'	=> 'manage_'.RHC_VENUE,
					'delete_terms'	=> 'manage_'.RHC_VENUE,
					'assign_terms'	=> 'manage_'.RHC_VENUE
				)
			),
			$fields,//defined in venue_meta_fields.php
			RHC_PATH
		);
endif;		

function venue_admin_customization($taxonomy){
	if($taxonomy==RHC_VENUE){
?>
<style>
.form-field label[for=tag-description],
.form-field textarea#tag-description,
.form-field textarea#tag-description + p,
.tagcloud {display:none;}
</style>
<?php		
	}
}
add_action('add_tag_form_pre', 'venue_admin_customization', 10, 1);

function venue_pre_edit_form($tag,$taxonomy){
?>
<style>
.form-field label[for=description],
.form-field textarea#description,
.form-field textarea#description + br + span.description {
display:none;
}
</style>
<?php
}
add_action(RHC_VENUE . '_pre_edit_form', 'venue_pre_edit_form', 10, 2);

// add tax meta fields to post info metabox
function filter_venue_taxonomy_meta_field($meta_fields){
	$meta_fields = is_array($meta_fields)?$meta_fields:array();	
	require 'venue_meta_fields.php'; 
	return array_merge($meta_fields,$fields);
}
add_filter( RHC_VENUE.'_taxonomy_meta_fields','filter_venue_taxonomy_meta_field',10,1);
?>