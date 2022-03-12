<div class="wrap">

	<h1>Settings</h1>

	<?php
		if ( isset( $_POST['customUserInfoSubmit'] ) ) {
			$userEmail = $_POST['customUserEmail'];
			$userEmailReplaceAt = str_replace("@","%40","$userEmail");
			$userEmailReplacePlus = str_replace("+","%2B","$userEmailReplaceAt");
			$userPassword = $_POST['customUserPassword'];

			$curl = curl_init();

			curl_setopt_array($curl, array(
			// CURLOPT_URL => 'https://account-v1qa.cloudapper.com/connect/token',
			CURLOPT_URL => 'https://dev-account.cloudapper.com/connect/token',
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => 'grant_type=password&username='.$userEmailReplacePlus.'&password='.$userPassword.'&scope=openid+profile+ko_webapi_v2&client_id=identity_client_wordpress&client_secret=a2cdb8a52b22484ca011ed15a5292add',
			CURLOPT_HTTPHEADER => array(
				'Content-Type: application/x-www-form-urlencoded'
			),
			));

			$response = curl_exec($curl);
			curl_close($curl);

			$response_json = json_decode($response);

			if (isset($response_json->access_token)) {
				// here will collect the USER INFO from API and save into WP OPTIONS table
				update_option('ca_access_token', $response_json->access_token);
				echo '<h5>Access token stored Successfully.</h5>';
				// Store username and password in option table
				update_option( 'ca_username', $userEmailReplacePlus, true );
				$key = 'CloudApper';
				$encrypted_password_string = openssl_encrypt($userPassword, "AES-128-ECB", $key);
				update_option( 'ca_user_password', $encrypted_password_string, true );
			}
			else {
				echo '<h4>Something went wrong!</h4>';
			}
		}
	?>

	<div class="custom_wrap">
		<div class="custom_section">
			<?php $token = get_option('ca_access_token');?>
			<?php if ($token) : ?>
				<form action="<?php echo esc_url( admin_url('admin-post.php') );?>" method="post">
					<input type="hidden" name="action" value="action_logout_ca_feeder">
					<input type="hidden" name="remove_access_token" value="yes">
					<input type="submit" value="Logout">
				</form>
			<?php else : ?>
				<h2 style="color: #1d2327;font-size: 1.3em;margin: 1em 0;">CloudApper Account</h2>
				<div class="getToken">
					<form action="" method="post" enctype="multipart/form-data">
						<table class="form-table" cellpadding="5">
							<tr>
								<th>User Email: </td>
								<td>
									<input type="email" name="customUserEmail" placeholder="Login Email" required="required">
								</td>
							</tr>
							<tr>
								<th>Password: </td>
								<td>
									<input type="password" name="customUserPassword" placeholder="Login Password" required="required">
								</td>
							</tr>
							<tr>
								<th>
									<input type="submit" name="customUserInfoSubmit" class="button button-primary" value="Login">
								</th>
							</tr>
						</table>
					</form>
				</div>
			<?php endif; ?>
		</div>
		<div class="custom_section">

		<?php 
			global $wpdb;
			$token = get_option('ca_access_token');
			// START this api call to get apps list
			$curl = curl_init();
		
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://dev-api.cloudapper.com/api/v2.0/Users/clients',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer ' . $token
				),
			));
			
			$response = curl_exec($curl);			
			curl_close($curl);

			$app_list = json_decode($response, true);

			// echo '<pre>'; var_dump($app_list['Result']); echo '</pre>';
			
			$client_id = $app_list['Result']['Clients'][0]['Id'];
			$company_name = $app_list['Result']['Clients'][0]['Name'];
			$user_name = $app_list['Result']['Name'];
			$user_id = $app_list['Result']['Id'];

			// Update options for client id, user name, user id
			update_option( 'ca_client_id', $client_id, true );
			update_option( 'ca_user_name', $user_name, true );
			update_option( 'ca_user_id', $user_id, true );
			// $get_client_id = $app_list['Result']['Clients'];
			// $get_client_info = $app_list;
		?>

			<h2 style="color: #1d2327;font-size: 1.3em;margin: 1em 0;">User Detail</h2>
			<table class="form-table custom-user-info">
				<tr>
					<th><label>Client ID</label></th>
					<td> <?php echo $client_id; ?></td>
				</tr>
				<tr>
					<th><label>User ID</label></th>
					<td> <?php echo $user_id; ?></td>
				</tr>
				<tr>
					<th><label>User Name</label></th>
					<td> <?php echo $user_name; ?></td>
				</tr>
				<tr>
					<th><label>Client Platform</label></th>
					<td> <?php echo $client_platform = get_option('cld_client_platform'); ?></td>
				</tr>
				<tr>
					<th><label>Client Version</label></th>
					<td> <?php echo $client_version = get_option('cld_client_version'); ?></td>
				</tr>
			</table>
		<!-- 	
			<form method="post" action="<?php //echo esc_url( admin_url('admin-post.php') );?>">
				<table class="form-table">
					<tr>
						<th><label for="clientid">Client ID</label></th>
						<td><input type="text" name="clientid" id="clientid" value="<?php //echo $client_id;?>" /></td>
					</tr>
					<tr>
						<th><label for="userid">User ID</label></th>
						<td><input type="text" name="userid" id="userid" value="<?php //echo $user_id;?>" /></td>
					</tr>
					<tr>
						<th><label for="username">User Name</label></th>
						<td><input type="text" name="username" id="username" value="<?php //echo $user_name;?>" /></td>
					</tr>
					<tr>
						<th><label for="clientplatform">Client Platform</label></th>
						<td><input type="text" name="clientplatform" id="clientplatform" value="<?php //echo $client_platform;?>" /></td>
					</tr>
					<tr>
						<th><label for="clientversion">Client Version</label></th>
						<td>
							<input type="text" name="clientversion" id="clientversion" value="<?php //echo $client_version;?>" />
							<input type="hidden" name="action" value="settings_action" />
							<input type="hidden" name="nonce" value="<?php //echo wp_create_nonce('settings_action');?>" />
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<input class="button button-primary" type="submit" value="Submit" />
						</td>
					</tr>
				</table>	
			</form> -->

		</div>
	</div>

	<hr>

	<?php if ($token) { ?>
		<?php 
			// START this api call to get apps list
			$getToken = $hasToken;
			$curl = curl_init();
		
			curl_setopt_array($curl, array(
				CURLOPT_URL => 'https://dev-api.cloudapper.com/api/v2.0/Users/clients',
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'GET',
				CURLOPT_HTTPHEADER => array(
				'Authorization: Bearer '.$token
				),
			));
			
			$response = curl_exec($curl);
			
			curl_close($curl);
			$app_list = json_decode($response, true);
			$get_app_list = isset($app_list['Result']['Clients'][0]['Apps']) ? $app_list['Result']['Clients'][0]['Apps'] : [];
		?>
		<h2 style="color: #1d2327;font-size: 1.3em;margin: 1em 0;">Apps list</h2>
		<ul class="app-list">
			<?php foreach ($get_app_list as $value): ?>
			<li>
				<a class="button button-primary" href="<?php echo admin_url("admin.php?page=ca-feeder-app-detail&appid=" . $value['Id']."&appname=" . $value['Name']);?>"><?php echo $value['Name'];?></a>
			</li>
			<?php endforeach; ?>
		</ul>
	<?php } else { ?>
		<p style="background: #f5ff56;display: table;padding: 8px 20px;border-radius: 3px;margin: 0;margin-top: 10px;">Please make CloudApper Account login to get installed apps list.</p>
	<?php } ?>

</div>