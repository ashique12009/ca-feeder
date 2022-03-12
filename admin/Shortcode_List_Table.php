<?php
namespace ca_feeder\admin;

class Shortcode_List_Table extends \WP_List_Table {

  /** Text displayed when no customer data is available */
  public function no_items() {
    echo 'No audience avaliable.';
  }

  public function get_columns() {
    $columns = array(
      'cb'          => '<input type="checkbox" />',
      'shortcode'   => 'Shortcode',
      'fields'      => 'Fields',
      'app_name'    => 'App Name',
      'form_name'   => 'Form Name',
    );
    return $columns;
  }

  public function column_default($item, $column_name) {
    switch ($column_name) {
    case 'id':
      return $item['id'];
    case 'shortcode':
      return '[' . $item['shortcode'] . ']';
    case 'fields':
      return $item['fields'];
    case 'app_name':
      return $item['app_name'];
    case 'form_name':
      return $item['form_name'];
    default:
    }
    return $item[$column_name];
  }

  public function get_bulk_actions() {
    $actions = array(
      'bulk-delete' => 'Delete',
    );
    return $actions;
  }

  public function delete_shorcode($id) {
    global $wpdb;

    $wpdb->delete(
      "{$wpdb->prefix}ca_shortcodes",
      ['id' => $id],
      ['%d']
    );
  }

  public function process_bulk_action() {
    // If the delete bulk action is triggered
    if ((isset($_REQUEST['action']) && $_REQUEST['action'] == 'bulk-delete') || (isset($_REQUEST['action2']) && $_REQUEST['action2'] == 'bulk-delete')) {

      $delete_ids = esc_sql($_REQUEST['id']);

      // loop over the array of record IDs and delete them
      foreach ($delete_ids as $id) {
        $this->delete_shorcode($id);
      }
    }
  }

  public function column_cb($item) {
    return sprintf(
      '<input type="checkbox" name="id[]" value="%s" />', $item['id']
    );
  }

  public function prepare_items() {
    $columns = $this->get_columns();
    $hidden = array();
    $sortable = array();
    $this->_column_headers = array($columns, $hidden, $sortable);
    /** Process bulk action */
    $this->process_bulk_action();

    // GET template-filter value as template_id
    $filter_template_id = isset($_REQUEST['template-filter']) ? $_REQUEST['template-filter'] : '';

    $per_page = 25;

    $current_page = $this->get_pagenum();
    $offset = ($current_page - 1) * $per_page;

    $args = array(
      'offset'             => $offset,
      'number'             => $per_page,
      'filter_template_id' => $filter_template_id,
    );

    $this->items = getShortcodeRow($args);
    $total_items = getTotalShortcodeRows($args);

    $this->set_pagination_args(array(
      'total_items' => $total_items, //WE have to calculate the total number of items
      'per_page'    => $per_page, //WE have to determine how many items to show on a page
    ));
  }
}