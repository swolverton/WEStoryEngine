<?php

/**
 * 
 *
 * @version $Id$
 * @copyright 2003 
 **/
 
if (!function_exists("quoted_printable_encode")) {
	function quoted_printable_encode($string) {
	      $string = str_replace(array('%20', '%0D%0A', '%'), array(' ', "\r\n", '='), rawurlencode($string));
	      $string = preg_replace('/[^\r\n]{73}[^=\r\n]{2}/', "$0=\r\n", $string);
	
	      return $string;
	}
}

class events_to_vcalendar {
	var $events = array();
	var $dtend_is_exclusive = true;//google calendar and ical seem to treat it that way.
	function events_to_vcalendar($events) {
		$this->events = $events;
	}
	
	function get_vcalendar(){
		ob_start();
?>
BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//RIGHTHERE//CALENDARIZE IT V1.0//EN

<?php echo $this->get_vcalendar_body() ?>
END:VCALENDAR

<?php		
		$content = ob_get_contents();
		ob_end_clean();
		return $content;
	}
	
	function get_vcalendar_body(){
		$properties = array(
			'UID'		=> 'id',
			'DTSTART'	=> 'start',
			'DTEND'		=> 'end',
			'SUMMARY'	=> 'title',
			'RRULE'		=> 'fc_rrule',
			'EXDATE'	=> 'fc_exdate',
			'RDATE'		=> 'fc_rdate',
			'URL'		=> 'url'
		);
		$str = "";
		
		if(!empty($this->events)){
			foreach($this->events as $event){

				$str .= "BEGIN:VEVENT\r\n";
				foreach( $properties as $property => $field ){
					$method = "_".strtolower($property);
					if(method_exists($this,$method)){
						$str.=$this->$method( $field, $property, $event );
					}
				}
				$str.= "END:VEVENT\r\n";
				$str.= "\r\n";			
			}
		}
		
		return $str;
	}
	
	function text_encode($text){
		return quoted_printable_encode($text);
	}
	
	function unencoded_text( $field, $property, $e ){
		if(!isset($e[$field]) || ''==trim( $e[$field] ) )return '';
		return $this->vevent_row($property, $e[$field] );	
	}
	
	function datetime(  $field, $property, $e ){
		if(!isset($e[$field]))return '';
		$ts = strtotime($e[$field]);
		if(false==$ts||-1==$ts)return '';
		return $this->vevent_row($property, date( 'Ymd\THis', $ts ) );
	}
	
	function allday_date( $field, $property, $e ){
		if(!isset($e[$field]))return '';
		$ts = strtotime($e[$field]);
		if($field=='end' && $this->dtend_is_exclusive && $e['fc_start']!=$e['fc_end']){
			$ts = $ts + 86400;//we use fc_end inclusive, whilst most ical implementation seem to do dtend exclusive.
		}
		if(false==$ts||-1==$ts)return '';
		return $this->vevent_row($property.';VALUE=DATE', date( 'Ymd', $ts ) );
	}
	
	function vevent_row($field,$value){
		return sprintf( "%s:%s\r\n", $field, $value );
	}
	
	function _uid( $field, $property, $e ){
		$arr = parse_url( site_url() );
		$id = $e['id'].'@'.$arr['host'];
		return $this->vevent_row($property, $id );
	}
	
	function _dtstart( $field, $property, $e ){
		return isset($e['allDay'])&&$e['allDay']? $this->allday_date( $field, $property, $e ) : $this->datetime(  $field, $property, $e );
	}
	
	function _dtend( $field, $property, $e ){
		return isset($e['allDay'])&&$e['allDay']? $this->allday_date( $field, $property, $e ) : $this->datetime(  $field, $property, $e );
	}
	
	function _rrule( $field, $property, $e ){
		if(!isset($e[$field]) || ''==trim( $e[$field] ) )return '';
		$e[$field]=rtrim($e[$field], ';');//remove ending semicolon
		return $this->vevent_row($property, $e[$field] );			
		//return $this->unencoded_text( $field, $property, $e );
	}
	
	function _exdate($field, $property, $e){
		if(!isset($e[$field]) || ''==trim( $e[$field] ) )return '';
		return $this->vevent_row($property, $e[$field] );
	}
	
	function _rdate($field, $property, $e){
		if(!isset($e[$field]) || ''==trim( $e[$field] ) )return '';
		//return sprintf( "%s;VALUE=DATE:%s\r\n", $property, $e[$field] );
		return $this->vevent_row($property, $e[$field] );
	}
	
	function _summary( $field, $property, $e ){
		return $this->unencoded_text( $field, $property, $e );
		//return $this->vevent_row('SUMMARY', $this->text_encode( $e['title'] ) );
		//return sprintf( "SUMMARY;ENCODING=QUOTED-PRINTABLE:%s\r\n", $this->text_encode( $e['title'] ) );
	}
	
	function _url( $field, $property, $e ){
		return $this->unencoded_text( $field, $property, $e );
	}
	

}
 



/*
     BEGIN:VCALENDAR
     VERSION:2.0
     PRODID:-//hacksw/handcal//NONSGML v1.0//EN

     BEGIN:VEVENT
     DTSTART:19970714T170000Z
     DTEND:19970715T035959Z
     SUMMARY:Bastille Day Party
     END:VEVENT

     END:VCALENDAR
*/
?>