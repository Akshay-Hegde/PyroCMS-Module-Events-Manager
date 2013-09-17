{{ if events }}

<h1>{{ template:title }}</h1>

<ul>
{{ categories }}

	<li><a href="{{ url:site uri="events_manager/events/category" }}/{{ category_slug }}" style="color: {{ color_id:color_slug }};">{{ category }}</a></li>
	
{{ /categories }}
</ul>

	<table class="table">
		<tr>
			<th>Date</th>
			<th>Event</th>
			<th>Location</th>
		</tr>
	
		{{ events }}
	
		<tr>
			<td>{{ events_manager:display_timespan start=start end=end }}</td>
			<td><a href="{{ url:site }}events_manager/event{{ helper:date format="/Y/m/d/" timestamp=start }}{{ slug }}" style="color: {{ color_slug }};">{{ title }}</a></td>
			<td>{{ location }}</td>
		</tr>
	
		{{ /events }}
	</table>
	
	{{ pagination }}

{{ else }}

	<div class="alert alert-warning">
		<p>No events.</p>
	</div>

{{ endif }}