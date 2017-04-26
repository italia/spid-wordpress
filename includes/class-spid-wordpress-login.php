<?php
/*
 * SPID-Wordpress - Plugin che connette Wordpress e SPID
 * Copyright (C) 2017 Ludovico Pavesi, Valerio Bozzolan, spid-wordpress contributors
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * The login-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Spid_Wordpress
 * @subpackage Spid_Wordpress/login
 * @author     Ludovico Pavesi, Valerio Bozzolan, spid-wordpress contributors
 */
class Spid_Wordpress_Login {
	/**
	 * Another spawned settings from hell (TODO, to it well).
	 */
	private $settings;

	/**
	 * More hellish nightmare fuel
	 */
	private $user_meta;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->settings    = new Spid_Wordpress_Settings();
		$this->user_meta   = new Spid_Wordpress_User_Meta();
	}

	/**
	 * @since 1.0.0
	 */
	public static function factory() {
		return new self();
	}

	/**
	 * @TODO make something sane
	 *
	 * @since    1.0.0
	 */
	public static function is_spid_request() {
		return ! empty( $_GET );
	}

	/**
	 * Use only if it's a SPID request.
	 *
	 * @since    1.0.0
	 */
	public function try_spid_login() {
		include plugin_dir_path(__FILE__) . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

		$saml_auth_config = SimpleSAML_Configuration::getInstance();
		//$saml_auth_version = $saml_auth_config->getVersion();

		$saml_auth_as = new SimpleSAML_Auth_Simple('default-sp');
		//$saml_auth_attributes = $saml_auth_as->getAttributes();

		DEBUG and var_dump($sams_auth_as);

		if($saml_auth_as->isAuthenticated()) {
			$existing_username = self::get_spid_authname($saml_auth_as);
			//if($existing_username) {
			$this->bypass_login( $existing_username );
			//}
		}
	}

	/**
	 * @param $auth SimpleSAML_Auth_Simple
	 * @return string
	 */
	private static function get_spid_authname($auth) {
		$simplesaml_attributes = $auth->getAttributes();
		$authname = '';
		// Check if valid local session exists..
		if( isset($simplesaml_attributes) ) {
			DEBUG and printf('_spid_auth_get_authname: Valid local session exist');
			if (isset($simplesaml_attributes['fiscalNumber']) ) {
				$authname = $simplesaml_attributes['fiscalNumber'][0];
			} else if (isset($simplesaml_attributes['ivaCode'])) {
				$authname = $simplesaml_attributes['ivaCode'][0];
			} else {
				throw new Exception( sprintf("Error in %s: no valid unique id attribute set", __FILE__ ) );
			}
		} else {
			// TODO: Capire se è intenzionale qui evitare di scagliare eccezioni ecco
		}
		// TODO: Capire perchè @madbob qui ha messo 6
		return substr($authname, 6);
	}

	/**
	 * Register the stylesheets for the login area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// wp_enqueue_style( Spid_Wordpress::PLUGIN_NAME, plugin_dir_url( __FILE__ ) . 'css/spid-wordpress-login.css', array(), Spid_Wordpress::VERSION, 'all' );
	}

	/**
	 * Register the JavaScript for the login area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		//wp_enqueue_script( Spid_Wordpress::PLUGIN_NAME, plugin_dir_url( __FILE__ ) . 'js/spid-wordpress-login.js', array( 'jquery' ), Spid_Wordpress::VERSION, false );
	}

	public function login_form() {
		echo "SPID è una tecnologia subliminalmente eccezionale, transumanante, asd. SPID non è una backdoor. SPID is love. SPID is life. Se vedi questo messaggio, SPID è in te.";
	}

	/**
	 * In a message box.
	 *
	 * Called also for action=lostpassword.
	 *
	 * @param $default string Default message
	 *
	 * @return string
	 */
	public function login_errors( $default ) {
		return $default;
	}

	/**
	 * Not in message box.
	 *
	 * @param string $default Default login message
	 *
	 * @return string
	 */
	public function login_message( $default ) {
		return $default;
	}

	/**
	 * Never called.
	 */
	public function login_successful() {
		echo "SPID login eseguito asd tutto bene presa bn pija bns";
		die( "login_successful() fired?" );
	}

	/**
	 * Programmatically logs a user in.
	 *
	 * @param string $username the WORDPRESS, NOT SPID, username
	 *
	 * @return bool True if the login was successful; false if it wasn't
	 * @throws Exception if SPID login disabled
	 * @see https://wordpress.stackexchange.com/a/156431
	 */
	function bypass_login( $username ) {
		$user = get_user_by( 'login', $username );

		if ( ! $user ) {
			// TODO: remove and controllare a monte
			throw new Exception( 'User not found (this should never happen)' );
		}

		if ( ! $this->settings->get_option_value( Spid_Wordpress_Settings::USER_SECURITY_CHOICE ) && $this->user_meta->get_user_has_disabled_spid( $user->ID ) ) {
			throw new Exception( "SPID login disabled by user" );
		}

		if ( is_user_logged_in() ) {
			wp_logout();
		}

		$filter = array( __CLASS__, 'short_circuit_auth' );

		// Hook in earlier than other callbacks to short-circuit them
		add_filter( 'authenticate', $filter, 10, 3 );

		// Login the user with the previous registered hook
		$user = wp_signon( array( 'user_login' => $username ) );

		// Unregister the previously registered fake authentication hook
		// Secret undocumented parameters found in OpenID plugin or something
		/** @noinspection PhpMethodParametersCountMismatchInspection */
		remove_filter( 'authenticate', $filter, 10, 3 );

		if ( is_a( $user, 'WP_User' ) ) {
			wp_set_current_user( $user->ID, $user->user_login );

			if ( is_user_logged_in() ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * An 'authenticate' filter callback that authenticates the user using only the username.
	 *
	 * To avoid potential security vulnerabilities, this should only be used in the context of a programmatic login,
	 * and unhooked immediately after it fires.
	 *
	 * @param WP_User $user
	 * @param string $username
	 * @param string $password
	 *
	 * @return bool|WP_User a WP_User object if the username matched an existing user, or false if it didn't
	 */
	static function short_circuit_auth( $user, $username, $password ) {
		// Support also ' email'
		return get_user_by( 'login', $username );
	}
}
