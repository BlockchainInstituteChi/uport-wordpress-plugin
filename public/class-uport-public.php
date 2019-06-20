<?php

/**
 * The public-facing functionality of the uPort plugin.
 *
 * @link       http://uport.me/
 * @since      1.0.0
 *
 * @package    uPort
 * @subpackage uPort/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * @package    uPort
 * @subpackage uPort/public
 * @author     uPort <support@uport.me>
 */
class Uport_Public {

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
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/uport-public.css', array(), $this->version, 'all' );

	}

	public function login_scripts () {
		wp_register_script( 'jquery', plugin_dir_url( __FILE__ ) . 'js/jquery.js' );
		wp_register_script( 'uport-js', plugin_dir_url( __FILE__ ) . 'js/uport-js.js' );
		wp_register_script( 'qrcode', plugin_dir_url( __FILE__ ) . 'js/qrcode.js' );
		// wp_register_script( 'test', plugin_dir_url( __FILE__ ) . 'js/test.js' );

		wp_enqueue_script('jquery');
		wp_enqueue_script('uport-js');
		wp_enqueue_script('qrcode');
		// wp_enqueue_script('test', array('jquery'), '1.0', true );
	}

	public function login_styles () {
		wp_register_style( 'uport-js', plugin_dir_url( __FILE__ ) . 'css/uport-login.css' );

		wp_enqueue_style('uport-js');
	}
	
	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		// This was put here by raininja but doesn't seem to be necessary
		// wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/uport-public.js', array( 'jquery' ), $this->version, false );
	}

}
