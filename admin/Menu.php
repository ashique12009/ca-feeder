<?php
namespace ca_feeder\admin;

/**
 * The menu class
 */
class Menu {
  function __construct() {
    add_action('admin_menu', [$this, 'admin_menu']);
  }

  public function admin_menu() {
    add_menu_page( 
      "Cloudapper", 
      "Cloudapper", 
      "manage_options", 
      "ca-feeder", 
      [$this, "cloudapper_admin_dashboard_view"], 
      "dashicons-dashboard", 
      11 
    );
  
    add_submenu_page( 
      "ca-feeder", 
      "Dashboard", 
      "Dashboard", 
      "manage_options", 
      "ca-feeder", 
      [$this, "cloudapper_admin_dashboard_view"]
    );
  
    add_submenu_page( 
      "ca-feeder", 
      "Settings", 
      "Settings", 
      "manage_options", 
      "ca-feeder-settings", 
      [$this, "cloudapper_admin_settings_view"]
    );
  
    add_submenu_page( 
      null, 
      "Get token", 
      "Get token", 
      "manage_options", 
      "ca-feeder-get-token", 
      [$this, "cloudapper_admin_get_token"]
    );

    add_submenu_page( 
      null, 
      "Add employee", 
      "Add employee", 
      "manage_options", 
      "ca-feeder-add-employee", 
      [$this, "cloudapper_admin_add_employee"]
    );

    add_submenu_page( 
      null, 
      "App detail", 
      "App detail", 
      "manage_options", 
      "ca-feeder-app-detail", 
      [$this, "cloudapper_admin_app_detail_view"]
    );
  
    add_submenu_page( 
      null, 
      "Form detail", 
      "Form detail", 
      "manage_options", 
      "ca-feeder-form-detail", 
      [$this, "cloudapper_admin_form_detail_view"]
    );
  }

  function cloudapper_admin_dashboard_view() {
    require_once CA_FEEDER_DIR_PATH . "/views/dashboard.php";
  }
  
  function cloudapper_admin_add_employee() {
    require_once CA_FEEDER_DIR_PATH . "/views/add-employee.php";
  }

  function cloudapper_admin_get_token() {
    require_once CA_FEEDER_DIR_PATH . "/views/token.php";
  }

  function cloudapper_admin_settings_view() {
    require_once CA_FEEDER_DIR_PATH . "/views/settings.php";
  }
  
  function cloudapper_admin_app_detail_view() {
    require_once CA_FEEDER_DIR_PATH . "/views/app-detail.php";
  }
  
  function cloudapper_admin_form_detail_view() {
    require_once CA_FEEDER_DIR_PATH . "/views/form-detail.php";
  }
}