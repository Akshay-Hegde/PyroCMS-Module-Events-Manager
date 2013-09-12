{{ event }}

	<div class="event">
		<h2>{{ title }}</h2>
		
		<div class="date">
			<span>{{ events_manager:display_timespan start=start end=end }}</span>
		</div>
		
		<div class="category">
			<span>{{ category_id:category }}</span>
		</div>
		
		<div class="description">
			{{ description }}
		</div>
	</div>
	
{{ /event }}