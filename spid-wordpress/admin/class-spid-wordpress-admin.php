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
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Spid_Wordpress
 * @subpackage Spid_Wordpress/admin
 * @author     Ludovico Pavesi, Valerio Bozzolan, spid-wordpress contributors
 */
class Spid_Wordpress_Admin {
	/**
	 * Hook suffix for the options page.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      false|string $options_page_hook_suffix The hook suffix for the options page.
	 */
	private $options_page_hook_suffix = false;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->settings = new Spid_Wordpress_Settings();
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @deprecated
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

		// TODO: uncomment if we need these
		//wp_enqueue_style( Spid_Wordpress::PLUGIN_NAME, plugin_dir_url( __FILE__ ) . 'css/spid-wordpress-admin.css', array(), Spid_Wordpress::VERSION, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 * @deprecated
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

		// TODO: uncomment if we need these
		//wp_enqueue_script( Spid_Wordpress::PLUGIN_NAME, plugin_dir_url( __FILE__ ) . 'js/spid-wordpress-admin.js', array( 'jquery' ), Spid_Wordpress::VERSION, false );
	}

	/**
	 * Brings into existence a magnificent settings page.
	 *
	 * @since    1.0.0
	 */
	public function add_settings_page() {
		$this->options_page_hook_suffix = add_options_page( 'SPID',
			'SPID',
			'manage_options',
			Spid_Wordpress::PLUGIN_NAME,
			array( $this, 'display_settings_page' )
		);
	}

	public function display_settings_page() {
		require_once 'partials' . DIRECTORY_SEPARATOR . 'spid-wordpress-admin-display.php';
	}

	public function register_settings() {
		add_settings_section(
		// String for use in the 'id' attribute of tags
			$this->settings->get_group_id(),

			// Title of the section
			__( "General", 'spid-wordpress' ), // TODO: conviene far saltare fuori il domain dalla classe i18n o hardcodarlo ovunque?

			// Callback
			array( $this, 'settings_general_callback' ),

			// The menu page on which to display this section. Should match $menu_slug from Function Reference/add theme page
			Spid_Wordpress::PLUGIN_NAME
		);

		add_settings_field(
		// String for use in the 'id' attribute of tags
			$this->settings->get_label_id( Spid_Wordpress_Settings::USER_REGISTRATION ),

			// Title of the field
			__( "Registration", 'spid-wordpress' ),

			// Function that fills the field with the desired inputs as part of the larger form.
			// assed a single argument, the $args array.
			// Name and id of the input should match the $id given to this function.
			// The function should echo its output.
			array( $this, 'settings_field_checkbox_callback' ),

			// The menu page on which to display this field.
			// Should match $menu_slug from add_theme_page() or from do_settings_sections().
			Spid_Wordpress::PLUGIN_NAME,

			// The section of the settings page in which to show the box
			// (default or a section you added with add_settings_section(),
			// look at the page in the source to see what the existing ones are.)
			$this->settings->get_group_id(),

			// Additional arguments that are passed to the $callback function.
			// The 'label_for' key/value pair can be used to format the field title like so: <label for="value">$title</label>.
			array(
				'label_for'   => $this->settings->get_label_id( Spid_Wordpress_Settings::USER_REGISTRATION ),
				'option'      => Spid_Wordpress_Settings::USER_REGISTRATION,
				'description' => __( "Register new users if they log in using SPID for the first time. Disable to allow only already registered users to log in with SPID.", 'spid-wordpress' ),
			)
		);

		add_settings_field(
		// String for use in the 'id' attribute of tags
			$this->settings->get_label_id( Spid_Wordpress_Settings::NO_USER_SECURITY_CHOICE ),

			// Title of the field
			__( "Force SPID integration", 'spid-wordpress' ),

			// Function that fills the field with the desired inputs as part of the larger form.
			// assed a single argument, the $args array.
			// Name and id of the input should match the $id given to this function.
			// The function should echo its output.
			array( $this, 'settings_field_checkbox_callback' ),

			// The menu page on which to display this field.
			// Should match $menu_slug from add_theme_page() or from do_settings_sections().
			Spid_Wordpress::PLUGIN_NAME,

			// The section of the settings page in which to show the box
			// (default or a section you added with add_settings_section(),
			// look at the page in the source to see what the existing ones are.)
			$this->settings->get_group_id(),

			// Additional arguments that are passed to the $callback function.
			// The 'label_for' key/value pair can be used to format the field title like so: <label for="value">$title</label>.
			array(
				'label_for'   => $this->settings->get_label_id( Spid_Wordpress_Settings::NO_USER_SECURITY_CHOICE ),
				'option'      => Spid_Wordpress_Settings::NO_USER_SECURITY_CHOICE,
				'description' => __( "Leave this option unchecked if you care about user choice. Not all users may appreciate SPID centralization.", 'spid-wordpress' ),
			)
		);

		register_setting(
			Spid_Wordpress::PLUGIN_NAME,
			$this->settings->get_group_id(),
			array( $this, 'settings_general_sanitize' )
		);
	}

	public function settings_general_callback() {
		echo '<p>' . __( 'General settings for SPID integration.', 'spid-wordpress' ) . '</p>';
	}

	public function settings_general_sanitize( $input ) {
		$checkboxes = array(
			Spid_Wordpress_Settings::NO_USER_SECURITY_CHOICE,
			Spid_Wordpress_Settings::USER_REGISTRATION
		);

		$values = array();
		foreach ( $checkboxes as $i ) {
			$values[ $i ] = isset( $input[ $i ] ) ? (int) $input[ $i ] : 0;
		}

		return $values;
	}

	function spid_general_callback( $args ) {
		printf(
			'<p id="%s-%s">%s</p>',
			$args['id'],
			$args['name']
		);
	}

	/**
	 * @param array $args ['option' => string, 'label_for' => string]
	 */
	function settings_field_checkbox_callback( $args ) {
		$opt = $args['option'];
		if ( ! isset( $args['default'] ) ) {
			$args['default'] = false;
		}

		$group   = $this->settings->get_group_id();
		$value   = $this->settings->get_option_value( $opt );
		$checked = isset( $value ) ? $value : $args['default'];
		?>

		<input type="checkbox" id="<?php echo $this->settings->get_label_id( $opt ) ?>" value="1"
		       name="<?php printf( '%s[%s]', $group, $opt ) ?>" <?php checked( $checked ) ?> />
		<p class="description"><?php echo esc_html( $args['description'] ) ?></p>

		<?php
	}

	function settings_field_textbox_callback( $args ) {
		$opt = $args['option'];
		if ( ! isset( $args['default'] ) ) {
			$args['default'] = false;
		}

		$group     = $this->settings->get_group_id();
		$value     = $this->settings->get_option_value( $opt );
		$sanitized = isset( $value ) ? $value : $args['default'];
		?>

		<input type="text" id="<?php echo $this->settings->get_label_id( $opt ) ?>"
		       name="<?php printf( '%s[%s]', $group, $opt ) ?>" value="<?php echo esc_html( $sanitized ); ?>"/>
		<p class="description"><?php echo esc_html( $args['description'] ) ?></p>

		<?php

	}
}
