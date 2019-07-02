<?php

/**
 * The uPort plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://uport.me/
 * @since             1.0.0
 * @package           uPort
 *
 * @wordpress-plugin
 * Plugin Name:       uPort
 * Plugin URI:        http://uport.me/
 * Description:       This is a short description of what the uPort plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            uPort
 * Author URI:        http://uport.me/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       uport
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

ob_clean();
ob_start();

/**
 * Current plugin version.
 * Update this as you release new versions.
 */
define( 'UPORT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-uport-activator.php
 */
function activate_uport() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-uport-activator.php';
	Uport_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-uport-deactivator.php
 */
function deactivate_uport() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-uport-deactivator.php';
	Uport_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_uport' );
register_deactivation_hook( __FILE__, 'deactivate_uport' );


/**
 * The core uPort plugin class that is used to define public-facing site hooks, admin-specific hooks,
 * and internationalization.
 */




require plugin_dir_path( __FILE__ ) . 'includes/class-uport.php';



/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_uport() {

	$plugin = new Uport();
	$plugin->run();

}
run_uport();
