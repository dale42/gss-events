<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://group42.ca
 * @since      0.1.0
 *
 * @package    Gss_Events
 * @subpackage Gss_Events/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.1.0
 * @package    Gss_Events
 * @subpackage Gss_Events/includes
 * @author     Dale McGladdery <dale.mcgladdery@gmail.com>
 */
class Gss_Events_Deactivator {

	/**
	 * Plugin deactivation cleanup.
	 *
	 * @since    0.1.0
	 */
	public static function deactivate() {
    delete_option('gss_events_source_url');
	}

}
