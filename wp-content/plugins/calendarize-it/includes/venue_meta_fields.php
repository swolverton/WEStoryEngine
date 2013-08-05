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
				'description'=> __('The description of the venue, will be used in the venue page content.','rhc'),
				'type'		=> 'callback',
				'callback'	=> 'venue_html_description_input'
			),
			(object)array(
				'id'	=>	'address',
				'label'	=> 	__('Address','rhc'),
				'description'=> __('Street address of this venue','rhc')
			),
			(object)array(
				'id'	=> 'city',
				'label'	=> __('City','rhc')
			),
			(object)array(
				'id'	=> 'state',
				'label'	=> __('State/Province/Other','rhc')
			),
			(object)array(
				'id'	=> 'zip',
				'label'	=> __('Postal code','rhc')
			),
			(object)array(
				'id'	=> 'country',
				'label'	=> __('Country','rhc')
			),
			(object)array(
				'label'			=> __('Details for google map','rhc'),
				'type'			=> 'subtitle'
			),
			(object)array(
				'id'	=> 'gaddress',
				'label'	=> __('Google address','rhc'),
				'description'=> __('Optional, if not provided will build the google address using the regular adrress, city, zip, state and country fields previously filled.','rhc')
			),
			(object)array(
				'id'	=> 'glat',
				'label'	=> __('Latitude','rhc'),
				'description'=> __('Optional, if not provided will attempt to use address','rhc')
			),
			(object)array(
				'id'	=> 'glon',
				'label'	=> __('Longitud','rhc'),
				'description'=> __('Optional, if not provided will attempt to use address','rhc')
			),
			(object)array(
				'id'	=> 'gzoom',
				'label'	=> __('Zoom','rhc'),
				'description'=> __('Optional, specify the google map zoom value (default: 13)','rhc')
			),
			(object)array(
				'id'	=> 'ginfo',
				'label'	=> __('Text for info windows','rhc'),
				'type'	=> 'textarea'
			),
			(object)array(
				'label'			=> __('Contact Details','rhc'),
				'type'			=> 'subtitle'
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
				'description'=> __('Url to an image to display at the venue category page', 'rhc')
			)/*,
			(object)array(
				'label'			=> __('Custom template','rhc'),
				'type'			=> 'subtitle'
			),
			(object)array(
				'id'	=> 'template_page_id',
				'label'	=> __('Template page id (optional)','rhc'),
				'description'=> __('Specify the id of a page that you want to use as model for this venue', 'rhc')
			)		
			*/
			/* capacity info is not used in this version.
			,
			(object)array(
				'label'			=> __('Information','rhc'),
				'type'			=> 'subtitle'
			),
			(object)array(
				'id'	=> 'capacity',
				'label'	=> __('Capacity','rhc')
			)
			*/
		);		

?>