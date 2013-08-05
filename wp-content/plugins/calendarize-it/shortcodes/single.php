<?php

/**
 * Shortcodes that are meant to be used inside a single page (any custom/default post type)
 *
 * @version $Id$
 * @copyright 2003 
 **/

class rhc_single_shortcoes {
	function rhc_single_shortcoes(){
		foreach(array('venuebox','postinfo','featuredimage') as $shortcode){
			add_shortcode($shortcode, array(&$this,'handle_conditional'));
		}
	}
	
	function handle_conditional($atts,$template='',$code=""){
		extract(shortcode_atts(array(
			'include_conditional_tag' 		=> 'is_singular',
			'meta_key'			=> '',
			'meta_value'		=> '',
			'default'			=> '',//value to give meta_value if it is empty.
			'operator'			=> '=',//TODO: allow other operators.
			'filter' 			=> ''//TODO: allow applying a filter to the value
		), $atts));
		
		//---------test wp conditional tags
		if(''!=trim($include_conditional_tag)){
			$allowed_conditional_tags = array('is_singular','is_page','is_single','is_sticky','is_category','is_tax','is_author','is_archive','is_search','is_attachment');
			$test_tags = explode(',',trim(str_replace(' ','',$include_conditional_tag)));
			if(is_array($test_tags) && count($test_tags)>0){
				$condition_matched = false;
				foreach($test_tags as $test_method){
					if( in_array($test_method,$allowed_conditional_tags) && $test_method() ){
						$condition_matched = true;
						break;
					}
				}
				if(false===$condition_matched){
					return '';
				}
			}
		}
		
		//-------- test for post meta_key conditional value 
		if($meta_key!=''){
			global $post;
			$post_ID = property_exists($post,'ID') ? $post->ID : false;
			if(false!==$post_ID){
				$value = get_post_meta($post_ID,$meta_key,true);
				$value = ''==$value?$default:$value;
				//TODO: allow other operators
				if( $value != $meta_value ){
					//condition was not matched.
					return '';
				}
			}		
		}

/*
echo "<pre>";
print_r($post);
echo "</pre>";		
*/
		//--------
		
		$method = 'handle_'.$code;	
		if(method_exists($this,$method)){
			return $this->$method($atts,$template,$code);
		}else{
			return trim($template);
		}
	}
	
	function handle_venuebox($atts,$template='',$code=""){
	
		global $rhc_plugin;
		$filename = $rhc_plugin->get_template_path('content-venuebox.php');
		ob_start();
		include $filename;
		$content = ob_get_contents();
		ob_end_clean();
		return do_shortcode($content);
	}
	
	function handle_postinfo($atts,$template='',$code=""){
		return rhc_post_info_shortcode::handle_shortcode($atts,$template,$code);
	}
	
	function handle_featuredimage($atts,$template='',$code=""){
		//originally called featured image, it ended up being a custom featured image.
		extract(shortcode_atts(array(
			'custom' 		=> '',
			'size'			=> '',
			'class'			=> ''
		), $atts));
		
		$class.=' attachment- '.$custom;
		
		$arr = explode(',',$size);
		if(count($arr)==2){
			$size = $arr;
		}
		
		global $post;		
		if(''==$custom){
			if( $thumbnail = get_the_post_thumbnail( $post->ID ) ){
				return $thumbnail;
			}		
		}else{
			$attachment_id = get_post_meta( $post->ID, $custom, true);
			if( $image=wp_get_attachment_image( $attachment_id, $size, 0, array('class'=>$class) ) ){
				return $image;
			}
		}
		return '';
	}
}
?>