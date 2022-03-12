<?php
namespace ca_feeder\admin;

/**
 * The shorcode handler class
 */
class Settings {

  function __construct() {
    add_action('admin_post_settings_action', [$this, 'cld_post_settings']);
    add_action('admin_post_nopriv_settings_action', [$this, 'cld_post_settings']);
  }

  public function cld_post_settings() {
    $post = $_POST;

    $clientid = $post['clientid'];
    $userid = $post['userid'];
    $username = $post['username'];
    $clientplatform = $post['clientplatform'];
    $clientversion = $post['clientversion'];
    $nonce = $post['nonce'];

    $c_admin_page_url = admin_url('admin.php?page=ca-feeder-settings');

    if (isset($clientid) && wp_verify_nonce($nonce, 'settings_action')) {
      if (is_null(get_option('cld_client_id'))) {
        add_option('cld_client_id', $clientid);
      }
      else {
        update_option('cld_client_id', $clientid);
      }
    }

    if (isset($userid) && wp_verify_nonce($nonce, 'settings_action')) {
      if (is_null(get_option('cld_user_id'))) {
        add_option('cld_user_id', $userid);
      }
      else {
        update_option('cld_user_id', $userid);
      }      
    }
    
    if (isset($username) && wp_verify_nonce($nonce, 'settings_action')) {
      if (is_null(get_option('cld_user_name'))) {
        add_option('cld_user_name', $username);
      }
      else {
        update_option('cld_user_name', $username);
      }      
    }

    if (isset($clientplatform) && wp_verify_nonce($nonce, 'settings_action')) {
      if (is_null(get_option('cld_client_platform'))) {
        add_option('cld_client_platform', $clientplatform);
      }
      else {
        update_option('cld_client_platform', $clientplatform);
      }      
    }

    if (isset($clientversion) && wp_verify_nonce($nonce, 'settings_action')) {
      if (is_null(get_option('cld_client_version'))) {
        add_option('cld_client_version', $clientversion);
      }
      else {
        update_option('cld_client_version', $clientversion);
      }      
    }

    wp_redirect($c_admin_page_url);
    exit;
  }

}