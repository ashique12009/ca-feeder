<?php
namespace ca_feeder\admin;

function getShortcodeRow($args) {
  global $wpdb;

  $defaults = array(
    'offset' => 0,
    'number' => 5,
  );

  $args = wp_parse_args($args, $defaults);

  $query = "SELECT id,
                  shortcode,
                  fields,
                  fields_name,
                  app_name,
                  form_name  
              FROM {$wpdb->prefix}ca_shortcodes 
              WHERE shortcode LIKE '%cloudapper%'";
  if (isset($args['orderby'])) {
    $query .= " ORDER BY " . $args['orderby'] . " " . $args['order'] . " LIMIT {$args['offset']}, {$args['number']}";
  } else {
    $query .= " ORDER BY id DESC LIMIT {$args['offset']}, {$args['number']}";
  }

  return $wpdb->get_results($query, ARRAY_A);
}

function getTotalShortcodeRows($args) {
  global $wpdb;

  $defaults = array(
    'offset' => 0,
    'number' => 5,
  );

  $args = wp_parse_args($args, $defaults);

  $query = "SELECT id,
                    shortcode,
                    fields, 
                    fields_name, 
                    app_name,
                    form_name 
              FROM {$wpdb->prefix}ca_shortcodes
              WHERE shortcode LIKE '%cloudapper%'";

  $udata = $wpdb->get_results($query, ARRAY_A);

  if (is_array($udata) && count($udata) > 0) {
    return count($udata);
  } else {
    return 0;
  }
}

// generate GUID value
function getGUID() {
  if (function_exists('com_create_guid')) {
      return com_create_guid();
  }
  else{
      mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
      $charid = strtoupper(md5(uniqid(rand(), true)));
      $hyphen = chr(45);// "-"
      $uuid = substr($charid, 0, 8).$hyphen
          .substr($charid, 8, 4).$hyphen
          .substr($charid,12, 4).$hyphen
          .substr($charid,16, 4).$hyphen
          .substr($charid,20,12);
      return $uuid;
  }
}