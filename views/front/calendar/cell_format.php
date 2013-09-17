<div class="calendar-cell">
	
	<ul class="list list-unstyled">
		{{ events }}
		
			<li><a href="{{ url }}" style="color: {{ color_slug }};">{{ title }}</a></li>
			
		{{ /events }}
	</ul>
	
	<span class="day-num">{{ day }}</span>
</div>