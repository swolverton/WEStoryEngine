(function($){
	var methods = {
		init : function( options ){
			var settings = $.extend( {
				'editable':	false,
				'mode': 'view'
			}, options);		
			
			$('.fc-dialog').CalendarizeDialog();
			
			function fbd_move_cell(cell,destination){	
				if( destination.hasClass('fbd-checked') ){
					var animation = {opacity:0,left:-30};
				}else{
					var animation = {opacity:0,left:30};
				}
				var _easing = 'linear';
				var _duration = 'fast';
				
				if( destination.find('.fbd-cell').length==0 ){
					$(cell).animate(animation,_duration,_easing,function(){
						$(this).appendTo(destination).css('left',animation.left*-1).animate({opacity:1,left:0});
					});
				}else{
					var cells = destination.find('.fbd-cell');
					if( parseInt($(cell).attr('rel')) > parseInt($(cells[cells.length-1]).attr('rel')) ){
						//$(cell).appendTo(destination);
						$(cell).animate(animation,_duration,_easing,function(){
							$(this).appendTo(destination).css('left',animation.left*-1).animate({opacity:1,left:0});
						});
					}else if(parseInt($(cell).attr('rel')) < parseInt($(cells[0]).attr('rel'))){
						//$(cells[0]).before(cell);
						$(cell).animate(animation,_duration,_easing,function(){
							$(cells[0]).before(cell).prev()
								.css('left',animation.left*-1).animate({opacity:1,left:0});
						});								
					}else{
						for(a=0;a<cells.length;a++){
							if( (parseInt($(cell).attr('rel')) > parseInt($(cells[a]).attr('rel'))) && (parseInt($(cell).attr('rel')) < parseInt($(cells[a+1]).attr('rel'))) ){
								//$(cells[a]).after(cell);
								$(cell).animate(animation,_duration,_easing,function(){
									$(cells[a]).after(cell).next()
										.css('left',animation.left*-1).animate({opacity:1,left:0});
								});		
								break;
							}
						}							
					}
				}
			}
	
			return this.each(function(){
				var data = $(this).data('Calendarize');
				if(!data){
					$(this)
						.data('Calendarize',settings)
						.Calendarize('mode',settings.mode)
					;
					
					//-- add dialog with appropiate taxonomies
					if($(this).find('.fc-lower-head-tools').length==0){
						$(this).find('.fc-header').after('<div class="fc-lower-head-tools"></div>');
						if( $(this).find('.fc-lower-head-tools .fc-filters-dialog').length==0 ){
							$(this).find('.fc-filters-dialog-holder .fc-filters-dialog').clone().appendTo( $(this).find('.fc-lower-head-tools') );
						}
					}	
					//-- limit height of filters-dialog
					var _h = parseInt($(this).find('.fc-content').innerHeight()) * 0.6;			
					_h = _h<300?300:_h;
					$(this).find('.fc-lower-head-tools .fc-filters-dialog').find('.fbd-unchecked').css('max-height',_h+'px');
					//-- tabs click				
					$(this).find('.fc-filters-dialog .fbd-tabs a').on('click',function(e){
						$(this).parent().parent().parent()
							.find('.fbd-tabs').removeClass('fbd-active-tab').end()
							.find('.fbd-tabs-panel').hide().end()
							.find( $(this).attr('rel') ).show()
							;
						$(this).parent().addClass('fbd-active-tab');
					}).first().trigger('click');
					//-- all unchecked unload
					$(this).find('.fbd-cell input[type="checkbox"]').attr('checked',false);
					//-- move checked items
					$(this).find('.fbd-cell input[type="checkbox"]').on('click',function(e){
						if( $(this).is(':checked') ){
							var destination = $(this).parent().parent().parent().find('.fbd-checked');
						}else{
							var destination = $(this).parent().parent().parent().find('.fbd-unchecked');
						}
						fbd_move_cell($(this).parent(),destination);
					});
					//-- easier access
					$(this).find('.fullCalendar .fbd-dg-apply,.fullCalendar .fbd-dg-remove').attr('rel',$(this).attr('id'));
					//-- apply filter click
					$(this).find('.fullCalendar .fbd-dg-apply').on('click',function(e){
						var cal_id = '#'+$(this).attr('rel');

						var taxonomies = [];
						$(cal_id+' .fullCalendar').find('.fbd-filter-group').each(function(i,element){		
							var terms=[];
							$(element).find('input[type=checkbox].fbd-filter:checked').each(function(j,inp){
								terms[terms.length]=$(inp).val();
							});
							if(terms.length>0){
								taxonomies[taxonomies.length]={
									'taxonomy':$(this).attr('rel'),
									'terms':terms.join(','),
									'terms_array':terms
								};
							}
						});
						
						var filter = '';
						if(taxonomies){
							$.each(taxonomies,function(i,t){
								filter += '&tax['+t.taxonomy+']=' + t.terms ;
							});
						}
						
						var data = $(cal_id).data('Calendarize');
						var fc_options = data.modes[data.mode].options;
						var fc = $(cal_id + ' .fullCalendar');	
						
						fc_options.events_source_query_original = fc_options.events_source_query_original?fc_options.events_source_query_original:fc_options.events_source_query;
						if(taxonomies){
							fc.fullCalendar('removeEventSources');
							fc_options.events_source_query = fc_options.events_source_query_original + filter;
						}else{
							fc.fullCalendar('removeEventSources');
							fc_options.events_source_query = fc_options.events_source_query_original;
						}

						var new_source = function(start, end, callback) {
							$.fn.Calendarize.events_source(start, end, callback, fc_options);
						};
						fc.fullCalendar('addEventSource',new_source);
						
						if(taxonomies.length==0){	
							if(fc_options.json_feed && fc_options.json_feed.length > 0){
					//			fc.fullCalendar('removeEventSources');
								$.each(fc_options.json_feed,function(i,f){
									fc.fullCalendar('addEventSource',f);
								});			
							}	
						}else{
							var filtered_sources = [];
							$.each(taxonomies,function(i,tax){
								$.each(tax.terms_array,function(j,tax_term){	
									$.each(fc_options.json_feed,function(i,f){
										if( $.inArray(f,filtered_sources) > -1 ) return;
										if(f.terms && f.terms.length>0){
											for(var i=0;i<f.terms.length;i++){					
												if( f.terms[i].taxonomy == tax.taxonomy && f.terms[i].slug == tax_term ){					
													if( -1 == $.inArray(f,filtered_sources) ){
														filtered_sources.push(f);
													}	
													return;				
												}
											}
										}
									});									
								});				
							});
							$.each(filtered_sources,function(i,f){
								fc.fullCalendar('addEventSource',f);
							});					
						}
						
						$(this).parents('.fullCalendar').find('.fc-button-rhc_search').trigger('click');
						
					});
					//-- remove filter click
					$(this).find('.fullCalendar .fbd-dg-remove').on('click',function(e){
						$('#'+$(this).attr('rel'))
							.find('input[type=checkbox].fbd-filter').each(function(i,inp){
								$(inp).attr('checked',false);
								var destination = $(this).parent().parent().parent().find('.fbd-unchecked');
								fbd_move_cell($(this).parent(),destination);
							})/*.attr('checked',false)*/.end()
							.find('.fbd-dg-apply').trigger('click');
							
							$(this).parents('.fullCalendar').find('.fc-button-rhc_search').trigger('click');
					});
					//--
					$(this).find('.fullCalendar .fc-header').on('click',function(e){
						$('.fct-tooltip').trigger('close-tooltip');
					});
					//--
					$(document).keyup(function(e) {
						if (e.keyCode == 27) { 
							$('.fct-tooltip').trigger('close-tooltip'); 	
							$('.fc-filters-dialog:visible').stop()
								.find('.fbd-unchecked').css('overflow-y','hidden').end()
								.animate({opacity:0,top:-10},'fast','linear',function(){$(this).hide();});							
						}
					});										
				}
			});
		},
		mode : function ( mode ){
			var _this = this;
			var data = $(this).data('Calendarize');
			var fc_options = $.extend( data.common, data.modes[mode].options);	
			var regColorcode = /^(#)?([0-9a-fA-F]{3})([0-9a-fA-F]{3})?$/;

			if(fc_options.for_widget){
				fc_options.eventRender = function (event,element,view){					
					var pattern=/fc-day([0-9]{1,2})/i;	
					var day_diff = 0;
					if(event.start&&event.end){
						var s = new Date(event.start);
						var e = new Date(event.end);
						s.setHours(0,0,0,0);
						e.setHours(0,0,0,0);
						day_diff = Math.floor((e.getTime()-s.getTime())/(86400000));
					}
			
					var day_number = event._start.getDate();
					$(view.element).find('.fc-day-number').each(function(i,inp){						
						if( day_number==$(inp).html() ){
							if(  event.start.getMonth() == view.start.getMonth() ){				
								if( !$(inp).parent().parent().hasClass('fc-other-month') ){
									$(inp).parent().parent()
										.addClass('fc-state-highlight')
										.addClass('fc-have-event')
										.css('background-image','none')
									;
									if(day_diff>0){
										var _class = $(inp).parent().parent().attr('class');
										var _arr = _class.match( pattern );
										if( _arr && _arr[1] && _arr[1]>0 ){
											for(a=1;a<=day_diff;a++){
												var fc_day = parseInt(_arr[1])+a;
												$(view.element).find('.fc-day' + fc_day)
													.addClass('fc-state-highlight')
													.addClass('fc-have-event')
													.css('background-image','none')
												;
		
												if($(view.element).find('.fc-day' + fc_day).length==0){
													break;
												}	
											}								
										}			
									}										
								}
							}else{
								if( $(inp).parent().parent().hasClass('fc-other-month') ){
									$(inp).parent().parent()
										.addClass('fc-state-highlight')
										.addClass('fc-have-event')
										.css('background-image','none')
									;	
									if(day_diff>0){
										var _class = $(inp).parent().parent().attr('class');
										var _arr = _class.match( pattern );
										if( _arr && _arr[1] && _arr[1]>0 ){
											for(a=1;a<=day_diff;a++){
												var fc_day = parseInt(_arr[1])+a;
												$(view.element).find('.fc-day' + fc_day)
													.addClass('fc-state-highlight')
													.addClass('fc-have-event')
													.css('background-image','none')
												;
		
												if($(view.element).find('.fc-day' + fc_day).length==0){
													break;
												}	
											}								
										}			
									}										
								}
							}	
						}
					});							
					return false;
				};
				
				fc_options.dayClick = function (date,allDay,jsEvent,view){

					if(fc_options.widget_link){
						if(fc_options.widget_link_view){
							var _view = fc_options.widget_link_view;
						}else{
							var _view = 'agendaDay';
						}
						$('<form method="post" />')
							.attr('action',fc_options.widget_link)
							.append('<input type="hidden" name="gotodate" value="'+ $.fullCalendar.formatDate( date, 'yyyy-MM-dd' ) +'" />')
							.append('<input type="hidden" name="defaultview" value="'+ _view +'" />')
							.append('<input type="hidden" name="fcalendar" value="'+ (fc_options.ev_calendar?fc_options.ev_calendar:'') +'" />')
							.append('<input type="hidden" name="fvenue" value="'+ (fc_options.ev_venue?fc_options.ev_venue:'') +'" />')
							.append('<input type="hidden" name="forganizer" value="'+ (fc_options.ev_organizer?fc_options.ev_organizer:'') +'" />')
							
							.appendTo(_this)
							.submit()
						;
					}
				}	
				fc_options.loading = function( isLoading, view ){
					if(isLoading){
						$(_this).find('.fc-have-event').each(function(i,inp){
							$(this)
								.removeClass('fc-state-highlight')
								.removeClass('fc-have-event')
								.css('background-image','')
							;
						});
					}
				}				
			}else{
				fc_options.eventRender = function (event,element,view){	
					$('.fc-event-title', element).html(event.title);
				}
				fc_options.loading = function( isLoading, view ){			
					if( 'undefined'==typeof(fc_options.loadingOverlay)||'1'!=fc_options.loadingOverlay)return;
					if(isLoading){
					//--placeholder for a loading overlay
						if( 0==$(_this).find('.fc-content .fc-view-loading').length  ){
							$(_this).find('.fullCalendar .fc-content').prepend(
								$('<div class="fc-view-loading"></div>')
									.hide()
									.append('<div class="fc-view-loading-1 ajax-loader"><div class="fc-view-loading-2"></div></div>')
							);							
						}
					
						$(_this)
							.find('.fc-view-loading')
							.addClass('loading-events')
							.find('.ajax-loader').addClass('loading-events').end()
							.stop()
							.fadeIn()
						;				
					}else{
						$(_this).find('.fc-view-loading').stop().fadeOut('fast',function(){
							$(_this).find().remove('.fc-view-loading');
						});
					}
				}					
			}
			/*
			fc_options.events = function(start, end, callback) {
		        $.fn.Calendarize.events_source(start, end, callback, fc_options);
		    };
			*/
			var rhc_event_src = function(start, end, callback) {
		        $.fn.Calendarize.events_source(start, end, callback, fc_options);
		    };
			
			fc_options.eventSources = [];
			if(fc_options.json_only!='1'){
				fc_options.eventSources.push(rhc_event_src);
			}		
			if( fc_options.json_feed && fc_options.json_feed.length>0 ){
				if(fc_options.json_only=='1'){
					fc_options.events = null;
					fc_options.singleSource = null;				
				}
				fc_options.eventSources = fc_options.eventSources.concat(fc_options.json_feed);
			}			
		
			f = $(this).find('.fullCalendar').fullCalendar( fc_options );
			if(data.editable && f.find('.fc-edit-tools').length==0 ){
				f.prepend('<div class="fc-edit-tools"></div>');
			}	
			
			if( f.find('.fc-footer').length==0 ){
				f.append('<div class="fc-footer"></div>');
				if(fc_options.icalendar_align){
					$('.fc-footer')
						.css('text-align',fc_options.icalendar_align)
						.addClass('dlg-align-'+fc_options.icalendar_align)
					;
				}
			}
			
			if(true){
//-----------------------------------
				if( $( ".ical-tooltip-template" ).length>0 ){
						var e = f.find('.fc-footer');
						var calendar = f;
						var text = $( ".ical-tooltip-template" ).first().attr('rel');
						var tm = fc_options.theme ? 'ui' : 'fc';
						var buttonName = 'icalendar';
						
						var buttonClick = function(f,btn,e) {
							if( $(btn).parent().find('.ical-tooltip').length>0 ){
								$(btn).parent().find('.ical-tooltip').remove();
							}else{
								var data = $(_this).data('Calendarize');
								var options = data.modes[data.mode].options;
								var url = options.events_source + options.events_source_query;
								url = url.replace('get_calendar_events','get_icalendar_events');
								var url2 = url + '&ics=1';
								
								var tooltip = $('.ical-tooltip-template').first().clone();
								tooltip
									.removeClass('ical-tooltip-template')
									.addClass('ical-tooltip')
									.find('.ical-url').html(url).end()
									.find('.ical-clip')
										.attr('href',url)
										.on('click',function(e){
								            $(this).focus();
								            $(this).select();
											return false;
			
										})
										.end()
									.find('.ical-ics').attr('href',url2).end()
								;						
								$(btn).after( tooltip );
								
								tooltip.fadeIn('fast',function(e){
									tooltip
										.find('textarea.ical-url')
										.focus()
										.select()
									;
								});
							}					
							//----------------------------------------------------------------
						};
						
						if (buttonClick) {
							var button = $(
								"<span class='fc-button fc-button-" + buttonName + " " + tm + "-state-default '>" +
									"<span class='fc-button-inner'>" +
										"<span class='fc-button-content'>" + 
										text +
										"</span>" +
										"<span class='fc-button-effect'><span></span></span>" +
									"</span>" +
								"</span>" 
							);
							if (button) {
								button
									.click(function(e) {
										if (!button.hasClass(tm + '-state-disabled')) {
											buttonClick(f,this,e);
										}
									})
									.mousedown(function() {
										button
											.not('.' + tm + '-state-active')
											.not('.' + tm + '-state-disabled')
											.addClass(tm + '-state-down');
									})
									.mouseup(function() {
										button.removeClass(tm + '-state-down');
									})
									.hover(
										function() {
											button
												.not('.' + tm + '-state-active')
												.not('.' + tm + '-state-disabled')
												.addClass(tm + '-state-hover');
										},
										function() {
											button
												.removeClass(tm + '-state-hover')
												.removeClass(tm + '-state-down');
										}
									)
									.appendTo(e);
								
								button.addClass(tm + '-corner-left');
								button.addClass(tm + '-corner-right');
							}
						}
					
				}			
//-----------------------------------			
			}
			
			if(fc_options.gotodate && fc_options.gotodate!=''){
				 $(this).find('.fullCalendar').fullCalendar('gotoDate', $.fullCalendar.parseDate(fc_options.gotodate) );
			}
		},
		destroy : function(){
			return this.each(function(){
				var $this = $(this),
				    data = $this.data('Calendarize');
				$(window).unbind('.Calendarize');
				data.Calendarize.remove();
				$this.removeData('Calendarize');
			});
		}
	};
	
	$.fn.Calendarize = function( method ) {
		if ( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.Calendarize' );
		}    
	};
	
	var rhc_events_cache = [];
	
	$.fn.Calendarize.events_source = function( start, end, callback, fc_options ){			
		jQuery(document).ready(function($){
			var data = [];
			$('.calendarize_meta_data').each(function(i,inp){
				if(inp.type=='checkbox'){
					data[data.length] = [inp.name,($(inp).is(':checked')?1:0)];
				}else{
					data[data.length] = [inp.name,($(inp).val())];
				}
			});
			
			var url = fc_options.events_source + fc_options.events_source_query;
			
			var use_cache = true;
			var now = new Date();
			var args = {
				start:		Math.round(start.getTime() / 1000),
				end:		Math.round(end.getTime() / 1000),
				'_': now.getTime(),
				'data[]': 	data
			};
	
			for(var a=0;a<rhc_events_cache.length;a++){
				if(
					use_cache &&
					rhc_events_cache[a].start == args.start &&
					rhc_events_cache[a].end	== args.end &&
					rhc_events_cache[a].url == url
				){			
					callback(rhc_events_cache[a].events);
					return;
				}
			}		
				
			var cache = args;
			cache.url = url;				
			$.post(url,args,function(data){
				if(data.R=='OK'){
					var events = [];
					if(data.EVENTS.length>0){
						$(data.EVENTS).each(function(i,e){
							if('undefined'==typeof(e.start) || null==e.start)return;
							e.src_start = e.start;
							e.fc_rrule = e.fc_rrule?e.fc_rrule:'';
							if(''==e.fc_rrule && ''==e.fc_rdate){
								events[events.length]=e;
							}else{						
								var duration = false;
								if(e.end){
									var e_start = new Date( $.fullCalendar.parseDate( e.start ) );
									var e_end = new Date( $.fullCalendar.parseDate( e.end ) );
									duration = e_end.getTime() - e_start.getTime();
								}	
//								var fc_start = new Date(e.fc_start+' '+e.fc_start_time);
//								var fc_start = new Date(e.start);
								var fc_start = $.fullCalendar.parseDate( e.start );
								e.fc_rrule = ''==e.fc_rrule?'FREQ=DAILY;INTERVAL=1;COUNT=1':e.fc_rrule;
								scheduler = new Scheduler(fc_start, e.fc_rrule, true);
								if(e.fc_interval!='' && e.fc_exdate){
									//handle exception dates
									var fc_exdate_arr = exdate_to_array_of_dates(e.fc_exdate);
									if(fc_exdate_arr.length>0)
										scheduler.add_exception_dates(fc_exdate_arr);
								}	
								if(e.fc_rdate && e.fc_rdate!=''){
									//handle rdates
									var fc_rdate_arr = exdate_to_array_of_dates(e.fc_rdate);
									if(fc_rdate_arr.length>0)
										scheduler.add_rdates(fc_rdate_arr);
								}
																						
								occurrences = scheduler.occurrences_between(start, end);
								if(occurrences.length>0){
									$(occurrences).each(function(i,o){
										var new_start = new Date(o);
										var p = $.extend(true, {}, e);
										p._start 	= new_start;
										p.start 	= new_start;
										p.fc_start 	= $.fullCalendar.formatDate(new_start,'yyyy-MM-dd');
										p.fc_start_time = $.fullCalendar.formatDate(new_start,'HH:mm:ss');
										p.fc_date_time 	= $.fullCalendar.formatDate(new_start,'yyyy-MM-dd HH:mm:ss');
										if(duration){
											var end_time = new_start.getTime() + duration;
											var new_end = new Date();
											new_end.setTime(end_time);
											p._end = new_end;
											p.end = new_end;
											p.fc_end = $.fullCalendar.formatDate(new_end,'yyyy-MM-dd');
											p.fc_end_time = $.fullCalendar.formatDate(new_end,'HH:mm:ss');
										}else{
											p.end = p.start;
											p._end = p._start;
										}
										p.repeat_instance = true;
										p = _add_repeat_instance_data_to_event(p);
										events[events.length]=p;
									});
								}else{

								}
								//handle a situation, where there is no recurring instance in the date range (start / end) but the event was set
								//with long diference between start and end so the event doesnt actually starts or ends in the given time window.
								//this applies both with occurence.length=0 or >0.
								if( e_start < start && e_end > start ){
									e.start = e_start;
									e.end = e_end;
									events[events.length]=e;							
								}								
							}
						});
					}		
					cache.events = events;
					rhc_events_cache[rhc_events_cache.length]=cache;				
					callback(events);
				}else if(data.R=='ERR'){
					//alert(data.MSG);
				}else{
					//alert('Unexpected error');
				}
			},'json');
		});
	}
})(jQuery);


(function($){
	var methods = {
		init : function( options ){
			var settings = $.extend( {
				'draggable':true
			}, options);		

			return this.each(function(){
				var data = $(this).data('CalendarizeDialog');
				if(!data){
					$(this).data('CalendarizeDialog',settings);
					if(settings.draggable){$(this).draggable({handle:'.ui-widget-header'});}
					$(this).find('.ui-dialog-titlebar-close').on('click',function(e){$(this).parent().parent().parent().CalendarizeDialog('close');});			
				}
				$(this).hide();
			});
		},
		open : function ( o ){
			$(this)
				.show()
				.css('margin-left',0)
				.offset( o.offset )
				.css('margin-left', o.margin_left )
			;
		},
		close : function (){
			$(this).hide();
		}
	};
	$.fn.CalendarizeDialog = function( method ) {
		if ( methods[method] ) {
			return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
		} else if ( typeof method === 'object' || ! method ) {
			return methods.init.apply( this, arguments );
		} else {
			$.error( 'Method ' +  method + ' does not exist on jQuery.CalendarizeDialog' );
		}    
	};
})(jQuery);

function fc_mouseover(calEvent, jsEvent, view){

}

function fc_event_details(calEvent, jsEvent, view){
	calEvent.id = calEvent.id.replace('@','_');//google cal.
	calEvent.id = calEvent.id.replace('.','_');//google cal.
	if(calEvent.gcal){
		calEvent.description = calEvent.description.replace(/\n/g, '<br />');
	}
	jQuery(document).ready(function($){
		var tooltip_target = view.calendar.options.tooltip.target||'_self';
		view.calendar.rhc_search(view.calendar,jsEvent,true);	
		var id = 'fct-'+calEvent.id;
		if( $('BODY').find('#'+id).length>0 ){
			$('BODY').find('#'+id).remove();
		}
	
		if( $('BODY').find('#'+id).length==0 ){
			$('BODY').find('#fct-item-template').clone()
				.attr('id',id)
				.addClass('fct-tooltip')
				.bind('close-tooltip',function(e){
					$(this).animate({opacity:0},'fast','swing',function(e){$(this).remove();});
				})
				.find('.fc-close-tooltip a').on('click',function(e){
					$('.fct-tooltip').trigger('close-tooltip');
				}).end()
				.appendTo('BODY');
		}
	
		if( $('BODY').find('#'+id).length>0 ){
			var pos = $(jsEvent.target).offset();
			var view_pos= view.element.offset();
			
			var tip_left = pos.left<(view_pos.left + view.element.width()/2)?true:false;
			var tip_pos = tip_left?'fc-tip-left':'fc-tip-right';
			
			$('.fct-tooltip:not(#'+id+')').trigger('close-tooltip');
		
			var tooltip = $('BODY').find('#'+id);		
			tooltip
				.stop()
				.addClass(tip_pos)
				.find('.fc-description').html(calEvent.description).end()
				.css('opacity',0)
				.show()
			;
			
			if(calEvent.url){
				var url = calEvent.url;
				if(calEvent.fc_rrule && ''!=calEvent.fc_rrule){			
					/*
					var start = new Date(calEvent.start);			
					var start_seconds = parseInt(start.getTime() / 1000);	
					url = _add_param_to_url(url,'event_start',start_seconds);
					*/
				}
				
				var title_is_link = !(view.calendar.options.tooltip&&view.calendar.options.tooltip.disableTitleLink&&view.calendar.options.tooltip.disableTitleLink=='1');			
				if( !title_is_link || calEvent.gcal ){
					tooltip.find('.fc-title').html( calEvent.title );
				}else{
					if(calEvent.direct_link){
						//fb doesnt likes that you post
						$('<a></a>')
							.attr('href', url )					
							.html( calEvent.title )
							.attr('target',tooltip_target)
							.appendTo( tooltip.find('.fc-title') )
						;	
					}else{
						$('<a></a>')
							//.attr('href', url )
							.attr('href','javascript:void(0);')	
							.bind('click',function(e){
								jQuery('form#calendarizeit_repeat_instance').remove();
								var form = '<form id="calendarizeit_repeat_instance" method="post"></form>';
								jQuery(form)
									.attr('action',calEvent.url)
									.appendTo('BODY')	
								;
								if(calEvent.gotodate){
									jQuery('<input type="hidden" name="gotodate" value="' + calEvent.gotodate + '"/>')
										.appendTo('form#calendarizeit_repeat_instance')
									;
								}
								if(calEvent.event_rdate){
									jQuery('<input type="hidden" name="event_rdate" value="' + calEvent.event_rdate + '" />')
										.appendTo('form#calendarizeit_repeat_instance')
									;
								}
								jQuery('form#calendarizeit_repeat_instance').submit();	
							})						
							.html( calEvent.title )
							.attr('target',tooltip_target)
							.appendTo( tooltip.find('.fc-title') )
						;						
					}		
				
				}

				if(calEvent.image && calEvent.image[0]){
					$('<a></a>')
						.attr('href', url )
						.attr('target',tooltip_target)
						.append(
							$('<img />').attr('src', calEvent.image[0])
						)
						.appendTo( tooltip.find('.fc-image') )
					;
				}	
			}else{
				tooltip.find('.fc-title').html(calEvent.title);
				
				if(calEvent.image && calEvent.image[0]){
					$('<img />')
						.attr('src', calEvent.image[0])
						.appendTo( tooltip.find('.fc-image') )
					;
				}				
			}
			
			tooltip.find('.fc-start,.fc-end,.fc-hide').hide();
	
			if(calEvent.allDay){
				if(calEvent.start){
					tooltip.find('.fc-start').append(
						$('<span></span>').html( $.fullCalendar.formatDate( calEvent.start, view.calendar.options.tooltip.startDateAllDay, view.calendar.options ) )
					 ).show();
				}
				if(calEvent.end){
					tooltip.find('.fc-end').append(
						$('<span></span>').html( $.fullCalendar.formatDate( calEvent.end, view.calendar.options.tooltip.endDateAllDay||view.calendar.options.tooltip.startDateAllDay, view.calendar.options ) )
					 ).show();
				}					
			}else{
				if(calEvent.start){
					tooltip.find('.fc-start').append(
						$('<span></span>').html( $.fullCalendar.formatDate( calEvent.start, view.calendar.options.tooltip.startDate, view.calendar.options ) )
					 ).show();
				}
				if(calEvent.end){
					tooltip.find('.fc-end').append(
						$('<span></span>').html( $.fullCalendar.formatDate( calEvent.end, view.calendar.options.tooltip.endDate||view.calendar.options.tooltip.startDate, view.calendar.options ) )
					 ).show();
				}			
			}
			
			if(calEvent.terms && calEvent.terms.length>0){
				$.each(calEvent.terms,function(i,term){
					if(term.gaddress){
						var sel = '.fc-term-' + term.taxonomy + '-gaddress';
						if( tooltip.find(sel).find('a').length>0 ){
							tooltip.find(sel).append( '<span class="rhc-tooltip tax-term-divider"></span>' );
						}
						$('<a></a>')
							.attr('href', 'http://www.google.com/maps?f=q&hl=en&source=embed&q='+escape(term.gaddress) )
							.html( term.gaddress )
							.attr('target','_blank')
							.appendTo( tooltip.find(sel).show() )
						;			
					}
					
					if( tooltip.find('.fc-tax-' + term.taxonomy).length>0 ){
						if(term.name==''){			
							tooltip.find('.fc-tax-' + term.taxonomy).hide();
						}else{
							if( tooltip.find('.fc-tax-' + term.taxonomy).find('a').length>0 ){
								tooltip.find('.fc-tax-' + term.taxonomy).append( '<span class="rhc-tooltip tax-term-divider"></span>' );
							}
							
							if(term.url && term.url!=''){
								$('<a></a>')
									.attr('href', term.url )
									.html( term.name )
									.attr('target',tooltip_target)
									.appendTo( tooltip.find('.fc-tax-' + term.taxonomy) )
								;							
							}else{
								$('<span></span>')
									.html( term.name )
									.appendTo( tooltip.find('.fc-tax-' + term.taxonomy) )
								;	
							}
							
							
							tooltip.find('.fc-tax-' + term.taxonomy)
								.find('.tax-label').html( term.taxonomy_label ).end()
								.show()
							;							
						}
						
					}
				});
			}

			pos.top = pos.top - tooltip.height()/2 + ($(jsEvent.srcElement).height()/2);
			//---adjust tooltip top
			var cal_offset = view.element.offset();		
			var diff = cal_offset.top-pos.top - 5;
			if(diff>0){
				pos.top = pos.top+diff;		
				tooltip.find('.fct-arrow-holder').css('margin-top', diff*-1);			
			}
		
			if( tip_left ){
				pos.left = pos.left + $(jsEvent.target).width();
			}else{
				pos.left = pos.left + tooltip.width()*(-1);
			}
			
			if(view.name=='agendaDay'){
				pos.left = pos.left - tooltip.width() + 50;
			}
			
			tooltip
				.css('min-height', tooltip.height())
				.css('height','auto')
				.offset(pos)
				.animate({opacity:1},'fast','swing')
			;				
		}
	});
}

function no_link(calEvent, jsEvent, view){
	jsEvent.stopPropagation();
	return false;
}

function fc_click(calEvent, jsEvent, view){		
	var click_link = !calEvent.fc_click_link?'view':calEvent.fc_click_link;
	if(view&&view.name=='rhc_event'&&click_link=='view')click_link='page';//event list with tooltip is redundant.
	if(click_link=='none'){
		return false;
	}
	if('undefined'==typeof calEvent.fc_click_target){
		calEvent.fc_click_target = '_self';
	}	
	if(calEvent.url && click_link=='page' ){
		if(calEvent.fc_click_target && calEvent.fc_click_target!=''){
			//if(calEvent.event_rdate || calEvent.gotodate){
			if(true){
				jQuery('form#calendarizeit_repeat_instance').remove();
				var form = '<form id="calendarizeit_repeat_instance" method="post" target="' + calEvent.fc_click_target + '"></form>';
				jQuery(form)
					.attr('action',calEvent.url)
					.appendTo('BODY')	
				;
				if(calEvent.gotodate){
					jQuery('<input type="hidden" name="gotodate" value="' + calEvent.gotodate + '"/>')
						.appendTo('form#calendarizeit_repeat_instance')
					;
				}
				if(calEvent.event_rdate){
					jQuery('<input type="hidden" name="event_rdate" value="' + calEvent.event_rdate + '" />')
						.appendTo('form#calendarizeit_repeat_instance')
					;
				}
				
				jQuery('form#calendarizeit_repeat_instance').submit();	
			}
			return false;
		}else{
			return true;
		}
	}else{
		fc_event_details(calEvent, jsEvent, view);
		return false;
	}
}

function fc_select(startDate, endDate, allDay, jsEvent, view){
	jQuery(document).ready(function($){
		var offset = $(jsEvent.target).offset();
		var margin_left = $(jsEvent.target).width();
		$('.fc-dialog')
			.CalendarizeDialog('open',{offset:offset,margin_left:margin_left});
		
	});
}

function _add_param_to_url(url, param, paramVal){
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var aditionalURL = tempArray[1]; 
    var temp = "";
    if(aditionalURL){
        var tempArray = aditionalURL.split("&");
        for ( i=0; i<tempArray.length; i++ ){
            if( tempArray[i].split('=')[0] != param ){
                newAdditionalURL += temp+tempArray[i];
                temp = "&";
            }
        }
    }
    var rows_txt = temp+""+param+"="+paramVal;
    return baseURL+"?"+newAdditionalURL+rows_txt;
}

function _add_repeat_instance_data_to_url(e){
	if(e.repeat_instance){
		if( (e.fc_rrule && ''!=e.fc_rrule)||(e.fc_rdate && ''!=e.fc_rdate) ){
			if(e.using_calendar_url){
				var period = jQuery.fullCalendar.formatDate(  e.start, "yyyy-MM-dd" );
				if( period && ''!=period ){
					e.url = _add_param_to_url(e.url,'gotodate',period);
				}			
			}else{
			
				if(e.src_start && e.fc_date_time && e.src_start==e.fc_date_time){
					
				}else{
					var period = jQuery.fullCalendar.formatDate(  e.start, "yyyyMMddHHmmss" );
					var end = jQuery.fullCalendar.formatDate(  e.end, "yyyyMMddHHmmss" );
					if( period && ''!=period ){
						if(end && ''!=end){
							period = period + ',' + end;
						}
						e.url = _add_param_to_url(e.url,'event_rdate',period);
					}					
				}			
			}
		}	
	}
	return e;
}

function _add_repeat_instance_data_to_event(e){
	if(e.repeat_instance){
		if( (e.fc_rrule && ''!=e.fc_rrule)||(e.fc_rdate && ''!=e.fc_rdate) ){
			if(e.using_calendar_url){
				var period = jQuery.fullCalendar.formatDate(  e.start, "yyyy-MM-dd" );
				if( period && ''!=period ){
					e.gotodate = period;
				}			
			}else{
			
				if(e.src_start && e.fc_date_time && e.src_start==e.fc_date_time){
					
				}else{
					var period = jQuery.fullCalendar.formatDate(  e.start, "yyyyMMddHHmmss" );
					var end = jQuery.fullCalendar.formatDate(  e.end, "yyyyMMddHHmmss" );
					if( period && ''!=period ){
						if(end && ''!=end){
							period = period + ',' + end;
						}
						e.event_rdate = period;
					}					
				}			
			}
		}	
	}
	return e;
}

function exdate_to_array_of_dates(fc_exdate){
	var fc_exdate_arr = fc_exdate==''?[]:fc_exdate.split(',');
	if( fc_exdate_arr.length>0 ){
		var array_of_dates = [];
		for(a=0;a<fc_exdate_arr.length;a++){
			var _exdate = fc_exdate_arr[a]; 
			array_of_dates[array_of_dates.length] = new Date( _exdate.substring(0,4), _exdate.substring(4,6)-1, _exdate.substring(6,8), _exdate.substring(9,11), _exdate.substring(11,13), _exdate.substring(13,15) );
		}
		return array_of_dates;
	}else{
		return [];
	}		
}

jQuery(document).ready(function($){
	if( $('.rhc-ical-feed-cont').length>0 ){
		$('.rhc-ical-feed-cont').each(function(i,o){
			var me = this;
			var e = $(this).parent();
			var text = $(this).attr('rel');
			var tm = $(this).attr('data-theme');
			var buttonName = 'icalendar';
			var icalendar_title = $(this).attr('data-title');
			
			var buttonClick = function(me) {
				var url = 'javascript:alert(1);';
				var _width = parseInt($(me).css('width'));	
				_width = _width==0?450:_width;
				var title = icalendar_title ;
				$( me )
					.dialog({
						height: 'auto',
						width:_width,
						modal: true,
						draggable: false,//todo:draggable breaks layout
						resizable: false,
						open: function(event,ui){
							$('body').addClass('rhcalendar');								
							if(tm=='fc'){
								$(this).parent()
									.addClass('fbd-main-holder')
									.removeClass('ui-widget-content')
									.removeClass('ui-corner-all')
									.removeClass('ui-widget')
									.find('.ui-dialog-titlebar')
									.removeClass('ui-widget-header')
									.removeClass('ui-corner-all')
									.addClass('fbd-head')
									.end()
									.find('.ui-dialog-content')
									.addClass('fbd-body')
									.removeClass('ui-corner-all')
									.removeClass('ui-widget-content')
								;										
							}
							$(this).parent()
								.hide()
								.css('opacity',0)
								.show()
								.animate({opacity:1})
							;
						},
						close: function(event,ui){
							$('body').removeClass('rhcalendar');									
						},
						create: function (event,ui){
							$(this).parent().addClass('rhc-icalendar-holder');
							$(this).parent().addClass(tm+'-theme');
						}
					})
				;

			};
			
			if (buttonClick) {
				var button = $(
					"<span class='fc-button fc-button-" + buttonName + " " + tm + "-state-default '>" +
						"<span class='fc-button-inner'>" +
							"<span class='fc-button-content'>" + 
							text +
							"</span>" +
							"<span class='fc-button-effect'><span></span></span>" +
						"</span>" +
					"</span>"
				);
				if (button) {
					button
						.click(function(e) {
							if (!button.hasClass(tm + '-state-disabled')) {
								buttonClick(me);
							}
						})
						.mousedown(function() {
							button
								.not('.' + tm + '-state-active')
								.not('.' + tm + '-state-disabled')
								.addClass(tm + '-state-down');
						})
						.mouseup(function() {
							button.removeClass(tm + '-state-down');
						})
						.hover(
							function() {
								button
									.not('.' + tm + '-state-active')
									.not('.' + tm + '-state-disabled')
									.addClass(tm + '-state-hover');
							},
							function() {
								button
									.removeClass(tm + '-state-hover')
									.removeClass(tm + '-state-down');
							}
						)
						.appendTo(e);
					
					button.addClass(tm + '-corner-left');
					button.addClass(tm + '-corner-right');
				}
			}		
		});
	}	
	
	//---- initialize any calendars
	$('.rhc_holder').each(function(i,el){
		var ui_theme = $(el).data('rhc_ui_theme');
		if(''!=ui_theme){
			$("#fullcalendar-theme-css").attr("href",ui_theme);
		}
		var rhc_options = $(el).data('rhc_options');
		eval( '$(el).Calendarize('+rhc_options+')' ); 
	});		
	
	$('.fc-button-custom').hover(function(){
		$(this).addClass('fc-state-hover');
	},function(){
		$(this).removeClass('fc-state-hover');
	});
});

function get_event_ocurrences(e){
	var fc_start = jQuery.fullCalendar.parseDate( e.start );
	e.fc_rrule = ''==e.fc_rrule?'FREQ=DAILY;INTERVAL=1;COUNT=1':e.fc_rrule;
	scheduler = new Scheduler(fc_start, e.fc_rrule, true);
	if(e.fc_interval!='' && e.fc_exdate){
		//handle exception dates
		var fc_exdate_arr = exdate_to_array_of_dates(e.fc_exdate);
		if(fc_exdate_arr.length>0)
			scheduler.add_exception_dates(fc_exdate_arr);
	}	
	if(e.fc_rdate && e.fc_rdate!=''){
		//handle rdates
		var fc_rdate_arr = exdate_to_array_of_dates(e.fc_rdate);
		if(fc_rdate_arr.length>0)
			scheduler.add_rdates(fc_rdate_arr);
	}
															
	occurrences = scheduler.occurrences_between(start, end);
}