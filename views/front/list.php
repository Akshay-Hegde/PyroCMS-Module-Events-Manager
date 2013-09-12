{{ if events }}

	<table class="table">
		<tr>
			<th>Date</th>
			<th>Event</th>
			<th>Location</th>
		</tr>
	
		{{ events }}
	
		<tr>
			<td>{{ events_manager:display_timespan start=start end=end }}</td>
			<td><a href="{{ url:site }}event{{ helper:date format="/Y/m/d/" timestamp=start }}{{ slug }}">{{ title }}</a></td>
			<td>{{ location }}</td>
		</tr>
	
		{{ /events }}
	</table>

{{ else }}

	<div class="alert alert-warning">
		<p>No events.</p>
	</div>

{{ endif }}