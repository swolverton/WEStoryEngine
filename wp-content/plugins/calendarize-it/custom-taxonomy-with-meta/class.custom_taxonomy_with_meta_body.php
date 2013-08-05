<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 * Author: Alberto Lau (Righthere.com)
 **/


class custom_taxonomy_with_meta_body {
	var $taxonomy;
	function custom_taxonomy_with_meta_body ($taxonomy,$meta,$args=array()){
		//------------
		$defaults = array(
			'template'		 		=> '<div class="form-field {class} {required}">{label}{input}{description}</div>',
			'required_class' 		=> 'form-required',
			'description_template'	=> '<p>%s</p>',
			'subtitle_template'		=> '<h3>%s</h3>',
			'term_id'				=> false,
			'pluginpath'			=> ''
		);
		foreach($defaults as $property => $default){
			$this->$property = isset($args[$property])?$args[$property]:$default;
		}
		//-----------
		if(!is_array($meta)||empty($meta))return;
		$this->taxonomy = $taxonomy;
		$this->render_edit_tags($meta);
	}
	
	function render_edit_tags($meta){
		$tab = null;
		require_once "taxonomy-metadata.php";
		//require_once $this->pluginpath.'options-panel/class.pop_input.php';
		foreach($meta as $i => $o){
			$o->type = property_exists($o,'type')?$o->type:'text';
			
			if(in_array($o->type,array('subtitle'))){
				$method = "__".$o->type;
				$this->$method($tab,$i,$o);
				continue;	
			}
			
			if(false!==$this->term_id&&$this->term_id>0){
				$o->load_option = property_exists($o,'load_option')?$o->load_option:true;
				if($o->load_option){
					$o->value = get_term_meta($this->term_id, $this->get_meta_key(null,$i,$o), true);
				}
			}
			$output = $this->template;
			$output = str_replace("{required}",(property_exists($o,'required')&&$o->required?$this->required_class:''),$output);
			$output = str_replace("{class}",($this->get_id(null,$i,$o)),$output);
			$output = str_replace("{label}",($this->label(null,$i,$o)),$output);
			$output = str_replace("{input}",($this->input(null,$i,$o)),$output);
			$output = str_replace("{description}",($this->description(null,$i,$o)),$output);
			echo $output;
		}	
	}
		
	function __subtitle($tab,$i,$o){
		echo sprintf($this->subtitle_template,$o->label);
	}	
		
	function get_el_properties($tab,$i,$o){
		$elp = array();
		if(count(@$o->el_properties)>0){
			foreach($o->el_properties as $prop => $val){
				$elp[] = sprintf("%s=\"%s\"",$prop,$val);
			}
		}
		return implode(' ',$elp);
	}
	
	function get_meta_key($tab,$i,$o){
		return property_exists($o,'name')?$o->name:($this->get_id($tab,$i,$o));	
	}
	
	function get_id($tab,$i,$o){
		return property_exists($o,'id')?$o->id:'tax_meta_'.$i;
	}
	
	function get_name($tab,$i,$o){
		return sprintf("%s_meta[%s]",$this->taxonomy,property_exists($o,'name')?$o->name:($this->get_id($tab,$i,$o)));
	}
	
	function get_value($tab,$i,$o){
		return property_exists($o,'value')?$o->value:(property_exists($o,'default')?$o->default:'');
	}
	
	function label($tab,$i,$o){
		return sprintf('<label for="%s">%s</label>',$this->get_id($tab,$i,$o),(property_exists($o,'label')?$o->label:ucfirst($this->get_id($tab,$i,$o))) );
	}
	
	function input($tab,$i,$o){
		$method = property_exists($o,'type')&&method_exists($this,"_".$o->type)?"_".$o->type:"_text";
		return $this->$method($tab,$i,$o);
	}
	
	function description($tab,$i,$o){
		return property_exists($o,'description')&&''!=trim($o->description)?sprintf($this->description_template,$o->description):'';
	}
	
	function _text($tab,$i,$o){
		return sprintf('<input type="text" name="%s" id="%s"  value="%s" %s />',
			$this->get_name($tab,$i,$o),
			$this->get_id($tab,$i,$o),
			$this->get_value($tab,$i,$o),
			$this->get_el_properties($tab,$i,$o)
		);
	}
	
	function _textarea($tab,$i,$o){
		return sprintf('<textarea name="%s" id="%s"  %s >%s</textarea>',
			$this->get_name($tab,$i,$o),
			$this->get_id($tab,$i,$o),
			$this->get_el_properties($tab,$i,$o),
			$this->get_value($tab,$i,$o)
		);
	}
	
	function _callback($tab,$i,$o){
		if(is_callable($o->callback)){
			return call_user_func($o->callback,$tab,$i,$o,$this);
		}
		return '';
	}		
	
}
 
?>