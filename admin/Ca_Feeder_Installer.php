<?php
namespace ca_feeder\admin;

/**
 * Installer Class
 *
 * @package ASH
 */
class Ca_Feeder_Installer {

  /**
   * Binding all events
   *
   * @since 1.0
   *
   * @return void
   */
  public function __construct() {
    register_activation_hook(CA_FEEDER_FILE, [$this, 'activate']);
    register_deactivation_hook(CA_FEEDER_FILE, [$this, 'deactivate']);
  }

  /**
   * Placeholder for activation function
   * Nothing being called here yet.
   *
   * @since 1.0
   *
   * @return void
   */
  public function activate() {
    update_option('ca_feeder_version', CA_FEEDER_VERSION);
    $this->create_shortcode_holder_tables();

    // run schedule to get new token and store that token to wp_options table
    if (! wp_next_scheduled ( 'my_hourly_event' )) {
      wp_schedule_event( time(), 'hourly', 'my_hourly_event' );
    }
  }

  /**
   * Placeholder for deactivation function
   *
   * Nothing being called here yet.
   */
  public function deactivate() {
    // global $wpdb;
    //$table = $wpdb->prefix . 'kids_zone';
    //$wpdb->query( "DROP TABLE IF EXISTS $table" );

    // clear schedule
    wp_clear_scheduled_hook( 'my_hourly_event' );
  }

  /**
   * Create necessary table for ERP & HRM
   *
   * @since 1.0
   *
   * @return  void
   */
  public function create_shortcode_holder_tables() {
    global $wpdb;

    $collate = '';

    if ($wpdb->has_cap('collation')) {
      if (!empty($wpdb->charset)) {
        $collate .= "DEFAULT CHARACTER SET $wpdb->charset";
      }

      if (!empty($wpdb->collate)) {
        $collate .= " COLLATE $wpdb->collate";
      }
    }

    $table_schema = [
      "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}ca_shortcodes` (
        `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
        `shortcode` VARCHAR(255) NOT NULL,
        `fields` longtext NOT NULL,
        `fields_name` longtext NOT NULL,
        `app_name` VARCHAR(255) NOT NULL,
        `form_name` VARCHAR(255) NOT NULL,
        `entry_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`)
      ) $collate;,"
    ];

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    foreach ($table_schema as $table) {
      dbDelta($table);
    }
  }

}