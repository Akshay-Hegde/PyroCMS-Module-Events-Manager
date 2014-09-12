{{ event }}

	<div class="event">
		<h1>{{ title }}</h1>
		
		<div class="date">
			<span>{{ events_manager:display_timespan start=start end=end }}</span>
		</div>
		
		<div class="category">
			<span>{{ category_id:category }}</span>
		</div>
		
		<div class="details">
			{{ details }}
		</div>
	</div>
	
	{{ template:partial name="registration" }}
	
{{ /event }}