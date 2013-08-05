<?php

/**
 * 
 * I control what template is used.
 * @version $Id$
 * @copyright 2003 
 **/

class rhc_template_frontend {
	function rhc_template_frontend(){
		global $rhc_plugin;
		if( '1'!=$rhc_plugin->get_option('template_archive')){
			add_filter('archive_template', array(&$this,'archive_template'));	
		}
		if( '1'!=$rhc_plugin->get_option('template_single')){
			add_filter('single_template', array(&$this,'single_template'),1,1);
			//add_filter('page_template', array(&$this,'page_template'),1,1);
		}
		if( '1'!=$rhc_plugin->get_option('template_taxonomy')){
			add_filter('taxonomy_template', array(&$this,'taxonomy_template'));	
			//add_filter('category_template', array(&$this,'taxonomy_template'));	
		}
		
		add_filter( 'query_vars', array(&$this,'query_vars') );
	}
	
	function get_template_path(){
		global $rhc_plugin;
		return $rhc_plugin->get_template_path();
	}
	
	function query_vars($vars){
		array_push($vars,RHC_DISPLAY);
		return $vars;
	}
	
	function is_calendar(){
		global $rhc_plugin;
		return (get_query_var( RHC_DISPLAY )==$rhc_plugin->get_option('rhc-visualcalendar-slug',RHC_VISUAL_CALENDAR,true));
	}
	
	function archive_template($template){	
		if( $this->is_calendar() ){
			global $rhc_plugin,$wp_query,$post; 
			$template_page_id = intval($rhc_plugin->get_option('calendar_template_page_id',0,true));
			if($template_page_id>0){
				$wp_query = new WP_Query('page_id='.$template_page_id);
				$o = $wp_query->get_queried_object();				
				//----- without this, template selection does not gets correctly done, always default.
				$post = $o;
				//------------			
				$template = get_page_template();//fetch template before overwritting post.
				$o->post_content = $this->archive_template_content($o->post_content);	
			}else{
				$template = $this->query_template( $this->get_template_path().'archive-'.get_query_var( 'post_type' ).'-calendar.php' );			
			}			
		}	
		return $template;
	}
	
	function archive_template_content($content){
		$filename = $this->get_template_path().'content-calendar.php';
		ob_start();
		if(file_exists($filename)){
			include($filename);
		}else{
			echo '[calendarizeit]';
		}	
		$output = ob_get_contents();
		ob_end_clean();
		return $this->inject_content($content,$output);
	}
	
	function page_template($template){
		return $template;
	}
	
	function single_template($template){	
		global $wp_query,$wp_the_query;
		$o = get_queried_object();
	
		if($o->post_type==RHC_EVENTS){
			global $rhc_plugin; 
			$template_page_id = intval($rhc_plugin->get_option('event_template_page_id',0,true));
			if($template_page_id>0){
				$copy_fields = array(
				'ID',
				'post_author','post_date','post_date_gmt'
				,'post_content','post_title','post_excerpt','post_status'
				,'comment_status','ping_status','post_password','post_name','to_ping','pinged','post_modified','post_modified_gmt','post_content_filtered'
				,'post_parent','guid','menu_order'
				,'post_type'
				,'post_mime_type','comment_count','ancestors','filter');
				$copy_fields = apply_filters('rhc_single_template_copy_fields',$copy_fields);
				$values = array();
				foreach( $copy_fields as $field){
					$values[$field] = $o->$field;
				}			
			
				global $wp_filter;	
				if(isset($wp_filter['pre_get_posts'])){
					$bak = $wp_filter['pre_get_posts'];
					unset($wp_filter['pre_get_posts']);				
				}else{
					$bak = false;
				}			
			
				$wp_query = new WP_Query('page_id='.$template_page_id);
			
				$o = $wp_query->get_queried_object();	
				$wrap = $o->post_content;			
				//----- without this, template selection does not gets correctly done, always default.
				global $post;
				$post = $o;
				$post = is_object($post)?$post:(object)array();
				$post->rhc_template_id = $template_page_id;
				$post->post_status = 'publish';//force it as publish 
				//------------			
				$template = get_page_template();//fetch template before overwritting post.

				if(false!==$bak){
					$wp_filter['pre_get_posts'] = $bak;
				}
				
				foreach( $copy_fields as $field){
					$post->$field = $values[$field];
				}
				$post->post_content = $this->single_template_content($o->post_content,$wrap);		
				$wp_query->post = $post;		
				$wp_the_query = $wp_query;	
			}else{
				global $wp_query;
				//-- this is to much hacking.  TODO, find an alternative.
				$wp_query->is_single = false;
				$wp_query->is_page = true;
			
				$o->post_content = $this->single_template_content($post->post_content);	
				$template = get_page_template();			
			}			
			//infocus theme fix: autop not applied: if(class_exists('Mysitemyway'))$o->post_content = apply_filters('the_content',$o->post_content);
		}
		return $template;
	}
	
	function single_template_content($content,$wrap=''){
		$filename = $this->get_template_path().'content-single-event.php';
		ob_start();
		if(file_exists($filename)){
			include($filename);
		}else{
?>
[post_info]
<div class="single-event-gmap-holder">
[single_venue_gmap width=960 height=250]
</div>
<?php echo $content ?>
<?php		
		}	
		$output = ob_get_contents();
		ob_end_clean();
		return $this->inject_content($wrap, $output);
	}
		
	function taxonomy_template($template){	
		global $wp_query,$wp_the_query;

		$cat = $wp_query->get_queried_object();
		$term_id 	= $cat->term_id;
		$name 		= $cat->name;
		$taxonomy	= $cat->taxonomy;

		$template_page_id = $this->get_taxonomy_template_page_id($term_id,$taxonomy);
		if($template_page_id){
			global $wp_filter;	
			if(isset($wp_filter['pre_get_posts'])){
				$bak = $wp_filter['pre_get_posts'];
				unset($wp_filter['pre_get_posts']);				
			}else{
				$bak = false;
			}	
					
			$wp_query = new WP_Query('page_id='.$template_page_id);
			$o = $wp_query->get_queried_object();
			//----- without this, template selection does not gets correctly done, always default.
			global $post;
			$post = $o;
			//------------
			$template = get_page_template();

			if(false!==$bak){
				$wp_filter['pre_get_posts'] = $bak;
			}
			
			$post_content = $this->get_taxonomy_content($term_id,$taxonomy,$o->post_content);
/*
			$post_content = str_replace("\n","",$post_content);//autop adds p tags
			$post_content = str_replace("\r","",$post_content);
			$post_content = str_replace("\t","",$post_content);
*/
			$o->post_title = $name;
			$o->post_content = do_shortcode($post_content);
			$wp_query->post = $o;	
			$wp_the_query = $wp_query;

			return $template;		
		}
		return $this->_taxonomy_template($template);
	}

	function _taxonomy_template($template){	
		if( $this->is_calendar() ){
			$template = $this->query_template( $this->get_template_path().'taxonomy-calendar.php' );				
		}else{
			$map_original_name = array(
				RHC_VENUE 		=> 'venue',
				RHC_ORGANIZER	=> 'organizer',
				RHC_CALENDAR	=> 'calendar'
			);
			$o = get_queried_object();
			$filename = sprintf('%staxonomy-%s.php',
				$this->get_template_path(),
				isset($map_original_name[$o->taxonomy])?$map_original_name[$o->taxonomy]:$o->taxonomy
			);

			if(file_exists( $filename )){
				return $filename;
			}
		}		

		return $template;
	}
	
	function get_taxonomy_template_page_id($term_id,$taxonomy){
		global $wpdb;
		$page_id = intval(get_term_meta($term_id,'template_page_id',true));
		if($page_id==0 && in_array($taxonomy,array(RHC_VENUE,RHC_ORGANIZER,RHC_CALENDAR))){
			global $rhc_plugin; 
			$page_id = intval($rhc_plugin->get_option('taxonomy_template_page_id',0,true));
		}

		if($page_id>0){
			if('page'==$wpdb->get_var("SELECT post_type FROM {$wpdb->posts} WHERE ID={$page_id}",0,0)){
				return $page_id;		
			}
		}
		
		return 0;
	}
	
	function get_taxonomy_content($_term_id,$_taxonomy,$wrap=''){
		global $term_id,$taxonomy;
		$term_id = $_term_id;
		$taxonomy = $_taxonomy;
		$term = get_term($term_id,$taxonomy);
		$content = get_term_meta($term_id,'content',true);
		$content = trim($content)==''?$term->description:$content;
		
		$website = get_term_meta($term_id,'website',true);
		$href = false===strpos($website,'://')?'http://'.$website:$website;
		ob_start();
		
		$filename1 = $this->get_template_path().'content-taxonomy-'.$taxonomy.'.php';
		$filename2 = $this->get_template_path().'content-taxonomy.php';
		if(file_exists($filename1)){
			include($filename1);
		}else if(file_exists($filename2)){
			include($filename2);
		}else{	
	?>
	<div class="venue-container custom-content-area">
		<div class="venue-top-info">
			<div class="venue-small-map"><?php the_venue_map() ?></div>
	        <div class="venue-name"><?php the_venue_title()?></div>
			<div class="venue-details-holder">
				<div class="venue-image-holder"><?php the_venue_image()?></div>
				<div class="venue-defails">
	            	<?php the_venue_detail( array('label'=>__('Address','rhc'),'field'=>'gaddress'))?>
					<?php the_venue_detail( array('label'=>__('Telephone','rhc'),'field'=>'phone'))?>
					<?php the_venue_detail( array('label'=>__('Email','rhc'),'field'=>'email'))?>
					<?php the_venue_detail( array('label'=>__('Website','rhc'),'field'=>'website'))?>
					<div class="venue-description"><?php the_venue_content();?></div>
				</div>
	           
			</div>
			<div class="clear"></div>
		</div>
		[calendarizeit defaultview="rhc_event"]
	</div>
	<?php
		}
		$output = ob_get_contents();
		ob_end_clean();
		return $this->inject_content($wrap,$output);
	}
	
	function query_template($filename){
		if(file_exists($filename)){
			return $filename;
		}else{
			$filename = $this->get_template_path().'calendar.php';
			if(file_exists( $filename )){
				return $filename;
			}else{
				return RHC_PATH.'templates/default/calendar.php';
			}
		}
	}
	
	function inject_content($wrap,$content){
		if(false!==strpos($wrap,'[CONTENT]')){
			$content = str_replace('[CONTENT]',$content,$wrap);
		}else{
			$content = $wrap.$content;
		}
		return $content;
	}
}
?>