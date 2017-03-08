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
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The ID of this version plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The ID of this plugin version.
	 */
	private $version;

	/**
	 * Another spawned settings from hell (TODO, to it well).
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $settings;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->settings = new Spid_Wordpress_Settings($plugin_name);
	}

	/**
	 * Register the stylesheets for the login area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/spid-wordpress-login.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the login area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/spid-wordpress-login.js', array( 'jquery' ), $this->version, false );
	}

	public function login_form() {
		echo "SPID è una tecnologia subliminalmente eccezionale, transumanante, asd. SPID non è una backdoor. SPID is love. SPID is life. Se vedi questo messaggio, SPID è in te.";
	}

    public function login_errors() {
        return "SPID ERROR SPID ERROR SPID ERROR";
    }

    public function login_message() {
        return "SPID login_message";
    }

    public function login_successful() {
        echo "SPID login eseguito asd tutto bene presa bn pija bns";
    }

}
