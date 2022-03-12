<?php
namespace ca_feeder\admin;

/**
 * The shorcode handler class
 */
class Form_Handler {

  function __construct() {
    add_action('admin_post_action_token_login_form', [$this, 'cld_post_handle_token_login_form']);
    add_action('admin_post_nopriv_action_token_login_form', [$this, 'cld_post_handle_token_login_form']);

    add_action('admin_post_action_token_add_employee_form', [$this, 'cld_post_handle_add_employee_form']);
    add_action('admin_post_nopriv_action_token_add_employee_form', [$this, 'cld_post_handle_add_employee_form']);
    
    add_action('admin_post_action_logout_ca_feeder', [$this, 'cld_post_handle_logout_ca_feeder']);
    add_action('admin_post_nopriv_action_logout_ca_feeder', [$this, 'cld_post_handle_logout_ca_feeder']);
  }

  public function cld_post_handle_token_login_form() {
    global $wp;
    $rurl = home_url($wp->request);
    $post = $_POST;

    $email = $post['email'];
    $password = $post['password'];

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_PORT => "8000",
      CURLOPT_URL => "http://localhost:8000/api/login",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "email=".$email."&password=".$password,
      CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded",
        "postman-token: d9554592-c552-3390-c9a9-82ff1e2a3c67"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      $response_array = json_decode($response, TRUE);
      $token = $response_array['token'];
      // store token to this wp option table
      if (is_null($token)) {
        echo "Token not found! Please login first!"; exit;
      }
      else {
        update_option('server_app_token', $token);
        $path = 'admin.php?page=ca-feeder';
		    $url = admin_url($path);
        wp_redirect($url);
      }
    }
  }

  public function cld_post_handle_add_employee_form() {
    global $wp;
    $rurl = home_url($wp->request);
    $post = $_POST;

    $name = $post['name'];
    $age = $post['age'];
    $job = $post['job'];
    $salary = $post['salary'];
    
    // Get token
    $token = get_option('server_app_token');

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_PORT => "8000",
      CURLOPT_URL => "http://localhost:8000/api/employee",
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => "name=".$name."&age=".$age."&job=".$job."&salary=".$salary,
      CURLOPT_HTTPHEADER => array(
        "authorization: Bearer " . $token,
        "cache-control: no-cache",
        "content-type: application/x-www-form-urlencoded",
        "postman-token: 3f1faf1d-9450-9c98-5ba3-fe94f8b9c760"
      ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
      echo "cURL Error #:" . $err;
    } else {
      $response_array = json_decode($response, TRUE);
      $message = $response_array['message'];
      // store token to this wp option table
      if (!isset($message) || $message != "Success") {
        echo "Error!"; exit;
      }
      else {
        $path = 'admin.php?page=ca-feeder';
		    $url = admin_url($path);
        wp_redirect($url);
      }
    }
  }

  public function cld_post_handle_logout_ca_feeder() {
    $remove_access_token = $_POST['remove_access_token'];
    if ($remove_access_token === 'yes') {
      delete_option( 'ca_access_token' );
    }
    $path = 'admin.php?page=ca-feeder-settings';
    $url = admin_url($path);
    wp_redirect($url);
  }
}