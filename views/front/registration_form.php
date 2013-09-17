<h4>Register for this event</h4>

{{ form_open }}

	{{ validation_errors }}

	<label for="name">Name</label>
	<input type="text" name="name" value="{{ name }}" id="name">
	
	<label for="email">Email</label>
	<input type="text" name="email" value="{{ email }}" id="email">
	
	<input type="submit" name="register" value="Register" id="register">
	
{{ form_close }}