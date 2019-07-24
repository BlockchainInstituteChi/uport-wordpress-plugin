<?php

require plugin_dir_path( dirname( __FILE__ ) ) . '/vendor/autoload.php';

use Blockchaininstitute\jwtTools as jwt_tools;


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
	 * decrypt_stored_key
	 *
	 * decryptes a key using a stored encryption key
	 *	 
	 * @return string Returns false or a decrypted signing key
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function decrypt_stored_key ( $encrypted ) {

		$uport     = new Uport ();
		$key       = $uport->get_or_create_encryption_key();

		if ( is_wp_error($key) ) {
			return false;
		} else {
			$cipher    = "aes-128-ctr";

			return openssl_decrypt($encrypted, $cipher, $key, OPENSSL_ZERO_PADDING, str_pad( $cipher, 16 ) );
		}

	}

	/**
	 * encrypt_and_store_key
	 *
	 * encrypts a signing key using a stored encryption key
	 *	 
	 * @return string Returns returns the encrypted signing key
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	public function encrypt_and_store_key ( $plaintext ) {
		
		$uport     = new Uport ();
		$key       = $uport->get_or_create_encryption_key();
		$cipher    = "aes-128-ctr";

		return openssl_encrypt( $plaintext, $cipher, $key, OPENSSL_ZERO_PADDING, str_pad( $cipher, 16 ) );

	}

	/**
	 * get_or_create_encryption_key
	 *
	 * gets or creates an encryption key
	 *	 
	 * @return string Returns the key or creates it and returns that
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function get_or_create_encryption_key () {

		global $wp_filesystem;

		// protect if the the global filesystem isn't setup yet
		if( is_null( $wp_filesystem ) )
		    WP_Filesystem();

		$uport    = new Uport ();
	    $filename = plugin_dir_path( __FILE__ ) . 'key.txt';

	    if ( file_exists( $filename ) ) {

			return $wp_filesystem->get_contents( $filename );

		} else {

			return $uport->create_new_key();

		}

	}

	/**
	 * connect_fs 
	 *
	 * initializes or verifies the filesystem access
	 *
	 * @param string $url should contain the url for the fs call
	 *
	 * @param string $method should contain the filesystem method
	 *	 
	 * @param string $context should contain the permission context for the filesystem interaction
	 *	 	 
	 * @param string $fields contains a list of applicable form field options
	 *	 	 	 
	 * @return string Returns the user object or creates it
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function connect_fs($url, $method, $context, $fields = null){
		global $wp_filesystem;

		if(false === ($credentials = request_filesystem_credentials($url, $method, false, $context, $fields))) {
			return false;
		}

		//check if credentials are correct or not.
		if(!WP_Filesystem($credentials)) {
			request_filesystem_credentials($url, $method, true, $context);
			return false;
		}

		return true;
	}

	/**
	 * create_new_key 
	 *
	 * generates a new key file and populates it with a new 64 bit key. 
	 * returns a WP error on failure 
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function create_new_key () {
		global $wp_filesystem;
		$uport    = new Uport ();

		$url = wp_nonce_url("options-general.php?page=demo", "filesystem-nonce");
		$form_fields = array("file-data");

		if( $uport->connect_fs($url, "", plugin_dir_url( dirname( __FILE__ ) ), $form_fields ) ){

			$key      = openssl_random_pseudo_bytes( 64 );
			$dir      = $wp_filesystem->find_folder( plugin_dir_url( dirname( __FILE__ ) ) );
			$file     = trailingslashit($dir) . "key.txt";
			$wp_filesystem->put_contents($file, $key, FS_CHMOD_FILE);

		    return $key;

		} else {

			return new WP_Error("filesystem_error", "Cannot initialize filesystem");

		}



	}


	/**
	 * decrypt_signing_key 
	 *
	 * returns the decrypted signing key from the wordpress options db or returns an error 
	 *
	 * @since    1.0.0
	 * @access   private
	 */

	public static function decrypt_signing_key () {

		$uport           = new Uport ();
		$uport_options   = get_option( 'uport' );
		$encrypted_key   = $uport_options['uport-key'];

		return $uport->decrypt_stored_key( $encrypted_key );

	}

	/**
	 * generate_disclosure_request 
	 *
	 * POST endpoint that returns a signed JWT using the credentials from the uport-wordpress admin setting. Returns an invalid JWT if the settings for the plugin are not configured.
	 *
	 * @since    1.0.0
	 * @access   private
	 */

	public static function generate_disclosure_request () {

		$uport           = new Uport ();
		$jwt_tools       = new jwt_tools( null );
		$uport_options   = get_option( 'uport' );
		$time            = time();
		$jwt_header      = ( object )[];
		$jwt_header->typ = 'JWT'; 
		$jwt_header->alg = 'ES256K'; 

		$jwt_body = ( object )[];

		if ( isset( $uport_options['uport-key'] ) && !empty( $uport_options['uport-key'] )) {
			
			$signing_key  = $uport->decrypt_signing_key();

		} else {
			
			// if not configured in settings, defaults to an invalid signing key, which will send an error code to the front end
			$signing_key = "";

		}

		$random_address_string = $uport->generate_random_string();

		$topic_url = 'https://chasqui.uport.me/api/v1/topic/' . $random_address_string;

		$jwt_body->iss  = "";

		if ( isset( $uport_options['uport-mnid'] ) && !empty( $uport_options['uport-key'] ) ) $jwt_body->iss         =  $uport_options['uport-mnid'];

		if ( isset( $uport_options['uport-network'] ) && !empty( $uport_options['uport-key'] ) ) {
			$network         = $uport_options['uport-network'];
		} else {
			// if no network is set, use mainnet
			$network         = "0x1";
		}

		$jwt_body->iat         = $time;
		$jwt_body->requested   = ['name','email'];
		$jwt_body->callback    = $topic_url;
		$jwt_body->net         = $network;
		$jwt_body->exp         = $time + 600;
		$jwt_body->type        = "shareReq";

		$jwt         = $jwt_tools->create_JWT( json_encode( $jwt_header, JSON_UNESCAPED_SLASHES ), json_encode( $jwt_body, JSON_UNESCAPED_SLASHES ), $signing_key );

		$payload     = [
			"jwt"   => $jwt,
			"topic" => $topic_url,
		];

		echo json_encode( $payload );

		wp_die();

	}


	/**
	 * verify_disclosure_response 
	 *
	 * POST Endpoint that accepts a valid JWT with a disclosure response for email and name
	 *
	 */
	public static function verify_disclosure_response () {

		$jwt = $_POST['disclosureResponse'];

		$jwt_tools 	= new jwt_tools( null );
		$uport 		= new Uport();

		$plain_text = json_decode( base64_decode( urldecode( ( $jwt_tools->deconstruct_and_decode( $jwt ) )['body'] ) ) );

		if ( 1 == $jwt_tools->verify_JWT( $jwt ) ) {

			$payload = [
				'name'  => $plain_text->own->name,
				'email' => $plain_text->own->email,
				'mnid'  => $plain_text->nad,
			];

			$uport->login_with_uport( $payload );

		} else {

			echo "{'success':false;'error':'imvalid jwt';}"; 

		}

	}

	/** 
	 * get_user_by 
	 *
	 * @param stdclass object containing the keys mnid, email, and name
	 *
	 * @return returns a valid user object if it exists
	 *
	 * @since    1.0.0
	 * @access   private	 
	 */
	private function get_user_by( $user ) {

		if( is_user_logged_in() )
			return wp_get_current_user();

		$user_data = get_user_by('email', $user['user_email']);

		if( ! $user_data ) {
			$users     = get_users(
				array(
					'meta_key'    => '_uport_mnid',
					'meta_value'  => $user['uport_mnid'],
					'number'      => 1,
					'count_total' => false
				)
			);
			if( is_array( $users ) ) $user_data = reset( $users );
		}
		return $user_data;
	}


	/**
	 * login_or_register_user 
	 *
	 * handles user signin with uport credentials
	 *
	 * @param string $name should contain a string formatted nice name for the user
	 *
	 * @param string $email should contain an email to look up the account by
	 *	 
	 * @param string $mnid should contain an account mnid which will be used to generate a new username if necessary
	 *	 	 
	 * @return string Returns the user object or creates it
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function login_or_register_user ( $name, $email, $mnid ) {
		
		$user         = [
			'user_email' => $email,
			'uport_mnid' => $mnid,
			'uport_name' => $name,
		];

		$uport_options  = get_option( 'uport' );
		$user_obj       = $this->get_user_by( $user );
		$meta_updated   = false;

		if ( $user_obj ){

			$user_id = $user_obj->ID;
			$status  = array( 'success' => $user_id, 'method' => 'login' );
			
			if( empty( $user_obj->user_email ) )
				wp_update_user( array( 'ID' => $user_id, 'user_email' => $user['user_email'] ) );
				update_user_meta( $user_id, '_uport_mnid', $user['uport_mnid'] );
		
		} else {

			$user['user_login'] = $user['uport_name'] . "_" . $user['uport_mnid'];
			$newUser            = [
				'user_login'   => $user['user_login'],
				'user_pass'    => bin2hex( openssl_random_pseudo_bytes( 10 ) ),
				'nickname'     => $user['user_login'],
				'display_name' => $user['uport_name'],
				'email'        => $user['user_email'],
				'role'         => 'subscriber',
			];

			$user_id = wp_insert_user( $newUser );

			if( !is_wp_error( $user_id ) ) {

				update_user_meta( $user_id, '_uport_mnid', $user['uport_mnid'] );
				$meta_updated = true;
				$status       = array( 'success' => $user_id, 'method' => 'registration' );

			}

		}

		if( is_numeric( $user_id ) ) {

			wp_set_auth_cookie( $user_id, true );

			if( !$meta_updated ) {
				update_user_meta( $user_id, '_uport_mnid', $user['uport_mnid'] );
			}

			if( ( !isset( $uport_options['uport-login-url'] ) ) || ( "" === $uport_options['uport-login-url'] ) ) {
				$redirect_url = get_home_url();
			} else {
				$redirect_url = $uport_options['uport-login-url'];
			}

			$successPayload = [
				'success'  => true,
				'redirect' => $redirect_url,
			];

			wp_send_json( $successPayload );

			wp_die();

		} else {

			$failure_payload = [
				'success' => false,
				'user'    => $user,
				'newUser' => $newUser,
			];
			wp_send_json( $failure_payload );
			wp_die();

		}

	}


	/**
	 * login_with_uport 
	 *
	 * verifies uport credentials and handles login
	 *	 
	 * @param array $payload should contain a uport login payload generated from verify_disclosure_response
	 *	 	 
	 * @return string Returns the user object or creates it
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function login_with_uport ($payload) {

		if( empty( $payload['email'] ) ) { 

			echo "{'error':'no email provided','errcode':'2'}";
			
		} else {

			return $this->login_or_register_user( $payload['name'], $payload['email'], $payload['mnid'] );

		}

	}

	/**
	 * generate_random_string
	 *
	 * generates a random string for chasqui topic generation
	 *	 	 
	 * @return string Returns the random string
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function generate_random_string( $strength = 16 ) {
		$permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $input_length = strlen( $permitted_chars );
	    $random_string = '';
	    for($i = 0; $i < $strength; $i++) {
	        $random_character = $permitted_chars[mt_rand( 0, $input_length - 1 )];
	        $random_string    .= $random_character;
	    }
	    return $random_string;
	}

	/**
	 * Define the locale for this plugin for internationalization.
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
	 * @param string $private_key_string Passes a string which will be used as the private key to sign the payload
	 *
	 * @return string Returns the base 64 encoded and trimmed JWT with a signature generated using the given private key string
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Uport_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Add menu item
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		 
		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );
		$this->loader->add_action('admin_init', $plugin_admin, 'options_update');
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