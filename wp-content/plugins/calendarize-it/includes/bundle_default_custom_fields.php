<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/
if(false): 
__('Event Details','rhc');
__('Start time','rhc');
__('Start date','rhc');
__('End date','rhc');
__('End time','rhc');
__('Calendar','rhc');
__('Organizer','rhc');
__('Name:','rhc');
__('Name','rhc');
__('Email','rhc');
__('Phone','rhc');
__('Website','rhc');
__('Venue Details','rhc');
__('Address','rhc');
__('City','rhc');
__('Zip Code','rhc');
__('State','rhc');
__('Country','rhc');
__('Information','rhc');
__('Get directions','rhc');
endif;
               
$str = <<<EOT
{"detailbox":{"id":"detailbox","columns":"2","span":"6","data":[{"id":"","type":"label","label":"Event Details","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"","taxonomymeta":"","taxonomymeta_field":"","render_cb":"false","post_ID":"13076","date_format":"false","column":"0","span":"12","offset":"0","index":"","frontend":true},{"id":"","type":"postmeta","label":"Start date","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"fc_start","taxonomymeta":"","taxonomymeta_field":"","render_cb":"false","post_ID":"13076","date_format":"F j, Y","column":"0","span":"6","offset":"0","index":"","frontend":true},{"id":"","type":"postmeta","label":"Start time","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"fc_start_time","taxonomymeta":"","taxonomymeta_field":"","render_cb":"false","post_ID":"13076","date_format":"g:i a","column":"0","span":"6","offset":"0","index":"","frontend":true},{"id":"","type":"postmeta","label":"End date","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"fc_end","taxonomymeta":"","taxonomymeta_field":"","render_cb":"false","post_ID":"13076","date_format":"F j, Y","column":"0","span":"6","offset":"0","index":"","frontend":true},{"id":"","type":"postmeta","label":"End time","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"fc_end_time","taxonomymeta":"","taxonomymeta_field":"","render_cb":"false","post_ID":"13076","date_format":"g:i a","column":"0","span":"6","offset":"0","index":"","frontend":true},{"id":"","type":"taxonomy","label":"Calendar","value":"","taxonomy":"calendar","taxonomy_links":"false","postmeta":"","taxonomymeta":"","taxonomymeta_field":"","render_cb":"false","post_ID":"13076","date_format":false,"column":"0","span":"12","offset":"0","index":"","frontend":true},{"id":"","type":"label","label":"Organizer","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"","taxonomymeta":"","taxonomymeta_field":"","render_cb":"false","post_ID":"13076","date_format":"false","column":"1","span":"12","offset":"0","index":"","frontend":true},{"id":"","type":"taxonomy","label":"Name:","value":"","taxonomy":"organizer","taxonomy_links":"false","postmeta":"","taxonomymeta":"","taxonomymeta_field":"","render_cb":"false","post_ID":"13076","date_format":false,"column":"1","span":"12","offset":"0","index":"","frontend":true},{"id":"","type":"taxonomymeta","label":"Email","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"","taxonomymeta":"organizer","taxonomymeta_field":"email","render_cb":"false","post_ID":"13076","date_format":false,"column":"1","span":"12","offset":"0","index":"","frontend":true},{"id":"","type":"taxonomymeta","label":"Phone","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"","taxonomymeta":"organizer","taxonomymeta_field":"phone","render_cb":"false","post_ID":"13076","date_format":false,"column":"1","span":"12","offset":"0","index":"","frontend":true},{"id":"","type":"taxonomymeta","label":"Website","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"","taxonomymeta":"organizer","taxonomymeta_field":"website","render_cb":"false","post_ID":"13076","date_format":false,"column":"1","span":"12","offset":"0","index":"","frontend":true}]},"venuebox":{"id":"venuebox","columns":"2","span":"6","data":[{"id":"","type":"label","label":"Venue Details","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"","taxonomymeta":"","taxonomymeta_field":"","render_cb":"false","post_ID":13076,"date_format":"false","column":"0","span":"12","offset":"0","index":"","frontend":true},{"id":"","type":"taxonomymeta","label":"Address","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"","taxonomymeta":"venue","taxonomymeta_field":"address","render_cb":"false","post_ID":13076,"date_format":false,"column":"0","span":"12","offset":"0","index":"","frontend":true},{"id":"","type":"taxonomymeta","label":"City","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"","taxonomymeta":"venue","taxonomymeta_field":"city","render_cb":"false","post_ID":13076,"date_format":false,"column":"0","span":"12","offset":"0","index":"","frontend":true},{"id":"","type":"taxonomymeta","label":"Zip Code","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"","taxonomymeta":"venue","taxonomymeta_field":"zip","render_cb":"false","post_ID":13076,"date_format":false,"column":"0","span":"6","offset":"0","index":"","frontend":true},{"id":"","type":"taxonomymeta","label":"State","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"","taxonomymeta":"venue","taxonomymeta_field":"state","render_cb":"false","post_ID":13076,"date_format":false,"column":"0","span":"6","offset":"0","index":"","frontend":true},{"id":"","type":"taxonomymeta","label":"Country","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"","taxonomymeta":"venue","taxonomymeta_field":"country","render_cb":"false","post_ID":13076,"date_format":false,"column":"0","span":"12","offset":"0","index":"","frontend":true},{"id":"","type":"label","label":"Information","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"","taxonomymeta":"","taxonomymeta_field":"","render_cb":"false","post_ID":13076,"date_format":"false","column":"1","span":"12","offset":"0","index":"","frontend":true},{"id":"","type":"taxonomymeta","label":"Phone","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"","taxonomymeta":"venue","taxonomymeta_field":"phone","render_cb":"false","post_ID":13076,"date_format":false,"column":"1","span":"12","offset":"0","index":"","frontend":true},{"id":"","type":"taxonomymeta","label":"Email","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"","taxonomymeta":"venue","taxonomymeta_field":"email","render_cb":"false","post_ID":13076,"date_format":false,"column":"1","span":"12","offset":"0","index":"","frontend":true},{"id":"","type":"taxonomymeta","label":"Website","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"","taxonomymeta":"venue","taxonomymeta_field":"website","render_cb":"false","post_ID":13076,"date_format":false,"column":"1","span":"12","offset":"0","index":"","frontend":true},{"id":"","type":"taxonomymeta","label":"Get directions","value":"","taxonomy":"","taxonomy_links":"false","postmeta":"","taxonomymeta":"venue","taxonomymeta_field":"gaddress","render_cb":"false","post_ID":13076,"date_format":false,"column":"1","span":"12","offset":"0","index":"","frontend":true}]}}
EOT;

$postinfo_boxes = json_decode( trim($str) );
$postinfo_boxes = (array)$postinfo_boxes;
?>