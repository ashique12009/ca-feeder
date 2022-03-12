<div class="wrap">
<h1>Form detail</h1>

<?php

if (isset($_GET['formid'])) {
	$formid = $_GET['formid'];
}
if (isset($_GET['form_name'])) {
	$form_name = $_GET['form_name'];
}
if (isset($_GET['appid'])) {
	$appid = $_GET['appid'];
	update_option( 'ca_app_id', $appid, true );
}

$curl = curl_init();
// Get access token from options table
$access_token = get_option('ca_access_token');

$client_id = get_option( 'ca_client_id');
$user_name = get_option('ca_user_name');
$user_id = get_option( 'ca_user_id');

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://dev-api.cloudapper.com/api/v2.0/Users/details',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
  "ClientId": "'.$client_id.'",
  "AppId": "'.$appid.'",
  "UserId": "'.$user_id.'",
  "UserName": "'.$user_name.'",
  "ClientPlatform": 3,
  "ClientVersion": "5.5.4.8"
}',
  CURLOPT_HTTPHEADER => array(
    'Authorization: Bearer ' . $access_token,
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);
curl_close($curl);
$response_array = json_decode($response, true);
// echo "<pre>";
// var_dump(json_decode($response, true));
// echo "</pre>";
// exit;
?>

<ul class="fields-list">
<?php
if (isset($response_array['Result']['Schemas'])) {
	foreach ($response_array['Result']['Schemas'] as $schema) {
		if ( $schema['Id'] == $formid ) {
				$x = 1;
			foreach ($schema['Fields'] as $value) {
				if ( $value['ControlType'] != 8 && $value['ControlType'] != 9 && $value['ControlType'] != 99 && $value['ControlType'] != 100 && $value['ControlType'] != 101 && $value['ControlType'] != 102 ) {
				?>
					<li>
						<label for="<?php echo $value['Name']; ?>">
							<?php if ($value['Mandatory']) : ?>
							<span class="fieldCounter"><?php $num_padded = sprintf("%02d", $x); echo $num_padded . ". "; ?></span> <input type="checkbox" checked="checked" disabled="disabled" class="take-fields" id="<?php echo $value['Name']; ?>" name="<?php echo $value['Name']; ?>" value="<?php echo $value['Label']; ?>"> <span class="form-field"><?php if( isset( $value['Label'] ) ) { echo $value['Label']; } ?></span>
							<?php else : ?>
							<span class="fieldCounter"><?php $num_padded = sprintf("%02d", $x); echo $num_padded . ". "; ?></span> <input type="checkbox" class="take-fields" id="<?php echo $value['Name']; ?>" name="<?php echo $value['Name']; ?>" value="<?php echo $value['Label']; ?>"> <span class="form-field"><?php if( isset( $value['Label'] ) ) { echo $value['Label']; } ?></span>
							<?php endif; ?>
						</label>
					</li>
				<?php
				$x++;
				}
			}
		}	
	}
}
?>
</ul>

<?php  
if (isset($_GET['appid'])) {
	$app_id = $_GET['appid'];
}
else {
	$app_id = get_option('ca_app_id');
}
?>

<div class="form-preview-wrapper">
	<span class="spinner"></span>
	<p class="shortcodeGenerator"></p>
	<p class="allInfo">
		<span class="checkedInput"></span>
		<span id="log"></span>
	</p>
	<div id="formWrapper">
		<input type="text" name="user-defined-word" id="user-defined-word" value="" maxlength="32" placeholder="Name Your Shortcode" required />
		<input type="hidden" name="appid" id="appid" value="<?php echo $app_id;?>">
		<input type="hidden" name="formid" id="formid" value="<?php echo $formid;?>">
		<input type="hidden" name="formname" id="formname" value="<?php echo $form_name;?>">
		<h2 style="color: #1d2327;font-size: 1.3em;margin: 1em 0;">Form Preview</h2>
		<form id="customForm" method="post">
			<table class="form-table"></table>
			<input type="hidden" name="cpage" value="<?php echo get_permalink();?>">
			<input type="hidden" name="app_id" value="<?php echo $app_id;?>">
			<input type="submit" name="submit" class="vhide">
		</form>	
		<button id="btn-shortcodeGenerator" class="button button-primary">Generate Form</button>	
	</div>
</div>

</div>