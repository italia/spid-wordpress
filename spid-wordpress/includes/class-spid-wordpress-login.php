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
     * Public class where all hooks are added
     * @var Spid_Wordpress_Public   $spid
     */
    public $spid;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Facebook_Login_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

	/**
	 * More hellish nightmare fuel
	 */
	private $user_meta;

	/**
	 * Headers of a shibboleth request (don't know why we are inserting this if it is a SPID integration?)
	 *
	 * Are they the same inconsistence thing of they co-exist? I think the first.
	 *
	 * @see https://gist.github.com/umbros/0c0293b9fa541cd34be33f099611e79e
	 */
	private static $SHIB_HEADERS = array('Shib-Session-ID', 'Shib_Session_ID', 'HTTP_SHIB_IDENTITY_PROVIDER');

    protected $plugin_name;


    /**
     * Main Spid Instance
     *
     * Ensures only one instance of WSI is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @see WSI()
     * @return Fbl - Main instance
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Cloning is forbidden.
     * @since 1.0.0
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wsi' ), '2.1' );
    }

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
        $this->version      = SPID_VERSION;
        $this->plugin_name  = 'spid-login';
        $this->loader       = new Spid_Wordpress_Loader();
		$this->settings     = new Spid_Wordpress_Settings();
		$this->user_meta    = new Spid_Wordpress_User_Meta();
        $this->shortcodes = new Spid_Login_Shortcodes( $this->get_plugin_name(), $this->get_version() );
        $this->define_public_hooks();
        //$this->loader->run();


	}



	/**
	 * @since 1.0.0
	 */
	public static function factory() {
		return new self();
	}

	/**
	 * Use only if it's a SPID request.
	 *
	 * @since    1.0.0
	 */
	public function try_spid_login() {
//        require WP_SIMPLESAML_DIR . DIRECTORY_SEPARATOR . WP_SIMPLESAML_AUTOLOADER_FILE;
//        // @TODO da sostituire con il nome dl servizio configurato dall'utente
//        $saml_auth_as = new SimpleSAML_Auth_Simple( 'service-name' );
//        if(!$saml_auth_as->isAuthenticated()) {
//            $saml_auth_as->login();
//        } else {
//            $saml_auth_attributes = $saml_auth_as->getAttributes();
//            // @TODO recuperare il codice utente dagli attributi utilizzati
//        }
//		require WP_SIMPLESAML_DIR . DIRECTORY_SEPARATOR . WP_SIMPLESAML_AUTOLOADER_FILE;
//
//		$config_path = dirname( dirname(__FILE__) ) . DIRECTORY_SEPARATOR  . 'config';
//		SimpleSAML_Configuration::setConfigDir($config_path, 'spid');
//		SimpleSAML_Configuration::loadFromArray(array(), '[ARRAY A MUZZO]', 'spid');
//		$saml_auth_config = SimpleSAML_Configuration::getInstance('spid');
//		//$saml_auth_version = $saml_auth_config->getVersion();
//		// what now? what do I use this config for?
//
//		$saml_auth_as = new SimpleSAML_Auth_Simple( WP_SIMPLESAML_AUTHSOURCE );
//		//$saml_auth_attributes = $saml_auth_as->getAttributes();
//
//		if($saml_auth_as->isAuthenticated()) {
//			// TODO: see https://github.com/dev4pa/spid-drupal/blob/master/spid_auth.module#L210 for some switchy switches switching among POST parameters and setting IDP thingamjig
//			$existing_username = self::get_spid_authname($saml_auth_as);
//			//if($existing_username) {
//			$this->bypass_login( $existing_username );
//			//}
//		}
	}

	/**
	 * @param $auth SimpleSAML_Auth_Simple the auth thingamajig.
	 *
	 * @return string authname. Substring of it, for unknown reasons.
	 * @throws Exception if no valid unique ID (codice fiscale et al) can be found in SPID response
	 */
	private static function get_spid_authname($auth) {
		$simplesaml_attributes = $auth->getAttributes();
		$authname = '';
		// Check if valid local session exists..
		if( isset($simplesaml_attributes) ) {
			// TODO: remove this?
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
		// TODO: Capire cos'hanno fatto di male i primi 6 caratteri
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
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . '../public/js/spid-sp-access-button.min.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . '../public/css/spid-sp-access-button.min.css', array(), $this->version, 'all' );

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

	/**
	 * Check if the client is a Shibboleth request (don't know why we are inserting this if it is a SPID integration?)
	 *
	 * @see https://gist.github.com/umbros/0c0293b9fa541cd34be33f099611e79e
	 */
	static function isShibbolethRequest() {
		foreach(self::$SHIB_HEADERS as $header) {
			// Why isn't enough `! empty()` alone? Boh.
			if( array_key_exists($header, $_SERVER) && ! empty( $_SERVER[$header] ) ) {
				return true;
			}
		}
		return false;
	}

    private function define_public_hooks()
    {
        $this->spid = new Spid_Wordpress_Public( $this->get_plugin_name(), $this->get_version() );

        // TODO: Attivare le opzioni solo se il plugin e' configurato bene.

        $this->loader->add_action( 'login_form', $this->spid, 'print_button' );
        $this->loader->add_action( 'spid_login_button', $this->spid, 'print_button' );


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
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }
}
