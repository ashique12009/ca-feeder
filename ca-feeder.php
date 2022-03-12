<?php 
/**
 * Plugin Name: CloudApper Feeder
 * Plugin URI: http://www.cloudapper.com/
 * Description: CloudApper data insert from website.
 * Version: 1.0
 * Author: CloudApper
 * Author URI: http://www.cloudapper.com
 */

// If this file is called directly, abort.
if (! defined('ABSPATH')) {
	die('Invalid request.');
}

/**
 * The main plugin class
 */
final class Ca_Feeder {

  /**
   * Plugin version
   */
  const version = '1.0';

  /**
   * Class constructor
   */
  private function __construct() {
    $this->define_constants();
    require_once CA_FEEDER_DIR_PATH . '/admin/Ca_Feeder_Installer.php';
    new ca_feeder\admin\Ca_Feeder_Installer();
    add_action('plugins_loaded', [$this, 'initialise_plugin']);
  }

  /**
   * Define constants
   */
  public function define_constants() {
    define('CA_FEEDER_VERSION', self::version);
    define('CA_FEEDER_FILE', __FILE__);
    define('CA_FEEDER_DIR_PATH', __DIR__);
    define('CA_FEEDER_URL', plugins_url('', CA_FEEDER_FILE));
    define('CA_FEEDER_ASSETS', CA_FEEDER_URL . '/assets');
  }

  /**
   * Initialises the plugin, loaded the classes
   */
  public function initialise_plugin() {
    if (!class_exists('WP_List_Table')) {
      require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
    }
    if (is_admin()) {
      require_once CA_FEEDER_DIR_PATH . '/admin/Admin_Classes_Loader.php';
      new ca_feeder\admin\Admin_Classes_Loader();
    }
    require_once CA_FEEDER_DIR_PATH . '/admin/functions-ca-feeder.php';
    require_once CA_FEEDER_DIR_PATH . '/admin/Shortcode_List_Table.php';
    require_once CA_FEEDER_DIR_PATH . '/admin/Shortcode_Handler.php';
    new ca_feeder\admin\Shortcode_Handler();
  }

  /**
   * Initialise a singleton instance
   * @return \Ca_Feeder
   */
  public static function init() {
    static $instance = false;
    if (!$instance) {
      $instance = new self();
    }

    return $instance;
  }

}

// connect style and JS files into frontend on plugin activation
function ca_scripts_enqueue() {
    wp_register_style( 'customCaStyle', plugins_url( '/front-assets/style.css',    __FILE__ ), false );
    wp_enqueue_style ( 'customCaStyle' );

    wp_register_script( 'jQuery', 'https://code.jquery.com/jquery-3.4.1.min.js', null, null, true );
    wp_enqueue_script('jQuery');

    wp_register_script( 'trafficSourceTrackerJs', plugins_url( '/front-assets/trafficSourceTracker.js', __FILE__ ), null, null, true );
    wp_enqueue_script('trafficSourceTrackerJs');

    wp_register_script( 'customCaScript', plugins_url( '/front-assets/script.js', __FILE__ ), null, null, true );
    wp_enqueue_script('customCaScript');
    
  //  wp_register_script( 'Custom_Js', get_stylesheet_directory_uri() . '/js/custom.js', null, null, true );
  //  wp_enqueue_script('Custom_Js');
}
add_action( 'wp_enqueue_scripts', 'ca_scripts_enqueue', 80 );

add_action( 'my_hourly_event', 'ca_do_this_hourly' );
function ca_do_this_hourly() {
  // do something every hour
  // first check in wp_options table there is username and password are exist or not
  // if exist then get new token and and store it to wp_options table
  $userEmailReplacePlus = get_option( 'ca_username', true );
  $userPassword = get_option( 'ca_user_password', true );
  if ($userEmailReplacePlus && $userPassword) {
    // make username fix by replacing
    $userEmailReplaceAt = str_replace("@","%40","$userEmailReplacePlus");
		$userEmailReplacePlus = str_replace("+","%2B","$userEmailReplaceAt");
    // decrypt password
    $key = "CloudApper";
    $decrypted_string_as_password = openssl_decrypt($userPassword, "AES-128-ECB", $key);

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
      CURLOPT_POSTFIELDS => 'grant_type=password&username='.$userEmailReplacePlus.'&password='.$decrypted_string_as_password.'&scope=openid+profile+ko_webapi_v2&client_id=identity_client_wordpress&client_secret=a2cdb8a52b22484ca011ed15a5292add',
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
    }
  }
}

/**
 * Initialise the main plugin
 * @return \Ca_Feeder
 */
function ca_feeder() {
  return Ca_Feeder::init();
}

// Kick off the plugin
ca_feeder();