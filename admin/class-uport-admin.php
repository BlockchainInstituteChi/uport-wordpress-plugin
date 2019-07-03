<?php

/**
 * The admin-specific functionality of the uPort plugin.
 *
 * @link       http://uport.me/
 * @since      1.0.0
 *
 * @package    uPort
 * @subpackage uPort/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    uPort
 * @subpackage uPort/admin
 * @author     uPort <support@uport.me>
 */
class Uport_Admin {

	/**
	 * The ID of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of the plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/uport-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/uport-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	* Register the administration menu for this plugin into the WordPress Dashboard menu.
	*
	* @since 1.0.0
	*/
	 
	public function add_plugin_admin_menu() {
		add_options_page( 'Uport Options Settings', 'Uport Login', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page'));
	}
	 
	/**
	* Add settings action link to the plugins page.
	*
	* @since 1.0.0
	*/

	public function options_update() {
		error_log('options update ran');

	    register_setting($this->plugin_name, $this->plugin_name, array($this, 'validate'));
	}

	 
	public function add_action_links( $links ) {
		$settings_link = array(
			'<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __('Settings', $this->plugin_name) . '</a>',
		);
		return array_merge( $settings_link, $links );
	}


	// This isn't finished yet. Need to add text field validation for the key and mnid
	public function validate($input) {

		error_log('validate ran');

	    $valid = array();
	 
	    if (isset($input['uport-mnid']) && !empty($input['uport-mnid'])) $valid['uport-mnid'] = $input['uport-mnid'];
	    if (isset($input['uport-key']) && !empty($input['uport-key'])) $valid['uport-key'] = $input['uport-key'];

	    return $valid;
	}
	 
	/**
	* Render the settings page for this plugin.
	*
	* @since 1.0.0
	*/
	 
	public function display_plugin_setup_page() {
		include_once( 'partials/uport-admin-display.php' );
	}

}
