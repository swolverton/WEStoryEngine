//custom fullCalendar views created for calendarize-it.

(function($){
$.fullCalendar.views.rhc_event = EventView;	
function EventView(element, calendar) {
	var t = this;
	var body;
	t.name = 'rhc_event';
	t.render = render;
	t.unselect = unselect;
	t.setHeight = setHeight;
	t.setWidth = setWidth;
	t.clearEvents = clearEvents;
	t.renderEvents = renderEvents;
	t.trigger = trigger;
	t.viewChanged = viewChanged;
	t.beforeAnimation = beforeAnimation;
	
	t.element = element;
	t.oldView = null;
	//not part of fc api.
	t.calendar = calendar;//needed for clicking event title.
	function viewChanged(oldView){
		if(oldView){
			if( oldView.visStart && oldView.visEnd ){
				t.title = oldView.title;
				//t.visStart = oldView.visStart;
				t.visStart = oldView.start;
				t.visEnd = oldView.visEnd;
				t.oldView = oldView;

				if( calendar.options.eventList.upcoming && calendar.options.eventList.upcoming=='1' ){
					var _now = new Date();
					t.visStart = t.visStart.getTime() > _now.getTime() ? t.visStart : _now ;
				}		
				
				var months = calendar.options.eventList.monthsahead?calendar.options.eventList.monthsahead:'';
				months = months.replace(' ','')==''?1:parseInt(months);	
				if( months>0 ){
					var _visEnd = new Date( t.visStart );
					_visEnd.setMonth( _visEnd.getMonth() + months );
					t.visEnd = _visEnd;		
				}			
			}	
		}
	}
	
	//not part of fc api.
	function beforeAnimation(oldView){
		
	}
	
	function render(date,delta){
		t.start = date;//if not defined, hidden views do not update size on window resize.
		var firstTime = !body;
		if(firstTime){
			$('<div class="fc-events-holder">...</div>').appendTo(element);
			body = true;
		}else{
			 
		}
		
		if(t.oldView){
		
		}else{
			t.oldView = new $.fullCalendar.views['month']( $('<div>') ,calendar);
			calendar.gotoDate(date);
		}
		
		if(t.oldView){	
			t.oldView.render(date,delta);
			if( t.oldView.visStart && t.oldView.visEnd ){
				t.title = t.oldView.title;
				t.start = t.oldView.start;
				t.end = t.oldView.end;
				//t.visStart = t.oldView.visStart;
				t.visStart = t.oldView.start;
				t.visEnd = t.oldView.visEnd;
				
			
			}		
		}

		if( calendar.options.eventList.upcoming && calendar.options.eventList.upcoming=='1' ){
			var _now = new Date();
			t.visStart = t.visStart.getTime() > _now.getTime() ? t.visStart : _now ;
		}
		
		var months = calendar.options.eventList.monthsahead?calendar.options.eventList.monthsahead:'';
		months = months.replace(' ','')==''?1:parseInt(months);	

		if( months>0 ){
			var _visEnd = new Date( t.visStart );
			_visEnd.setMonth( _visEnd.getMonth() + months );
			t.visEnd = _visEnd;		
		}
	}
	function unselect(){

	}
	function setHeight(h){
		//element.css('min-height',h);
		element.css('min-height','200px');
		element.css('height','auto');
	}
	function setWidth(){/*console.log('setWidth');*/}
	function clearEvents(){
		element.find('.fc-events-holder').empty();
	}
	function renderEvents(_events, modifiedEventId){
		var view_template = $(rhc_event_tpl);
		var item_template = view_template.find('.fc-event-list-item').clone().removeClass('fc-remove');
		var date_template = view_template.find('.fc-event-list-date').clone().removeClass('fc-remove');
		var no_events_template = view_template.find('.fc-event-list-no-events').clone().removeClass('fc-remove');
		if(calendar.options.eventList && calendar.options.eventList.eventListNoEventsText){
			no_events_template.find('.fc-no-list-events-message').html(calendar.options.eventList.eventListNoEventsText);
		}
		
		view_template
			.appendTo( element.find('.fc-events-holder') )
			.find('.fc-remove').remove();
			
		if(_events.length>0){
			events = [];
			var now = new Date();
			$.each(_events,function(i,ev){
//console.log( calendar.options.eventList.outofrange , calendar.options.eventList);			
				if(calendar.options.eventList && calendar.options.eventList.outofrange=='1'){
				
				}else{
					if(ev.start<t.visStart)return;
					if(ev.start>t.visEnd)return;				
				}

				//--past event
//				if( (ev.end&&ev.end<now)||(ev.start<now) )return;
				
				events[events.length]=ev;
			});
			if(events.length==0)return;
			//---
			if( '1'==calendar.options.eventList.reverse ){
				events.sort(_rsort_events);
			}else{
				events.sort(_sort_events);
			}
			
			var last_date = '';			
			$.each(events,function(i,ev){
				if( 'undefined'!=typeof(calendar.options.eventList.display) && calendar.options.eventList.display>0 ){
					if(i>=calendar.options.eventList.display)return;
				}
				
				var item = item_template.clone();

				if(ev.gcal || ev.url==''){
					item
						.find('.fc-event-list-title').parent()
						.empty()
						.append( $('<span></span>').addClass('fc-event-list-title').html(ev.title) )
					;
				}else if(ev.direct_link){
					item
						.find('.fc-event-list-title').html(ev.title).end()
						.find('a.fc-event-link')
							.attr('href',ev.url)	
							.end()	
					;
				}else{
					item
						.find('.fc-event-list-title').html(ev.title).end()
						.find('a.fc-event-link')
							.attr('target','')
							.attr('href','javascript:void(0);')	
							.bind('click',function(e){
								var click_method = calendar.options.eventClick?calendar.options.eventClick:fc_click;
								click_method(ev,e,t);
							})
							.end()	
					;
				}

				item
					.find('.fc-event-list-description').html(ev.description).end()
				;

				if( ''==ev.description.replace(' ','') ){
					item.find('.fc-event-list-description').addClass('rhc-empty-description');
				}
				
				if(ev.fc_click_link=='none'){
					item.find('a.fc-event-link').addClass('fc-no-link');
				}
				
				//--thumbnail
				if(ev.image&&ev.image[0]){
					item.find('img.fc-event-list-image').attr('src',ev.image[0]);
				}else{
					item.find('.fc-event-list-featured-image').empty();
				}	
				//--hour
				if(ev.allDay){
					item.find('.fc-time').remove();
					var _start_date_format = calendar.options.eventList.StartDateFormatAllDay||'dddd MMMM d, yyyy.';
				}else{
					item.find('.fc-time').html( $.fullCalendar.formatDate(ev.start,'h:mmtt') );
					var _start_date_format = calendar.options.eventList.StartDateFormat||'dddd MMMM d, yyyy. h:mmtt';
				}
			
				//--start
				if(ev.start){
					item.find('.fc-start').html( $.fullCalendar.formatDate(ev.start,_start_date_format,calendar.options) );
				}else{
					item.find('.fc-start').remove();
				}
				//--end
				if(ev.end){
					item.find('.fc-end').html( $.fullCalendar.formatDate(ev.end,_start_date_format,calendar.options) );
				}else{
					item.find('.fc-end')
						.parent().addClass('rhc_event-empty-taxonomy').end()
						.remove()
						
					;
				}
				//--terms
				item.find('.fc-event-term')
					.empty().hide()
					.parent().addClass('rhc_event-empty-taxonomy')
				;
				if(ev.terms && ev.terms.length>0){
					$.each(ev.terms,function(i,t){		
						if( item.find('.taxonomy-'+t.taxonomy).parent().find('a').length>0 ){
							item.find('.taxonomy-'+t.taxonomy).parent().append( '<span class="rhc-event-list tax-term-divider"></span>' );
						}
												
						if( t.name && ''!=t.name && item.find('.taxonomy-'+t.taxonomy).length>0 ){
							if( t.url=='' ){
								$('<span>'+ t.name +'</span>')
									.appendTo( item.find('.taxonomy-'+t.taxonomy).show().parent().removeClass('rhc_event-empty-taxonomy') )
								;	
							}else{
								$('<a>'+ t.name +'</a>')
									.attr('href',t.url)
									.appendTo( item.find('.taxonomy-'+t.taxonomy).show().parent().removeClass('rhc_event-empty-taxonomy') )
								;								
							}
	
						}
						
						if( item.find('.taxonomy-'+t.taxonomy+'-gaddress').length>0 && t.gaddress && t.gaddress!=''){
							if( item.find('.taxonomy-'+t.taxonomy+'-gaddress' ).parent().find('a').length>0 ){
								item.find('.taxonomy-'+t.taxonomy+'-gaddress' ).parent().append( '<span class="rhc-event-list tax-term-divider"></span>' );
							}							
							
							var _url = 'http://www.google.com/maps?f=q&hl=en&source=embed&q=' + escape(t.gaddress);
							$('<a>'+ t.gaddress +'</a>')
								.attr('href', _url)
								.attr('target','_blank')
								.appendTo( item.find('.taxonomy-'+t.taxonomy+'-gaddress' ).show().parent().removeClass('rhc_event-empty-taxonomy').end() )
							;	
						}
					});
				}
				
				if( calendar.options.eventList.ShowHeader && parseInt(calendar.options.eventList.ShowHeader)==1){
					var header_date = ev.start;
					if($.fullCalendar.formatDate(header_date,'yyyyMMdd')!=$.fullCalendar.formatDate(last_date,'yyyyMMdd')){
						last_date = header_date;
						var date_str = date_template.clone();
						date_str.find('.fc-event-list-date-header').html( $.fullCalendar.formatDate(ev.start, calendar.options.eventList.DateFormat||'dddd MMMM d, yyyy',calendar.options) );
						view_template.find('.fc-event-list-holder').append(date_str);
					}				
				}

				view_template.find('.fc-event-list-holder').append(item);	
			});
		}else{
			view_template.find('.fc-event-list-holder').append(no_events_template);	
		}
	}
	function trigger(){}
	function _sort_events(o,p){
		if(o.start>p.start){
			return 1;
		}else if(o.start<p.start){
			return -1;
		}else{
			return 0;
		}
	}
	function _rsort_events(o,p){
		if(o.start<p.start){
			return 1;
		}else if(o.start>p.start){
			return -1;
		}else{
			return 0;
		}
	}
}	

$.fullCalendar.views.rhc_detail = DetailView;	
function DetailView(element, calendar) {
	var t = this;
	var body;
	t.name = 'rhc_detail';
	t.render = render;
	t.unselect = unselect;
	t.setHeight = setHeight;
	t.setWidth = setWidth;
	t.clearEvents = clearEvents;
	t.renderEvents = renderEvents;
	t.trigger = trigger;
	t.viewChanged = viewChanged;
	t.beforeAnimation = beforeAnimation;
	
	t.element = element;
	
	function viewChanged(){
	
	}
	
	function beforeAnimation(oldView){
	
	}	
	function render(date,delta){
		t.start = date;//if not defined, hidden views do not update size on window resize.
		var firstTime = !body;
		if(firstTime){
			$('<div class="fc-detail-view-holder"><div class="fc-detail-view-content">TODO: a single event details. The button will be removed on the top right controls, and this view will be triggered when selecting an event.</div><div class="fc-detail-view-wp_footer" style="display:none;"></div></div>').appendTo(element);
			body = true;
		}else{
			 
		}
	}
	function unselect(){/*console.log('unselect');*/}
	function setHeight(h){
		//element.css('height',h);
		
		element.css('min-height','200px');
		element.css('height','auto');		
	}
	function setWidth(){/*console.log('setWidth');*/}
	function clearEvents(){
		/*console.log('clearEvents');*/
		$('.fc-detail-view-content').html( '' );
	}
	function renderEvents(){
		//console.log( calendar.last_clicked_event );
		//console.log('renderEvents');
		
		var args = {
			'id' : calendar.last_clicked_event.id
		};
		
		
		$.post(calendar.options.singleSource,args,function(data){
			if(data.R=='OK'){
				
				if( $('body .fc-single-item-holder').length==0 ){
					$('body').append('<div class="fc-single-item-holder"></div>');
				}
				
				$('body .fc-single-item-holder').empty();
				$(data.DATA.footer).each(function(i,inp){
					if( inp.nodeName && inp.nodeName=='SCRIPT'){
						var script   = document.createElement("script");
						if( $(inp).attr('type') ){
							script.type  = ($(inp).attr('type')||'');
						}
						if($(inp).attr('src')){
							script.src   = ($(inp).attr('src')||'');    // use this for linked script
						
						}else{
							script.text  = ($(inp).html()||'');
						}
						
						
						document.body.appendChild(script);
						
						//$('body .fc-single-item-holder').append( script );
						
					}else{
						$('body .fc-single-item-holder').append( inp );					
					}
				});
				
				$('.fc-detail-view-content').html( data.DATA.body );
			}
		},'json');
		
	}
	function trigger(){/*console.log('trigger');*/}
}	

})(jQuery);