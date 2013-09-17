{{ template:title }}

{{ categories }}

	{{ category }}
	
{{ /categories }}

<?php echo $this->calendar->generate($year, $month, $event_list); ?>