<?php
namespace ca_feeder\admin;

/**
 * The assets class
 */
class Assets {
  function __construct() {
    add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);
  }

  public function enqueue_assets($hook) {
    $current_screen = get_current_screen();
    if ( strpos($current_screen->base, 'ca-feeder') === false) {
      return;
    } else {
      wp_enqueue_style('cld-bootstrap-css', CA_FEEDER_ASSETS . '/css/bootstrap.min.css', NULL, microtime(), 'all');
      wp_enqueue_style('cld-custom-css', CA_FEEDER_ASSETS . '/css/custom.css', NULL, microtime(), 'all');
      
      wp_enqueue_script('cld-custom-js', CA_FEEDER_ASSETS . '/js/custom.js', ['jquery'], '1.0.0', true);

      wp_localize_script('cld-custom-js', 'settings', 
        [
          'ajaxurl' => admin_url('admin-ajax.php'),
        ]
      );
    }
  }
}