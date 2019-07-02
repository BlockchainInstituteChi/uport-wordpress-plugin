<?php

class uport_login () {
	public function login_or_register_user() {
		check_ajax_referer( 'facebook-nonce', 'security' );

		$access_token = isset( $_POST['fb_response']['authResponse']['accessToken'] ) ? $_POST['fb_response']['authResponse']['accessToken'] : '';
		$fb_user_id = $_POST['fb_response']['authResponse']['userID'];
		// Get user from Facebook with given access token
		$fb_url = add_query_arg(
			apply_filters( 'fbl/js_auth_data',
				array(
					'fields'            =>  'id,first_name,last_name,email',
					'access_token'      =>  $access_token,
				)
			),
			apply_filters( 'fbl/fb_api_url','https://graph.facebook.com/v2.10/'.$fb_user_id, $fb_user_id )
		);
		//
		if( !empty( $this->opts['fb_app_secret'] ) ) {
			$appsecret_proof = hash_hmac('sha256', $access_token, trim( $this->opts['fb_app_secret'] ) );
			$fb_url = add_query_arg(
				array(
					'appsecret_proof' => $appsecret_proof
				),
				$fb_url
			);
		}

		$fb_response = wp_remote_get( esc_url_raw( $fb_url ), array( 'timeout' => 30 ) );

		if( is_wp_error( $fb_response ) )
			$this->ajax_response( array( 'error' => $fb_response->get_error_message() ) );

		$fb_user = apply_filters( 'fbl/auth_data',json_decode( wp_remote_retrieve_body( $fb_response ), true ) );

		if( isset( $fb_user['error'] ) )
			$this->ajax_response( array( 'error' => 'Error code: '. $fb_user['error']['code'] . ' - ' . $fb_user['error']['message'] ) );

		//check if user at least provided email
		if( empty( $fb_user['email'] ) )
			$this->ajax_response( array( 'error' => __('We need your email in order to continue. Please try loging again. ', 'fbl' ),'fb' => $fb_user) );

		// Map our FB response fields to the correct user fields as found in wp_update_user
		$user = apply_filters( 'fbl/user_data_login', array(
			'fb_user_id' => $fb_user['id'],
			'first_name' => $fb_user['first_name'],
			'last_name'  => $fb_user['last_name'],
			'user_email' => $fb_user['email'],
			'user_pass'  => wp_generate_password(),
		));

		do_action( 'fbl/before_login', $user);

		$status = array( 'error' => __( 'Invalid User', 'fbl' ) );

		if ( empty( $user['fb_user_id'] ) )
			$this->ajax_response( $status );

		$user_obj = $this->getUserBy( $user );

		$meta_updated = false;

		if ( $user_obj ){
			$user_id = $user_obj->ID;
			$status = array( 'success' => $user_id, 'method' => 'login');
			// check if user email exist or update accordingly
			if( empty( $user_obj->user_email ) )
				wp_update_user( array( 'ID' => $user_id, 'user_email' => $user['user_email'] ) );

		} else {
			if( ! get_option('users_can_register') || apply_filters( 'fbl/registration_disabled', false ) ) {
				if( ! apply_filters( 'fbl/bypass_registration_disabled', false ) )
					$this->ajax_response( array( 'error' => __( 'User registration is disabled', 'fbl' ) ) );
			}
			// generate a new username
			$user['user_login'] = apply_filters( 'fbl/generateUsername', $this->generateUsername( $fb_user ) );

			$user_id = $this->register_user( apply_filters( 'fbl/user_data_register',$user ) );
			if( !is_wp_error( $user_id ) ) {
				$this->notify_new_registration( $user_id );
				update_user_meta( $user_id, '_fb_user_id', $user['fb_user_id'] );
				$meta_updated = true;
				$status = array( 'success' => $user_id, 'method' => 'registration' );
			}
		}
		if( is_numeric( $user_id ) ) {
			wp_set_auth_cookie( $user_id, true );
			if( !$meta_updated )
				update_user_meta( $user_id, '_fb_user_id', $user['fb_user_id'] );
			do_action( 'fbl/after_login', $user, $user_id);
		}
		$this->ajax_response( apply_filters( 'fbl/success_status', $status ) );
	}
}