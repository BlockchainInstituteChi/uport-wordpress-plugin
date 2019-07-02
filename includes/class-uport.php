<?php

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

if ( !class_exists('Uport') ) {
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
	 * verify_disclosure_response 
	 *
	 * POST Endpoint that accepts a valid JWT with a disclosure response for email and name
	 *
	 */

	public function verify_disclosure_response () {
 		
 	// 	function getUserBy( $user ) {

		// 	// if the user is logged in, pass curent user
		// 	if( is_user_logged_in() )
		// 		return wp_get_current_user();

		// 	$user_data = get_user_by('email', $user['user_email']);

		// 	if( ! $user_data ) {
		// 		$users     = get_users(
		// 			array(
		// 				'meta_key'    => '_uport_mnid',
		// 				'meta_value'  => $user['uport_mnid'],
		// 				'number'      => 1,
		// 				'count_total' => false
		// 			)
		// 		);
		// 		if( is_array( $users ) ) $user_data = reset( $users );
		// 	}
		// 	return $user_data;
		// }

		// function login_or_register_user ( $name, $email, $mnid ) {

		// 	$user = [
		// 		'user_email' => $email,
		// 		'uport_mnid' => $mnid,
		// 		'uport_name' => $name,
		// 	];

		// 	$user_obj = getUserBy( $user );

		// 	$meta_updated = false;

		// 	if ( $user_obj ){
		// 		$user_id = $user_obj->ID;
		// 		$status = array( 'success' => $user_id, 'method' => 'login');
		// 		// check if user email exist or update accordingly
		// 		if( empty( $user_obj->user_email ) )
		// 			wp_update_user( array( 'ID' => $user_id, 'user_email' => $user['user_email'] ) );

		// 	} else {
		// 		if( ! get_option('users_can_register') || apply_filters( 'fbl/registration_disabled', false ) ) {
		// 			// if( ! apply_filters( 'fbl/bypass_registration_disabled', false ) )
		// 			// $this->ajax_response( array( 'error' => __( 'User registration is disabled', 'fbl' ) ) );
		// 		}
		// 		// generate a new username
		// 		$user['user_login'] = $user['uport_name'] . "_" . $user['uport_mnid'];

		// 		// $user_id = $this->register_user( apply_filters( 'fbl/user_data_register',$user ) );

		// 		if( !is_wp_error( $user_id ) ) {
		// 			// $this->notify_new_registration( $user_id );
		// 			update_user_meta( $user_id, '_uport_mnid', $user['uport_mnid'] );
		// 			$meta_updated = true;
		// 			$status = array( 'success' => $user_id, 'method' => 'registration' );
		// 		}
		// 	}
		// 	if( is_numeric( $user_id ) ) {
		// 		wp_set_auth_cookie( $user_id, true );
		// 		if( !$meta_updated )
		// 			update_user_meta( $user_id, '_uport_mnid', $user['uport_mnid'] );
		// 			// do_action( 'fbl/after_login', $user, $user_id);
		// 	}
		// 	echo "{success: true}";		

		// }

 	// 	function login_with_uport ($payload) {
 	// 		error_log('Received valid payload: ');
		// 	error_log(print_r($payload, TRUE));

		// 	if( empty( $payload['email'] ) ) { 

		// 		error_log( 'email: ' );
		// 		error_log( $payload['email'] );
		// 		echo "{'error':'no email provided','errcode':'2'}";
				

		// 	} else {
		// 		// $user = login_or_register_user( $payload['name'], $payload['email'], $payload['mnid']);
		// 		// error_log(json_encode($user));
		// 		return login_or_register_user( $payload['name'], $payload['email'], $payload['mnid'] );

		// 	}
		// 	error_log('made it to the end of the login function. Email was: '. $payload['email'] );
		// }

		// $jwt = $_POST['disclosureResponse'];

		// $jwtTools = new jwtTools(null);

		// error_log('jwt received ' . $jwt);

		// $plainText = json_decode(base64_decode( urldecode( ( $jwtTools->deconstruct_and_decode( $jwt ) )['body'] ) ));

		// error_log(print_r($plainText, TRUE));

		// $isVerified = $jwtTools->verify_JWT($jwt);

		// // Check the response to see if it's valid
		// if ( 1 == $jwtTools->verify_JWT($jwt) ) {
		// 	// Jwt signature is valid
		// 	$payload = [
		// 		'name'  => $plainText->own->name,
		// 		'email' => $plainText->own->email,
		// 		'mnid'  => $plainText->nad,
		// 	];

		// 	// $this->login_with_uport($payload);
		// 	login_with_uport($payload);

		// } else {

		// 	echo "{'success':false;'error':'imvalid jwt';}"; 

		// }



	}

	// *
	//  * login_with_uport 
	//  *
	//  * @param $payload Payload receives a valid jwt payload with a name and email index which should be able to be accessed as $payload['name'] and $payload['email']
	//  *
	 

	// private static function login_with_uport ($payload) {
	// 	error_log(print_r($payload, TRUE));




	// }

	/**
	 * generate_disclosure_request 
	 *
	 * POST Endpoint that accepts a valid JWT with a disclosure response for email and name
	 *
	 */

	public static function generate_disclosure_request () {
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
		$signingKey  = 'cb89a98b53eec9dc58213e67d04338350e7c15a7f7643468d8081ad2c5ce5480'; 

		$topicUrl = 'https://chasqui.uport.me/api/v1/topic/' . generate_string();

		$time = time();
		$jwtBody->iss         = '2ojEtUXBK2J75eCBazz4tncEWE18oFWrnfJ';
		$jwtBody->iat 	      = $time;

		$jwtBody->requested   = ['name','email'];
		$jwtBody->callback    = $topicUrl;
		$jwtBody->net      	  = "0x4";
		$jwtBody->exp 	      = $time + 600;
		$jwtBody->type 		  = "shareReq";

		// 2. Create JWT Object
		$jwtBodyJson = json_encode($jwtBody, JSON_UNESCAPED_SLASHES);

		$jwt = $jwtTools->create_JWT($jwtHeaderJson, $jwtBodyJson, $signingKey);

		$payload = [];
		$payload["jwt"] = $jwt;
		$payload["topic"] = $topicUrl;	
		error_log('jwt');
		error_log($jwt);
		echo json_encode($payload);

		die();

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
		add_action( 'wp_ajax_nopriv_generate_disclosure_request', array(__CLASS__, 'generate_disclosure_request' ));
		add_action( 'wp_ajax_nopriv_verify_disclosure_response', array(__CLASS__, 'verify_disclosure_response' ));
		// error_log('set disclosure request action');


		function generate_string() {
			$strength = 16;
			$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		    $input_length = strlen($permitted_chars);
		    $random_string = '';
		    for($i = 0; $i < $strength; $i++) {
		        $random_character = $permitted_chars[mt_rand(0, $input_length - 1)];
		        $random_string .= $random_character;
		    }
		    return $random_string;
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
}