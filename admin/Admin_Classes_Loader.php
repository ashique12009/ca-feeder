<?php 
namespace ca_feeder\admin;

/**
 * All admin classes will be loaded here
 */
class Admin_Classes_Loader {
  /**
   * Class constructor
   */
  function __construct() {
    require_once CA_FEEDER_DIR_PATH . '/admin/Ca_Feeder_Installer.php';
    new Ca_Feeder_Installer();
    require_once CA_FEEDER_DIR_PATH . '/admin/Menu.php';
    new Menu();
    require_once CA_FEEDER_DIR_PATH . '/admin/Assets.php';
    new Assets();
    require_once CA_FEEDER_DIR_PATH . '/admin/Ajax_Activity.php';
    new Ajax_Activity();
    require_once CA_FEEDER_DIR_PATH . '/admin/Form_Handler.php';
    new Form_Handler();
    require_once CA_FEEDER_DIR_PATH . '/admin/Settings.php';
    new Settings();
  }
}