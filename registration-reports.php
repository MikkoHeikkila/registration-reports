<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.adventureclub.io/
 * @since             1.0.0
 * @package           Registration_Reports
 *
 * @wordpress-plugin
 * Plugin Name:       Registration Reports
 * Plugin URI:        https://www.adventureclub.io/
 * Description:       Report subscriber registrations or registration anniversaries within a date range.
 * Version:           1.0.0
 * Author:            Mikko Heikkilä
 * Author URI:        https://www.adventureclub.io//
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       registration-reports
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'REGISTRATION_REPORTS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-registration-reports-activator.php
 */
function activate_registration_reports() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-registration-reports-activator.php';
	Registration_Reports_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-registration-reports-deactivator.php
 */
function deactivate_registration_reports() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-registration-reports-deactivator.php';
	Registration_Reports_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_registration_reports' );
register_deactivation_hook( __FILE__, 'deactivate_registration_reports' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-registration-reports.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_registration_reports() {

	$plugin = new Registration_Reports();
	$plugin->run();

}
run_registration_reports();
