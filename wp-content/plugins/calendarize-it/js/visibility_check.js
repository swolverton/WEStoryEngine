/* when calendar is rendered on a container that is not visible, it doesnt gets the correct height calculated.*/
function _rhc_check_visibility(){
	jQuery(document).ready(function($){
		if( $('.fullCalendar').is(':visible') && $('.fullCalendar .fc-content').height()<10 ){
			$('.fullCalendar').fullCalendar('render');
		}else if( $('.fullCalendar .fc-content').height()<10 ){
			setTimeout('_rhc_check_visibility()',200);
		}
	});
}
jQuery(document).ready(function($){
	if( jQuery('.fullCalendar').length>0 )setTimeout('_rhc_check_visibility()',200);
});