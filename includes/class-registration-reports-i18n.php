<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.adventureclub.io/
 * @since      1.0.0
 *
 * @package    Registration_Reports
 * @subpackage Registration_Reports/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Registration_Reports
 * @subpackage Registration_Reports/includes
 * @author     Mikko Heikkilä <mikko.heikkila@adventureclub.io>
 */
class Registration_Reports_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'registration-reports',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
