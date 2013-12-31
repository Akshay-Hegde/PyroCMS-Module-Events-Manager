<div class="calendar-cell">
	
		<ul class="list list-unstyled">
			{{ events }}
			
				<li><a href="{{ url:site uri="events_manager/event" }}/{{ helper:date format="Y/m/d" timestamp=start }}/{{ slug }}" style="color: {{ hex }};">{{ title }}</a></li>
				
			{{ /events }}
		</ul>
		
		<span class="day-num">{{ day }}</span>
	
</div>