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
 * @author     Ludovico Pavesi, Valerio Bozzolan, Salvo Rapisarda, spid-wordpress contributors
 */
class Spid_Wordpress_Login {
	/**
	 * Plugin name, as always. Or maybe should be removed?
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since 1.0.0
	 * @var string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Another spawned settings from hell
	 */
	private $settings;

	/**
	 * More hellish nightmare fuel
	 */
	private $user_meta;

	/**
	 * Headers of a shibboleth request (don't know why we are inserting this if it is a SPID integration?)
	 *
	 * @todo Are they the same inconsistence thing of they co-exist? I think the first.
	 *
	 * @see https://gist.github.com/umbros/0c0293b9fa541cd34be33f099611e79e
	 */
	private static $SHIB_HEADERS = array( 'Shib-Session-ID', 'Shib_Session_ID', 'HTTP_SHIB_IDENTITY_PROVIDER' );

	/**
	 * Contains the instance.
	 *
	 * @type Spid_Wordpress_Login
	 */
	protected static $_instance;

	/**
	 * Return the only instance of this class or create it
	 *
	 * @since 1.0.0
	 */
	public static function factory() {
		if ( ! isset( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * Cloning is forbidden.
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, 'Cannot clone an instance', '2.1' );
	}

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	private function __construct() {
		$this->version     = Spid_Wordpress::VERSION;
		$this->plugin_name = Spid_Wordpress::PLUGIN_NAME;
		$this->settings    = new Spid_Wordpress_Settings();
		$this->user_meta   = new Spid_Wordpress_User_Meta();
        $this->shortcodes  = new Spid_Login_Shortcodes( $this->get_plugin_name(), $this->get_version() );
	}

	/**
	 * Do login actions (send SPID request, get SPID response and login user)
	 * if HTTP GET parameters are set and plugin is configured correctly.
	 */
	public function do_login_action() {
		if ( $this->settings->is_plugin_configured_correctly() ) {
			if ( isset( $_GET['init_spid_login'] ) ) {
				Spid_Wordpress_Login::factory()->spid_startsso();
			} elseif ( isset( $_GET['return_from_sso'] ) ) {
				Spid_Wordpress_Login::factory()->spid_login();
			}
		}
	}

	/**
	 * Try to get a certain element from a non-so-consistent array.
	 *
	 * @param $attributes array Haystack
	 * @param $attribute string|int Needle (index of the array)
	 *
	 * @return mixed|null The element, if found
	 */
	private static function get_attribute( $attributes, $attribute ) {
		if ( isset( $attributes[ $attribute ] ) ) {
			$v = $attributes[ $attribute ];
			if ( is_array( $v ) ) {
				return $v[0];
			}

			return $v;
		}

		return null;
	}

	/**
	 * Get an identifier from SPID. This will be the username.
	 *
	 * @param $simplesaml_attributes array SimpleSAML_Auth_Simple#getAttributes().
	 *
	 * @return string Any SPID identifier
	 * @throws Exception if no valid unique ID (codice fiscale et all) can be found in SPID response
	 */
	private static function get_spid_authname( $simplesaml_attributes ) {

		$identifiers = array(
			'CF'   => 'fiscalNumber',
			'IVA'  => 'ivaCode',
			'SPID' => 'spidCode'
		);

		foreach ( $identifiers as $prefix => $identifier ) {
			$authname = self::get_attribute( $simplesaml_attributes, $identifier );
			if ( $authname ) {
				return sprintf( "%s_%s", $prefix, $authname );
			}
		}

		throw new Exception( sprintf( "Error in %s: no valid unique id attribute set", __FILE__ ) );
	}

	/**
	 * Register the stylesheets for the login area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

	}

	/**
	 * Register the JavaScript for the login area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// Required for the footer inline code.
		wp_enqueue_script('jquery');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'public/js/spid-sp-access-button.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( dirname( __FILE__ ) ) . 'public/css/spid-sp-access-button.min.css', array(), $this->version, 'all' );
        //wp_enqueue_style(  $this->plugin_name, plugin_dir_url( __FILE__ ) . '../public/css/spid-sp-access-button.min.css', array(), $this->version, 'all' );
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
	 * @deprecated
	 */
	public function login_successful() {
		echo "SPID login eseguito asd tutto bene presa bn pija bns";
		die( "login_successful() fired?" );
	}

	/**
	 * Print the button on login page
	 * @since 1.0.0
	 */
	public function print_button() {
		/** @noinspection PhpIncludeInspection */
		include_once plugin_dir_path( dirname( __FILE__ ) ) . 'public' . DIRECTORY_SEPARATOR . 'partials' . DIRECTORY_SEPARATOR . 'spid-wordpress-button.php';
	}

	/**
	 * Returns path to image.
	 *
	 * @param string $name file name (no path)
	 *
	 * @return string string full path to file
	 */
	private static function get_img( $name ) {
		return plugins_url( 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $name, dirname( __FILE__ ) );
	}

	/**
	 * Get HTML "li" element for a single IdP.
	 *
	 * @param string $name full IdP name, for screen readers
	 * @param string $data_idp for the "data-idp" parameter
	 * @param string $image_name image name, no extension and no path (must exist both as .svg and .png)
	 * @param string $alt alt tag for the image (usually same as name...)
	 *
	 * @return string HTML code for a single IdP
	 * @see get_idp_html_all
	 */
	private static function get_idp_html( $name, $data_idp, $image_name, $alt ) {
		$svg = self::get_img( $image_name . '.svg' );
		$png = self::get_img( $image_name . '.png' );

		$result =
			"<li class=\"spid-idp-button-link\" data-idp=\"$data_idp\">" .
			"<a href=\"#\"><span class=\"spid-sr-only\">$name</span><img src=\"$svg\" onerror=\"this.src='$png'; this.onerror=null;\" alt=\"$alt\" /></a>" .
			'</li>';

		return $result;
	}

	/**
	 * Get "li" elements representing IdPs
	 *
	 * @return array HTML code for each IdP
	 */
	public static function get_idp_html_all() {
		$idp   = array();
		$idp[] = self::get_idp_html( 'Aruba ID', 'arubaid', 'spid-idp-arubaid', 'Aruba ID' );
		$idp[] = self::get_idp_html( 'Infocert ID', 'infocertid', 'spid-idp-infocertid', 'Infocert ID' );
		$idp[] = self::get_idp_html( 'Namirial ID', 'namirialid', 'spid-idp-namirialid', 'Namirial ID' );
		$idp[] = self::get_idp_html( 'Poste ID', 'posteid', 'spid-idp-posteid', 'Poste ID' );
		$idp[] = self::get_idp_html( 'Sielte ID', 'sielteid', 'spid-idp-sielteid', 'Sielte ID' );
		$idp[] = self::get_idp_html( 'SPIDItalia Register.it', 'spiditalia', 'spid-idp-spiditalia', 'SpidItalia' );
		$idp[] = self::get_idp_html( 'Tim ID', '', 'spid-idp-timid', 'Tim ID' );
		//$idp[] = self::get_idp_html('', '', '', '');

		// to randomize order server-side:
		//shuffle( $idp );

		return $idp;
	}

	/**
	 * Print javascript to open menu button
	 */
	public function add_button_scripts() {
		?>
		<script>
			jQuery( document ).ready( function() {
				var rootList = jQuery( '#spid-idp-list-small-root-get' );
				var idpList = rootList.children( '.spid-idp-button-link' );
				var lnkList = rootList.children( '.spid-idp-support-link' );

				while( idpList.length ) {
					rootList.append( idpList.splice( Math.floor( Math.random() * idpList.length ), 1 )[0] );
				}
				rootList.append( lnkList );
			} );
		</script>
		<?php
	}

	/**
	 * Programmatically logs a user in (if allowed).
	 *
	 * @param string $user_login the WORDPRESS, NOT SPID, username, as WP_User#user_login
	 * @param array $userdata See 2nd ::create_new_user() param
	 *
	 * @return bool True if the login was successful; false if it wasn't
	 * @throws Exception if SPID login disabled
	 * @see https://wordpress.stackexchange.com/a/156431
	 */
	function bypass_login( $user_login, $userdata = array() ) {

		$user = get_user_by( 'login', $user_login );

		// Check if the user exists
		if ( ! $user ) {
			// Try to create this new user if allowed or throw exception
			$user = $this->create_new_user( $user_login, $userdata );
		}

		// Now the $user exists

		// The user is allowed to choose?
		if ( ! $this->settings->get_option_value( Spid_Wordpress_Settings::NO_USER_SECURITY_CHOICE ) ) {
			// The user is allowed to choose!
			if ( Spid_Wordpress_User_Meta::get_user_has_disabled_spid( $user->ID ) ) {
				// The user don't want SPID integration
				throw new Exception( "SPID login disabled by user." );
			}
		}

		if ( is_user_logged_in() ) {
			wp_logout();
		}

		$filter = array( __CLASS__, 'short_circuit_auth' );

		// Hook in earlier than other callbacks to short-circuit them
		add_filter( 'authenticate', $filter, 10, 3 );

		// Login the user with the previous registered hook
		$user = wp_signon( array( 'user_login' => $user_login ) );

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
	 * Create a new user (if allowed by the settings).
	 *
	 * @param string $user_login WP_User#user_login
	 * @param array $userdata WP_User fields
	 *
	 * @return WP_User
	 * @throws Exception
	 */
	function create_new_user( $user_login, $userdata = array() ) {
		// The user does not exist

		if ( ! $this->settings->get_option_value( Spid_Wordpress_Settings::USER_REGISTRATION ) ) {
			throw new Exception( "Users are not allowed to register in using SPID in this website." );
		}

		$default_userdata = array(
			'user_login' => $user_login,
			'user_pass'  => null // When creating an user, `user_pass` is expected.
		);

		// https://codex.wordpress.org/Function_Reference/wp_insert_user
		$user_ID = wp_insert_user( array_merge( $default_userdata, $userdata ) );

		if ( is_wp_error( $user_ID ) ) {
			// Probably the user already exists, or illegal characters in username
			throw new Exception( "Can't create user" );
		}

		// Obtain the already created user
		return get_user_by( 'ID', $user_ID );
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
	 * Check if the client is a Shibboleth request
	 *
	 * @TODO (don't know why we are inserting this if it is a SPID integration)
	 * @see https://gist.github.com/umbros/0c0293b9fa541cd34be33f099611e79e
	 */
	static function is_shibbosomething_request() {
		foreach ( self::$SHIB_HEADERS as $header ) {
			// Why isn't enough `! empty()` alone? Boh.
			// ↑ Because "empty" expects an array, but the value may be null or other non-array thypes?
			if ( array_key_exists( $header, $_SERVER ) && ! empty( $_SERVER[ $header ] ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Init external authentication with SimpleSAMLPHP and ReturnToUrl.
	 *
	 * @since     1.0.0
	 */
	public function spid_startsso() {
		if ( ! $this->include_libs() ) {
			return;
		}

		// @TODO da sostituire con il nome dl servizio configurato dall'utente
		// TODO: quale servizio e quale utente? L'utente che seleziona quale IdP usare o il proprietario del sito (che cosa dovrebbe impostare!?)?
		$saml_auth_as = new SimpleSAML_Auth_Simple( WP_SIMPLESAML_AUTHSOURCE );
		if ( ! $saml_auth_as->isAuthenticated() ) {
			$saml_auth_as       = new SimpleSAML_Auth_Simple( WP_SIMPLESAML_AUTHSOURCE );
			$params             = array();
			$params['ReturnTo'] = get_home_url( null, '/?return_from_sso=true' );
			// TODO: Da sostiture con pagina di errore
			$params['ErrorURL'] = get_home_url();
			$saml_auth_as->login( $params );

		}
	}

	public function spid_login() {
		if ( ! $this->include_libs() ) {
			return;
		}

		// @TODO da sostituire con il nome dl servizio configurato dall'utente
		// TODO: quale servizio e quale utente? L'utente che seleziona quale IdP usare o il proprietario del sito (che cosa dovrebbe impostare!?)?
		$saml_auth_as = new SimpleSAML_Auth_Simple( WP_SIMPLESAML_AUTHSOURCE );
		if ( $saml_auth_as->isAuthenticated() ) {

			$saml_auth_attributes = $saml_auth_as->getAttributes();

			$spid_user_authname = self::get_spid_authname( $saml_auth_attributes );

			// Enrich the registered WordPress user with provided data.
			$userdata = array(
				'user_email' => 'email',
				'first_name' => 'name',
				'last_name'  => 'familyName'
			);
			foreach ( $userdata as $wp_field => $saml_field ) {
				// The value is NULL if not available
				$userdata[ $wp_field ] = self::get_attribute( $saml_auth_attributes, $saml_field );
			}

			// Enrich also with Consider also the username as "Name Surname"
			if ( isset( $userdata['first_name'], $userdata['last_name'] ) ) {
				$userdata['user_nicename'] = sprintf( "%s %s",
					$userdata['first_name'],
					$userdata['last_name']
				);
			}

			// Try login
			$this->bypass_login( $spid_user_authname, $userdata );

		} else {
			throw new Exception( 'Errore durante autenticazione SPID.' );
		}
	}

	/**
	 * Logout user, if logged in via SPID. Called in an hook.
	 */
	public function spid_logout() {

		if ( ! $this->include_libs() ) {
			return;
		}

		$saml_auth_as = new SimpleSAML_Auth_Simple( WP_SIMPLESAML_AUTHSOURCE );
		if ( $saml_auth_as->isAuthenticated() ) {
			$saml_auth_as->logout();
		}

	}

	/**
	 * Return true if plugin is enabled
	 * @since     1.0.0
	 * @return    boolean    True if plugin is enabled, otherwise False
	 */
	public function is_enabled() {
		// @TODO Come verificare se il plugin è attivo ???
		// TODO: basarsi su cosa fanno le classi activator e deactivator, cioè nulla. Che utilità ha una differenza tra "installato" e "attivato"?
		return true;
	}

	public static function include_libs() {

		//if ($this->is_enabled()) {
		// @TODO Should this be database-selectable?
		if(!file_exists(WP_SIMPLESAML_DIR . DIRECTORY_SEPARATOR . WP_SIMPLESAML_AUTOLOADER_FILE)) {
			return false;
		}
		require_once WP_SIMPLESAML_DIR . DIRECTORY_SEPARATOR . WP_SIMPLESAML_AUTOLOADER_FILE;
		return true;
		//} else {
		//    return false;
		//}
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
