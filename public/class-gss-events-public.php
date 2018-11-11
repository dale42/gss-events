<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://group42.ca
 * @since      0.1.0
 *
 * @package    Gss_Events
 * @subpackage Gss_Events/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Gss_Events
 * @subpackage Gss_Events/public
 * @author     Dale McGladdery <dale.mcgladdery@gmail.com>
 */
class Gss_Events_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/gss-events-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/gss-events-public.js', array( 'jquery' ), $this->version, false );

	}

  /**
   * Function for the gss_events_teaser_list shortcode.
   *
   * @since    0.1.2
   */
  public static function shortcode_gss_events_teaser_list( $attributes ) {

      $output = '';

      // Get the event list
      $gss_url = get_option('gss_events_source_url', '');
      if ($gss_url) {
        try {
          $spreadsheet = new Gss_Events_Reader($gss_url);
          $event_list = $spreadsheet->fetch_events();
        }
        catch (Exception $e) {
          return $output;
        }
      }
      else {
        return $output;
      }

      // Format the events and output
      $output = '<h2>Events</h2>';
      foreach ($event_list as $event) {
        ob_start();
        include( plugin_dir_path( __FILE__ ) . '/templates/event-item-teaser.php' );
        $output .= ob_get_clean();
      }

      return $output;

  }

  /**
   * Function for the gss_events_full_list shortcode.
   *
   * @since    0.1.2
   */
  public static function shortcode_gss_events_full_list( $attributes ) {

    $output = '';

    // Get the event list
    $gss_url = get_option('gss_events_source_url', '');
    if ($gss_url) {
      try {
        $spreadsheet = new Gss_Events_Reader($gss_url);
        $event_list = $spreadsheet->fetch_events();
      }
      catch (Exception $e) {
        return $output;
      }
    }
    else {
      return $output;
    }

    // Format the events and output
    $output = '';
    foreach ($event_list as $event) {
      ob_start();
      include( plugin_dir_path( __FILE__ ) . '/templates/event-item-card.php' );
      $output .= ob_get_clean();
    }

    return $output;

  }

}
