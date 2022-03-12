<h1>Login</h1>

<div>
	<div>
		<h1 class="ml-13">Login to get access token</h1>
		<form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" class="visitor-list-table custom-list-style">
			<input type="text" name="email" id="email" value="" placeholder="Enter your email">
      <input type="password" name="password" id="password" value="" placeholder="Enter your password">
      <input type="hidden" name="action" value="action_token_login_form">
      <input type="submit" value="Log in">
		</form>
	</div>
</div>
