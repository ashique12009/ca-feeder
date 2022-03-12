<div class="wrap">
<h1><?php echo $_GET['appname'] ?> App Forms</h1>

<?php

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
// var_dump($response_array);
// echo "</pre>";
// exit();

if (isset($response_array['Result']['Schemas'])) {
?>
<ul class="form-list">
  <?php foreach ($response_array['Result']['Schemas'] as $value) { ?>
  <li>
    <a class="appForm button button-primary" href="<?php echo admin_url('admin.php?page=ca-feeder-form-detail&formid='.$value["Id"].'&appid=' . $appid . '&form_name=' . $value['Title']);?>"><?php echo $value['Title']; ?></a>
  </li>
  <?php } ?>
</ul>

</div>
<?php
}
exit;
