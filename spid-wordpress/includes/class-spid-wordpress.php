<?php
/*
 * SPID-Wordpress - Plugin che connette Wordpress e SPID
 * Copyright (C) 2016, 2017 Ludovico Pavesi, Valerio Bozzolan, spid-wordpress contributors
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
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       asd.asd.asd
 * @since      1.0.0
 *
 * @package    Spid_Wordpress
 * @subpackage Spid_Wordpress/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Spid_Wordpress
 * @subpackage Spid_Wordpress/includes
 * @author     Ludovico Pavesi, Valerio Bozzolan, spid-wordpress contributors
 */
class Spid_Wordpress {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Spid_Wordpress_Loader $loader Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	public $path;

	private $thisFile;

	/**
	 * Plugin Instance
	 * @since 1.0.0
	 * @var Spid_Wordpress The Spid plugin instance
	 */
	protected static $_instance = null;

	/**
	 * The unique identifier of this plugin.
	 */
	const PLUGIN_NAME = 'spid-wordpress';

	/**
	 * The current version of the plugin.
	 */
	const VERSION = '1.0.0';

	const SETTINGS_PREFIX = 'spid';

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
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_user_settings_hooks();
		$this->define_login_page_hooks();
		$this->thisFile = __FILE__;
	}

	/**
	 * @since    1.0.0
	 */
	public static function factory() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Load the required dependencies for this plugin.
	 *r
	 * Include the following files that make up the plugin:
	 *
	 * - Spid_Wordpress_Loader. Orchestrates the hooks of the plugin.
	 * - Spid_Wordpress_i18n. Defines internationalization functionality.
	 * - Spid_Wordpress_Admin. Defines all hooks for the admin area.
	 * - Spid_Wordpress_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		$this->path = plugin_dir_path( dirname( __FILE__ ) );

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once $this->path . 'includes/class-spid-wordpress-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once $this->path . 'includes/class-spid-wordpress-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once $this->path . 'admin/class-spid-wordpress-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once $this->path . 'public/class-spid-wordpress-public.php';

		/**
		 * La classe che astrae le opzioni stoccate nel database. Ciò è necessario BECAUSE WORDPRESS.
		 */
		require_once $this->path . 'includes/class-spid-wordpress-settings.php';

		/**
		 * The login class managing the login page and other login stuff. Ecco.
		 */
		require_once $this->path . 'includes/class-spid-wordpress-login.php';

		/**
		 * The login class managing user settings (meta). Anche detta "user meta'" perche' previene il login quindi resta solo mezzo utente.
		 */
		require_once $this->path . 'includes/class-spid-wordpress-user-meta.php';

		/*
		 * The shortcodes
		 */
		require_once $this->path . 'includes/class-spid-wordpress-shortcodes.php';

		$this->loader = new Spid_Wordpress_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Spid_Wordpress_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Spid_Wordpress_i18n();

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

		$plugin_admin = new Spid_Wordpress_Admin();

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_settings_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_user_settings_hooks() {

		$plugin_user_meta = new Spid_Wordpress_User_Meta();

		$this->loader->add_action( 'profile_personal_options', $plugin_user_meta, 'add_user_settings_field' );
		$this->loader->add_action( 'personal_options_update', $plugin_user_meta, 'personal_options_update' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Spid_Wordpress_Public();

		$this->loader->add_action( 'wp_enqueue_styles', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action('wp_footer',$plugin_public,'add_spid_scripts');

	}

	private function define_login_page_hooks() {

		$plugin_login = Spid_Wordpress_Login::factory();
		$this->loader->add_action( 'login_enqueue_styles', $plugin_login, 'enqueue_styles' );
		$this->loader->add_action( 'login_enqueue_scripts', $plugin_login, 'enqueue_scripts' );
		//$this->loader->add_action( 'login_form', $plugin_login, 'login_form' );
		$this->loader->add_action( 'login_errors', $plugin_login, 'login_errors' );
		$this->loader->add_action( 'login_message', $plugin_login, 'login_message' );


		// Apparently never called
		// TODO: use for something useful
		$this->loader->add_action( 'login_form_postpass', $plugin_login, 'login_successful' );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
		Spid_Wordpress_Login::factory()->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return Spid_Wordpress::PLUGIN_NAME;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Spid_Wordpress_Loader    Orchestrates the hooks of the plugin.
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
		return Spid_Wordpress::VERSION;
	}



}
