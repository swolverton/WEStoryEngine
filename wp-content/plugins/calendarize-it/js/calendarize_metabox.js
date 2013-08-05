jQuery(document).ready(function($){
	var fullcalendar = $('#calendarize').fullCalendar({
		header: {
			left: 'prevYear,prev,next,nextYear today ',
			center: 'title',
			right: 'month,agendaWeek,agendaDay'
		},
		editable: true,
		transition: {
			notransition: 1
		},
		selectable:true,
		selectHelper:true,
		select: fc_select,
		events: calendarize_events_source,
		eventClick: function(event,jsEvent,view){
			fc_select(event.start, event.end, event.allDay, jsEvent, view);
		},
		eventDrop: fc_event_drop,
		eventResize: fc_event_resize,
		dragOpacity:{'':.7}
	});
	
	if( $('#fc_start').length>0 && ''!=$('#fc_start').val() ){
		var sdate = new Date( $('#fc_start').val() );
		if(!isNaN(sdate)){
			$('#calendarize').fullCalendar('gotoDate', sdate );			
		}
	}
	
	$('body').append( $('.fc-dialog') );
	 init_dialog();
	
	$('.fc-dg-cancel').live('click',function(){fc_dg_close(null,null);});
	$('.fc-dg-ok').live('click',function(){
		$('body').find('.fc-dialog .fc-status').fadeIn('fast');
		var data = [];
		$('.fc-dialog .fc_input').each(function(i,inp){
			if(inp.type=='checkbox'){
				if(inp.name!='fc_dow_except[]'){
					$('#'+inp.name).val( ($(inp).is(':checked')?1:0) );				
				}			
			}else{
				$('#'+inp.name).val( $(inp).val() );
			}
		});
		
		var dow_except = [];
		$('input:checkbox.fc_dow_except:checked').each(function(i,o){
			dow_except[dow_except.length]=$(o).val();
		});

		$('#fc_dow_except').val( dow_except.join('|') );

		$('body').find('.fc-dialog .fc-status').stop().fadeOut('fast');
		fc_dg_close(null,null);
		
		$('#calendarize').fullCalendar('refetchEvents');
	});
	$('.fc-dg-remove').live('click',function(){
		$('.fc-dialog .fc_input').each(function(i,inp){
			$('#'+inp.name).val( '' );
		});
		$('#calendarize').fullCalendar('refetchEvents');
		fc_dg_close(null,null);
	});
	//---time mask
	$('.fc_start_time, .fc_end_time').live('click keyup',function(e){
		validTime(e.target,e.keyCode);
		if( $('.fc_start_time').val()=='' ){
			$('.fc-dialog .fc_allday').attr('checked',true);
		}else{
			$('.fc-dialog .fc_allday').attr('checked',false);
		}
		return true;
	});
	//----
	$('.fc_start,.fc_end,.fc_end_interval').live('click',function(){
		if( $(this).parent().parent().find('.fc_start_fullcalendar_holder').is(':visible') ){
			$(this).parent().parent().find('.fc_start_fullcalendar_holder').hide();
		}else{
			$('.fc_start_fullcalendar_holder').hide();
			$(this).parent().parent().find('.fc_start_fullcalendar_holder').fadeIn('fast');
		}
	});	
	$('.fc_start_fullcalendar_holder')
		.live('mouseenter',function(e){$(this).removeClass('close-on-click');})
		.live('mouseleave',function(e){$(this).addClass('close-on-click');})
	$('body').click(function(e){
		$('.fc_start_fullcalendar_holder.close-on-click').removeClass('close-on-click').hide();
	});	
	
	//---farbtastic
	$('.fc-dialog')
		.find('.fc_color').attr('value', $('#fc_color').val() ).change().end()
		.find('.fc_text_color').val( $('#fc_text_color').val() ).change().end()
	;	
	$('.pop-farbtastic').each(function(i,o){
		$(this).farbtastic($(this).attr('rel')).hide();
	});	
	$('.farbtastic-choosecolor').click(function(e){
		var helper = $(this).parent().find('.pop-farbtastic');
		if(helper.is(':visible')){
			helper.slideUp();
			$(this).addClass('show-colorpicker').removeClass('hide-colorpicker');
		}else{
			helper.slideDown();
			$(this).addClass('hide-colorpicker').removeClass('show-colorpicker');
		}
		var tmp = $(this).attr('rel');
		$(this).attr('rel',$(this).attr('title'));
		$(this).attr('title',tmp);		
	});
	$('.farbtastic-choosecolor').mousedown(function(e){$(this).parent().find('input').trigger('focus');});	
	
	//---
	$('.fc_color_input').change(function(){
		if($(this).val()==''){
			$(this).val('#');
		}
	});
	
	//----
	$('.fc_interval').live('change',function(e){
		if( $(this).val()=='1 DAY' ){
			$('.fc_dow_except_holder').show();
		}else{
			$('.fc_dow_except_holder').hide();
			$('.fc-dialog input.fc_dow_except:checkbox').attr('checked',false);
		}
	}).trigger('change');
});

function calendarize_events_source(start,end,callback){
	jQuery(document).ready(function($){
		var data = [];
		$('.calendarize_meta_data').each(function(i,inp){
			if(inp.type=='checkbox'){
				data[data.length] = [inp.name,($(inp).is(':checked')?1:0)];
			}else{
				data[data.length] = [inp.name,($(inp).val())];
			}
		});
		
		var args = {
			action: 	'calendarize_' + $('#post_type').val(),
			post_ID: 	$('#post_ID').val(),
			start:		Math.round(start.getTime() / 1000),
			end:		Math.round(end.getTime() / 1000),
			'data[]': 	data
		};
	
		$.post(ajaxurl,args,function(data){
			if(data.R=='OK'){
				callback(data.EVENTS);
			}else if(data.R=='ERR'){
				alert(data.MSG);
			}else{
				alert('Unexpected error');
			}
		},'json');
	});
}

function fc_select(startDate, endDate, allDay, jsEvent, view){
	jQuery(document).ready(function($){
		var pos = $(jsEvent.target).offset();
		
		$('.fc-dialog')
			.find('.fc_start').val( $.fullCalendar.formatDate(startDate,'yyyy-MM-dd') ).end()
			.find('.fc_end').val( $.fullCalendar.formatDate(endDate,'yyyy-MM-dd') ).end()
			.find('.fc_start_time').val( (allDay?'':$.fullCalendar.formatDate(startDate,'hh:mm tt')) ).end()
			.find('.fc_end_time').val( (allDay?'':$.fullCalendar.formatDate(endDate,'hh:mm tt')) ).end()
			.find('.fc_allday').attr('checked',(allDay?true:false)).end()
			.find('.fc_interval').val( $('#fc_interval').val() ).change().end()
			.find('.fc_end_interval').val( $('#fc_end_interval').val() ).end()
			.find('.fc_color').val( $('#fc_color').val() ).change().end()
			.find('.fc_text_color').val( $('#fc_text_color').val() ).change().end()
			.find('.fc_click_link').val( $('#fc_click_link').val() ).change().end()
			.find('.fc_click_target').val( $('#fc_click_target').val() ).change().end()
			.find('.fc-status').hide().end()		
			.stop().show()
			.css('margin-left',0)
			.offset(pos)
		;		

		$('.fc-dialog input.fc_dow_except:checkbox').attr('checked',false);
		var arr= $('#fc_dow_except').val().split('|');
		$.each( arr, function(i,dow){
			$('.fc-dialog input.fc_dow_except:checkbox').each(function(j,inp){
				if( $(inp).val()==dow ){
					$(inp).attr('checked',true);
				}
			});
		});
		
		$('body').find('.fc-dialog')
			.css('margin-left', $(jsEvent.target).width() );
	});
}

function init_dialog(){
	jQuery(document).ready(function($){
		$('body').find('.fc-dialog')
			.draggable({
				handle:'.hndle'
			})
			.find('.fc_start_fullcalendar_holder').show().end()
			.find('.fc_start_fullcalendar_holder .fc_start_fullcalendar').fullCalendar('destroy').fullCalendar({
				header:{
					left:'title',
					center:'',
					right:'prevYear,prev,next,nextYear'
				},
				dayClick:function( date, allDay, jsEvent, view ) { 
					$(".fc_start")
						.val( $.fullCalendar.formatDate(date,'yyyy-MM-dd') )
						.focus(); 
					$(".fc_start_fullcalendar_holder").hide();
				}
			}).end()
			.find('.fc_end_fullcalendar_holder .fc_start_fullcalendar').fullCalendar('destroy').fullCalendar({
				header:{
					left:'title',
					center:'',
					right:'prevYear,prev,next,nextYear'
				},
				dayClick:function( date, allDay, jsEvent, view ) { 
					$(".fc_end")
						.val( $.fullCalendar.formatDate(date,'yyyy-MM-dd') )	
						.focus(); 
					$(".fc_start_fullcalendar_holder").hide();						
				}
			}).end()
			.find('.fc_end_interval_fullcalendar_holder .fc_start_fullcalendar').fullCalendar('destroy').fullCalendar({
				header:{
					left:'title',
					center:'',
					right:'prevYear,prev,next,nextYear'
				},
				dayClick:function( date, allDay, jsEvent, view ) { 
					$(".fc_end_interval")
						.val( $.fullCalendar.formatDate(date,'yyyy-MM-dd') )	
						.focus(); 
					$(".fc_start_fullcalendar_holder").hide();						
				}
			}).end()
			.find('.fc_start_fullcalendar_holder,.fc_end_fullcalendar_holder,.fc_end_interval_fullcalendar_holder').hide().end()		
			.find('.tabs-panel').hide().end()
			.find('.tabs a').click(function(e){
				$(this)
					.parent().parent()
						.find('.tabs').addClass('hide-if-no-js').removeClass('tabs').end()
						.parent()
							.find('.tabs-panel').hide().end()
							.find( $(this).attr('rel') ).show().end()
						.end()
					.end()
					.removeClass('hide-if-no-js').addClass('tabs')
					.end()	
			}).first().trigger('click').end()	
			.end()
			.hide()
	});
}

function fc_dg_close(view, jsEvent){
	jQuery(document).ready(function($){
		$('body').find('.fc-dialog').fadeOut('fast');	
	});
}

function fc_event_resize( event, dayDelta, minuteDelta, revertFunc, jsEvent, ui, view ) {
	jQuery(document).ready(function($){
		$('#fc_allday').val( 0 );
		$('#fc_start').val( $.fullCalendar.formatDate(event.start,'yyyy-MM-dd') );
		$('#fc_start_time').val( $.fullCalendar.formatDate(event.start,'hh:mm tt') );
		$('#fc_end').val( $.fullCalendar.formatDate(event.end,'yyyy-MM-dd') );
		$('#fc_end_time').val( $.fullCalendar.formatDate(event.end,'hh:mm tt') );
	});
}

function fc_event_drop( event, dayDelta, minuteDelta, allDay, revertFunc, jsEvent, ui, view ) { 
	jQuery(document).ready(function($){
		$('#fc_allday').val( (event.allDay?1:0) );
		$('#fc_start').val( $.fullCalendar.formatDate(event.start,'yyyy-MM-dd') );
		$('#fc_start_time').val( $.fullCalendar.formatDate(event.start,'hh:mm tt') );
		$('#fc_end').val( $.fullCalendar.formatDate(event.end,'yyyy-MM-dd') );
		$('#fc_end_time').val( $.fullCalendar.formatDate(event.end,'hh:mm tt') );
	});
}

