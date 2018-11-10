<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://group42.ca
 * @since      0.1.0
 *
 * @package    Gss_Events
 * @subpackage Gss_Events/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Gss_Events
 * @subpackage Gss_Events/admin
 * @author     Dale McGladdery <dale.mcgladdery@gmail.com>
 */
class Gss_Events_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

  /**
   * A utility function that formats GSS Reader previews.
   *
   * @since    0.1.0
   * @access   private
   * @param    array                $preview_data      The array returned by Gss_Events_Reader->fetch_preview_data().
   * @return   string                                  A formatted string of the preview data.
   */
  private function format_gss_preview_data( $preview_data ) {
    $output = '<h2>Preview</h2>';

    if ( !empty($preview_data['raw']) ) {
      $output .= '<p>The Google Spreadsheet could not be parsed.</p>';
      $output .= '<p>Please confirm the required columns are present and the spreadsheet URL is using the csv format.</p>';
      $output .= '<p>First five lines of the document:</p>';
      $output .= '<ol><li>' . implode('</li><li>', $preview_data['raw']) . '</li></ol>';
    }
    else {
      $format_table = function ($row_data) {
        $table = '<table>';
        foreach ($row_data as $row) {
          $cells = str_getcsv($row);
          $table .= '<tr><td>' . implode('</td><td>', $cells) . '</td></tr>';
        }
        $table .= '</table>';
        return $table;
      };
      $output .= '<h3>Preamble</h3>' . $format_table($preview_data['preamble']);
      $output .= '<h3>Header</h3>' . '<table><tr><td>' . implode('</td><td>', $preview_data['header']) . '</td></tr></table>';
      $output .= '<h3>Content</h3>' . $format_table($preview_data['content']);
    }

    return $output;

  }

    /**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Gss_Events_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Gss_Events_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/gss-events-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Gss_Events_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Gss_Events_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/gss-events-admin.js', array( 'jquery' ), $this->version, false );

	}

  /**
   * Add administration menu configuration page.
   */
  public function add_config_page(){
    $display_function = function () {
      if (!current_user_can('manage_options')) {
        wp_die('Unauthorized user');
      }

      // Initialize some variables
      $spreadsheet    = NULL;
      $admin_messages = [];
      $message_area   = '';
      $sample_content = '';

      // Process form submission
      if ( isset($_POST['gss_url']) ) {
        if ( empty($_POST['config_hash']) || !wp_verify_nonce($_POST['config_hash'], 'gss_events_config_nonce_action') ) {
          wp_die('Configuration submission error');
        }
        if ( filter_var($_POST['gss_url'], FILTER_VALIDATE_URL) ) {
          $new_gss_url = filter_var($_POST['gss_url'], FILTER_SANITIZE_URL);
          update_option('gss_events_source_url', $new_gss_url);
        }
        else {
          $admin_messages[] = 'Invalid URL';
        }
      }

      // Get current value and display config page
      $gss_url = get_option('gss_events_source_url', '');
      if ($gss_url) {
        try {
          $spreadsheet = new Gss_Events_Reader($gss_url);
          $preview_data = $spreadsheet->fetch_preview_data();
          $sample_content = $this->format_gss_preview_data($preview_data);
        }
        catch (Exception $e) {
          $admin_messages[] = $e->getMessage();
        }
      }

      if ($admin_messages) {
        $admin_messages = array_map(function($message) {
          return '<li>' . $message . '</li>';
        }, $admin_messages);
        $message_area = '<ul>' . implode('', $admin_messages) . '</ul>';
      }
      include plugin_dir_path( dirname( __FILE__ ) ) . 'admin/templates/gss-events-admin-display.php';
    };
    add_menu_page( 'GSS Events Configuration', 'GSS Events', 'manage_options', 'gss-events', $display_function );
  }

}
