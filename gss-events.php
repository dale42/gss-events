<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://group42.ca
 * @since             0.1.0
 * @package           Gss_Events
 *
 * @wordpress-plugin
 * Plugin Name:       Google Spreadsheet Events
 * Plugin URI:        https://github.com/dale42/gss-events
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           0.1.0+Sprint1
 * Author:            Dale McGladdery
 * Author URI:        https://group42.ca
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gss-events
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 0.1.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '0.1.1' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-gss-events-activator.php
 */
function activate_gss_events() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gss-events-activator.php';
	Gss_Events_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-gss-events-deactivator.php
 */
function deactivate_gss_events() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-gss-events-deactivator.php';
	Gss_Events_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_gss_events' );
register_deactivation_hook( __FILE__, 'deactivate_gss_events' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-gss-events.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function run_gss_events() {

	$plugin = new Gss_Events();
	$plugin->run();

}
run_gss_events();
