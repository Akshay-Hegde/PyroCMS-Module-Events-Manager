<h1>{{ template:title }}</h1>

<ul>
{{ categories }}

	<li><a href="{{ url:site uri="events_manager/calendar/category" }}/{{ category_slug }}" style="color: {{ color_id:color_slug }};">{{ category }}</a></li>
	
{{ /categories }}
</ul>

<?php echo $this->calendar->generate($year, $month, $event_list); ?>