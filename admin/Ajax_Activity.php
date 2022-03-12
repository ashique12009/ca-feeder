<?php
namespace ca_feeder\admin;

/**
 * The Ajax_Activity class
 */
class Ajax_Activity {

  function __construct() {
    add_action('wp_ajax_cld_save_form', [$this, 'save_form']);
    add_action('wp_ajax_cld_remove_sc', [$this, 'remove_sc']);
  }

  public function save_form() {
    global $wpdb;

    $data = $_POST;
    $app_id = $data['app_id'];
    $form_id = $data['form_id'];
    $form_name = $data['form_name'];
    $user_defined_word = $data['user_defined_word'];
    $field_collection = implode(', ',$data['field_collection']);
    $field_name_collection = implode(', ',$data['field_name_collection']);

    $digits = 10;
    $radon_10_digit = rand(pow(10, $digits-1), pow(10, $digits)-1);
    // add_option('cloudapper-' . $radon_10_digit . '-' . $form_name . '-' . $user_defined_word, $field_collection);
    $table = $wpdb->prefix . 'ca_shortcodes';
    $table_data = [
      'shortcode'   => 'cloudapper-' . $radon_10_digit . '-' . $form_name . '-' . $user_defined_word,
      'fields'      => $field_collection,
      'fields_name' => $field_name_collection,
      'app_name'    => $app_id,
      'form_name'   => $form_name
    ];
    $format = [
      '%s', '%s', '%s', '%s'
    ];
    $wpdb->insert($table, $table_data, $format);

    wp_send_json_success('Form saved or updated!');
  }
}