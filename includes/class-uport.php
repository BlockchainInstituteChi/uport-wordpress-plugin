<?php

require plugin_dir_path( dirname( __FILE__ ) ) . '/vendor/autoload.php';
require plugin_dir_path( dirname( __FILE__ ) ) . '/vendor/autoload.php';

use Blockchaininstitute\jwtTools as jwtTools;

/**
 * The file that defines the core uPort plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://uport.me/
 * @since      1.0.0
 *
 * @package    uPort
 * @subpackage uPort/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define public-facing site hooks, admin-specific hooks, and
 * internationalization.
 *
 * Also maintains the unique identifier of the plugin as well as the current
 * version.
 *
 * @since      1.0.0
 * @package    uPort
 * @subpackage uPort/includes
 * @author     uPort <support@uport.me>
 */

class Uport {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Uport_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'UPORT_VERSION' ) ) {
			$this->version = UPORT_VERSION;
		}
		$this->plugin_name = 'uport';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for uPort.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Uport_Loader. Orchestrates the hooks of the plugin.
	 * - Uport_i18n. Defines internationalization functionality.
	 * - Uport_Admin. Defines all hooks for the admin area.
	 * - Uport_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-uport-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-uport-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-uport-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-uport-public.php';

		$this->loader = new Uport_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Uport_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Uport_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Uport_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */



	private function define_public_hooks() {

		$plugin_public = new Uport_Public( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'login_enqueue_scripts', $plugin_public, 'login_scripts', 1);
		$this->loader->add_action( 'login_enqueue_scripts', $plugin_public, 'login_styles', 10);

		// This probably shouldn't live here, but it's going to have to for now because nothing else works
		add_action( 'wp_ajax_nopriv_test', 'generateDisclosureRequest' );

		function generateDisclosureRequest () {
			$jwtTools = new jwtTools(null);

			// Prepare the JWT Header
			// 1. Initialize JWT Values
			$jwtHeader = (object)[];
			$jwtHeader->typ = 'JWT'; // ""
			$jwtHeader->alg = 'ES256K'; // ""

			// 2. Create JWT Object
			$jwtHeaderJson = json_encode($jwtHeader, JSON_UNESCAPED_SLASHES);


			// Prepare the JWT Body
			// 1. Initialize JWT Values
			$jwtBody = (object)[];

			 // "Client ID"
			$signingKey  = 'cb89a98b53eec9dc58213e67d04338350e7c15a7f7643468d8081ad2c5ce5480'; // "Private Key"
			// 776e591d9674b1c0fc8182f8574f24734cdeb4dc7ef8c4643d0fda33f4f8e0d6

			$jwtBody->iat 	      = 1556912833;
			$jwtBody->requested   = ['name'];
			$jwtBody->callback    = 'https://chasqui.uport.me/api/v1/topic/1OzSjQRFrF948LLk';
			$jwtBody->net      	  = "0x4";
			$jwtBody->type 		  = "shareReq";
			$jwtBody->iss         = '2ojEtUXBK2J75eCBazz4tncEWE18oFWrnfJ';

			// 2. Create JWT Object
			$jwtBodyJson = json_encode($jwtBody, JSON_UNESCAPED_SLASHES);

			$jwt = $jwtTools->createJWT($jwtHeaderJson, $jwtBodyJson, $signingKey);
			echo $jwt;
			die();
		}
		
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		$this->loader->run();

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Uport_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
