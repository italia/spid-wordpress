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
		$this->plugin_name = 'spid-login';
		$this->version = '1.0.0';
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Spid_Wordpress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Spid_Wordpress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//wp_enqueue_style( Spid_Wordpress::PLUGIN_NAME, plugin_dir_url( __FILE__ ) . 'css/spid-wordpress-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Spid_Wordpress_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Spid_Wordpress_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/spid-sp-access-button.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_style(  $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/spid-sp-access-button.min.css', array(), $this->version, 'all' );

	}

	/**
	 * Print the button on login page
	 * @since 1.0.0
	 */
	public function print_button() {

		//echo apply_filters('spid/login_button', '<div><h1>Login</h1></div>');
		//echo "<div><h1>Login</h1></div>";
		include_once('partials' . DIRECTORY_SEPARATOR . 'spid-wordpress-button.php');

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

	public function get_img($name) {
		//echo $this->path;
		//echo $this->thisFile;
		echo plugins_url('img/' . $name, __FILE__);
	}

	public function add_spid_scripts(){
		?>

		<script>
		jQuery(document).ready( function () {
			var rootList = jQuery('#spid-idp-list-small-root-get');
			var idpList = rootList.children('.spid-idp-button-link');
			var lnkList = rootList.children('.spid-idp-support-link');
			while (idpList.length) {
				rootList.append( idpList.splice(Math.floor(Math.random() * idpList.length), 1)[0] );
			}
			rootList.append(lnkList);
		} );
		</script>

		<?php
	}
}
