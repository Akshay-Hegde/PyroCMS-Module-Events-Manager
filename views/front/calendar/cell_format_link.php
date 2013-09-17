<div class="calendar-cell">
	
	{{ events }}
	
		<a href="{{ url:site uri="events_manager/calendar" }}/{{ helper:date format="Y/m/d" timestamp=start }}"><span class="day-num">{{ helper:date format="d" timestamp=start }}</span></a>
		
	{{ /events }}
	
	
</div>