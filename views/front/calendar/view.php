<h1>{{ template:title }}</h1>

<ul>
{{ categories }}

	<li><a href="{{ url:site uri="events_manager/calendar/category" }}/{{ slug }}" style="color: #{{ color_id:hex }};">{{ title }}</a></li>
	
{{ /categories }}
</ul>

<?php echo $this->calendar->generate($year, $month, $event_list); ?>