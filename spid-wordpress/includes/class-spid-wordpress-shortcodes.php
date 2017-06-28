<?php
/**
 * The shortcodes class.
 *
 * All plugins shortcodes are defined on this class
 *
 * @since      1.0.0
 * @package    Facebook_Login_Pro
 * @subpackage Facebook_Login_Pro/includes
 * @author     Damian Logghe <info@timersys.com>
 * @TODO LICENSE!!
 */
class Spid_Login_Shortcodes {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
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
		$this->register_shortcodes();
	}

	/**
	 * Register all plugin shortcodes
	 */
	public function register_shortcodes( ) {
		add_shortcode( 'spid_login_button', array( $this, 'login_button' ) );
	}

	/**
	 * Display SPID login button
	 * [spid_login_button redirect="" hide_if_logged=""]
	 *
	 * @param $atts
	 * @param $content
	 *
	 * @return string
	 */
	function login_button( $atts, $content ) {
		ob_start();

		do_action('spid_login_button');
		$html = ob_get_contents();
		//$html = "ABC";
		ob_clean();

		return $html;
	}

}
