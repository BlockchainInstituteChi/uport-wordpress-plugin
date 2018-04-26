<?php

/**
 * Define the internationalization functionality of uPort
 *
 * Loads and defines the internationalization files
 * so that uPort is ready for translation.
 *
 * @link       http://uport.me/
 * @since      1.0.0
 *
 * @package    uPort
 * @subpackage uPort/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files
 * so that uPort is ready for translation.
 *
 * @since      1.0.0
 * @package    uPort
 * @subpackage uPort/includes
 * @author     uPort <support@uport.me>
 */
class Uport_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'uport',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
