<h1>Dashboard</h1>

<div>
	<a class="activateAccountLink" href="https://dev-account.cloudapper.com/connect/authorize?client_id=identity_client_wordpress&scope=openid%20profile%20api1%20offline_access&state=5784268afff648b78af562021e391a8e&redirect_uri=https://kernello.com/dev/wp-content/plugins/ca-feeder/views/settings.php&response_type=code">Activate Account</a>
	<!-- <a class="activateAccountLink" href="https://dev-account.cloudapper.com/connect/authorize?client_id=identity_client_wordpress&scope=openid%20profile%20api1%20offline_access&state=5784268afff648b78af562021e391a8e&redirect_uri=https://kernello.com/dev/wp-admin/admin.php?page=ca-feeder-settings&response_type=code">Activate Account</a> -->
	<?php 
		$path = 'admin.php?page=ca-feeder-get-token';
		$url = admin_url($path);
	?>
	<a href="<?php echo $url;?>">Get access token</a>
	<?php 
		$path = 'admin.php?page=ca-feeder-add-employee';
		$url = admin_url($path);
	?>
	<a href="<?php echo $url;?>">Add employee</a>
</div>

<div>
	<div>
		<h1 class="ml-13">Shorcode List</h1>
		<form method="get" class="visitor-list-table custom-list-style">
			<input type="hidden" name="page" value="ca-feeder">
			<?php
				$Shortcode_List_Table = new ca_feeder\admin\Shortcode_List_Table();
				$Shortcode_List_Table->prepare_items();
				$Shortcode_List_Table->views();
				$Shortcode_List_Table->display();
			?>
		</form>
	</div>
</div>
