<div class="calendar-cell">
	
		<ul class="uk-list">
			{{ events }}

            <li class="hidden-print"><a href="{{ url:site uri="events_manager/event" }}/{{ helper:date format="Y/m/d" timestamp=start }}/{{ slug }}" style="color: #{{ hex }};"><strong>{{ title }}</strong> - ({{ helper:date format="g:i a" timestamp=start }} to {{ helper:date format="g:i a" timestamp=end }})</a>
                </il>
				
			{{ /events }}
		</ul>
		
		<span class="day-num">{{ day }}</span>
	
</div>