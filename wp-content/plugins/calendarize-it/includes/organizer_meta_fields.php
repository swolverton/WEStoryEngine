<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/
		$fields = array(
			(object)array(
				'id'		=>	'content',
				'label'		=> 	__('HTML Description','rhc'),
				'description'=> __('The description of the organizer, will be used in the organizer page content.','rhc'),
				'type'		=> 'callback',
				'callback'	=> 'organizer_html_description_input'
			),		
			(object)array(
				'label'	=> __('Contact Details','rhc'),
				'type'	=> 'subtitle'
			),
			(object)array(
				'id'	=> 'phone',
				'label'	=> __('Phone','rhc')
			),
			(object)array(
				'id'	=> 'email',
				'label'	=> __('Email','rhc')
			),
			(object)array(
				'id'	=> 'website',
				'label'	=> __('Website','rhc')
			),
			(object)array(
				'id'	=> 'image',
				'label'	=> __('Image','rhc'),
				'description'=> __('Url to an image to display at the organizer category page', 'rhc')
			)
		);	

?>