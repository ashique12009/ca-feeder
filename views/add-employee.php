<h1>Add Employee</h1>

<div>
	<div>
		<h1 class="ml-13">Fill the form to add employee to 3rd party server app</h1>
		<form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" class="visitor-list-table custom-list-style">
			<input type="text" name="name" id="name" value="" placeholder="Enter name">
			<input type="text" name="age" id="age" value="" placeholder="Enter age">
			<input type="text" name="job" id="job" value="" placeholder="Enter job">
			<input type="text" name="salary" id="salary" value="" placeholder="Enter salary">
      <input type="hidden" name="action" value="action_token_add_employee_form">
      <input type="submit" value="Submit">
		</form>
	</div>
</div>
