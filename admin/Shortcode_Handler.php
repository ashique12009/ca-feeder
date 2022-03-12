<?php
namespace ca_feeder\admin;

/**
 * The shorcode handler class
 */
class Shortcode_Handler {

  function __construct() {
    add_action('init', [$this, 'cld_add_custom_shortcode']);
    add_action('admin_post_action_handle_shortcode', [$this, 'cld_post_handle_shortcode']);
    add_action('admin_post_nopriv_action_handle_shortcode', [$this, 'cld_post_handle_shortcode']);
  }

  public function cld_add_custom_shortcode() {
    global $wpdb;

    $table_name = $wpdb->prefix . 'ca_shortcodes';
    $results = $wpdb->get_results("SELECT * FROM $table_name WHERE shortcode LIKE '%cloudapper-%'");

    foreach ($results as $value) {
      $form_id = $value->shortcode;
      $field_array = explode(',', $value->fields);
      $field_name_array = explode(',', $value->fields_name);
      $this->cld_gen_form($form_id, $field_array, $field_name_array);
    }
  }

  public function cld_gen_form($form_id, $field_array, $field_name_array) {
    add_shortcode($form_id, function() use ($form_id, $field_array, $field_name_array) {
      $form = '<form id="' . $form_id . '" method="post" action="'.esc_url( admin_url('admin-post.php') ).'"><div class="custom-ca-form-wrap">';
      $x = 0;
      foreach ($field_name_array as $val) {
        $form .= '<div class="ca_form_field"><label><span>'.$field_array[$x].': </span><br><input name="'.trim($val).'" value="" id="'.trim($val).'" placeholder="'.$field_array[$x].'" /></label></div>';
        $x++;
      }
      $form .= '<div class="ca_form_field"><input type="hidden" name="action" value="action_handle_shortcode"></div>';
      $form .= '<div class="ca_form_field"><input type="hidden" name="cpage" value="'.get_permalink().'"></div>';
      $form .= '<div class="ca_form_field" style="margin-top: 15px;"><input type="submit" name="submit"></div>';
      $form .= '</div></form>';
      return $form;
    });
  }

  public function cld_post_handle_shortcode() {
    global $wp;
    $rurl = home_url($wp->request);
    $post = $_POST;

    $cpage_url = $post['cpage'];

    $string_fields = '';
    foreach ($post as $key => $value) {
      if (strpos($key, 'string') !== false) {
        $string_fields .= '"'.$key.'"'.':'.'"'.$value.'"'. ',';
      }      
    }

    // static data
    // $clientId = "1342038400000848";
    // $appId = "e6657d2d-e7bf-46b1-a9df-d367909e25be";
    // $appId = "1bb884ac-ed86-4808-9915-4d9faa3d87b2";
    $appid = isset($post['appid']) ? $post['appid'] : get_option('ca_app_id');
    // $userId = "de2e18e1-a265-467e-bf19-03b313c21a09";
    // $userName = "Shakib R Khan";

    $client_id = get_option('ca_client_id');
    $user_name = get_option('ca_user_name');
    $user_id = get_option('ca_user_id');

    $curl = curl_init();
    // Get access token from options table
    $access_token = get_option('ca_access_token');

    curl_setopt_array($curl, array(
      // CURLOPT_URL => 'https://dev-api.cloudapper.com/api/v2.0/Records/create-or-update-record',
      CURLOPT_URL => 'https://dev-api.cloudapper.com/api/v2.0/Records/save-records?records-required=true',
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
      "ClientVersion": "5.5.4.36",
      "Request": {
        "IsEdit": false,
        "Item": {
          '.$string_fields.'
          "stringField2TypeId": "0d1a20a0-9615-4443-852b-3d32f6d6f144",
          "TypeId": "50874d91-05c7-45e4-957c-86b360312861",
          "AppId": "1bb884ac-ed86-4808-9915-4d9faa3d87b2",
          "ClientId": 1342038400000848,
          "id": "'.getGUID().'",
          "stringField6TypeId": "50874d91-05c7-45e4-957c-86b360312861",
          "stringField19TypeId": "50874d91-05c7-45e4-957c-86b360312861",
          "booleanField1": true,
          "CreatedBy": "Shakib R Khan",
          "CreateDate": "2021-03-28T11:12:39.218Z",
          "CreatedById": "de2e18e1-a265-467e-bf19-03b313c21a09",
          "Type": 1,
          "Status": 2,
          "DisplayName": "sh12009 org"
        },
        "DefaultUser": null
      }
    }',
      CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $access_token
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $response = json_decode($response, true);
    var_dump($response);exit;
    if ($response['Success']) {
      wp_redirect($cpage_url);
      exit;
    }
  }
}