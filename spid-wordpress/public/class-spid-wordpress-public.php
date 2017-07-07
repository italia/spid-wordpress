<?php
/*
 * SPID-Wordpress - Plugin che connette Wordpress e SPID
 * Copyright (C) 2016 Ludovico Pavesi, Valerio Bozzolan, spid-wordpress contributors
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
 * The public-facing functionality of the plugin.
 *
 * @link       asd.asd.asd
 * @since      1.0.0
 *
 * @package    Spid_Wordpress
 * @subpackage Spid_Wordpress/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Spid_Wordpress
 * @subpackage Spid_Wordpress/public
 * @author     Ludovico Pavesi, Valerio Bozzolan, spid-wordpress contributors
 * @todo       Use this class for something. Or delete it.
 */
class Spid_Wordpress_Public {
	protected $plugin_name;

	protected $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// TODO: should be passed as parameters, or read from global constants, I don't even remeber
		$this->plugin_name = 'spid-login';
		$this->version     = '1.0.0';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {
		//wp_enqueue_style( Spid_Wordpress::PLUGIN_NAME, plugin_dir_url( __FILE__ ) . 'css/spid-wordpress-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		// TODO: doesn't work anyway
		//wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/spid-sp-access-button.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/spid-sp-access-button.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Print the button on login page
	 * @since 1.0.0
	 */
	public function print_button() {
		include_once( 'partials' . DIRECTORY_SEPARATOR . 'spid-wordpress-button.php' );
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since 1.0.0
	 * @return string The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Returns path to image.
	 *
	 * @param string $name file name (no path)
	 */
	private function get_img( $name ) {
		return plugins_url( 'img/' . $name, __FILE__ );
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
		$png = self::get_img( 'spid-idp-arubaid.png' );

		$result =
			"<li class=\"spid-idp-button-link\" data-idp=\"$data_idp\">" .
			"<a href=\"#\"><span class=\"spid-sr-only\">$name</span><img src=\"$svg\" onerror=\"this.src='$png'; this.onerror=null;\" alt=\"$alt\" /></a>" .
			'</li>';

		return $result;
	}

	/**
	 * Get "li" elements representing IdPs, in random order
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
		shuffle( $idp );

		return $idp;
	}

	/**
	 * @deprecated throws random warnings due to mismatched parameters. Also, IdP order randomization is now done server-side.
	 */
	public function add_spid_scripts() {
//		<script>
//		jQuery(document).ready( function () {
//			var rootList = jQuery('#spid-idp-list-small-root-get');
//			var idpList = rootList.children('.spid-idp-button-link');
//			var lnkList = rootList.children('.spid-idp-support-link');
//			while (idpList.length) {
//				rootList.append( idpList.splice(Math.floor(Math.random() * idpList.length), 1)[0] );
//			}
//			rootList.append(lnkList);
//		} );
//		</script>
	}
}
