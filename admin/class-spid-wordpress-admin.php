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
	 * Hook suffix for the options page.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      false|string    $options_page_hook_suffix    The hook suffix for the options page.
	 */
	private $options_page_hook_suffix = false;

	/**
	 * Settings prefix
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Settings prefix
	 */
	private $settings_prefix;

	/**
	 * Settings default values
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     array       $settings_defaults Default values for every setting
	 */
	private $settings_defaults;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->settings_prefix = $plugin_name.'_settings';
		$this->settings_defaults = array(
			$this->settings_prefix . '_user_security_choice' => 0,
			$this->settings_prefix . '_registration' => 1
		);
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/spid-wordpress-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/spid-wordpress-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Brings into existence a magnificent settings page.
	 *
	 * @since    1.0.0
	 */
	public function add_settings_page() {
		$this->options_page_hook_suffix = add_options_page('SPID', 'SPID', 'manage_options', $this->plugin_name, array($this, 'display_settings_page'));
	}

	public function display_settings_page() {
		require_once 'partials/spid-wordpress-admin-display.php';
	}

	public function register_settings() {
		add_settings_section(
		// String for use in the 'id' attribute of tags
			$this->settings_prefix.'_general',

			// Title of the section
			__("General", 'spid-wordpress'), // TODO: conviene far saltare fuori il domain dalla classe i18n o hardcodarlo ovunque?

			// Callback
			array($this, 'settings_general_callback'),

			// The menu page on which to display this section. Should match $menu_slug from Function Reference/add theme page
			$this->plugin_name
		);

		add_settings_field(
		// String for use in the 'id' attribute of tags
			$this->settings_prefix.'_registration',

			// Title of the field
			__("Registration", 'spid-wordpress'),

			// Function that fills the field with the desired inputs as part of the larger form.
			// assed a single argument, the $args array.
			// Name and id of the input should match the $id given to this function.
			// The function should echo its output.
			array($this, 'settings_checkbox_callback'),

			// The menu page on which to display this field.
			// Should match $menu_slug from add_theme_page() or from do_settings_sections().
			$this->plugin_name,

			// The section of the settings page in which to show the box
			// (default or a section you added with add_settings_section(),
			// look at the page in the source to see what the existing ones are.)
			$this->settings_prefix.'_general',

			// Additional arguments that are passed to the $callback function.
			// The 'label_for' key/value pair can be used to format the field title like so: <label for="value">$title</label>.
			[
				'label_for'    => $this->settings_prefix.'_registration',
				'option'       => $this->settings_prefix.'_registration',
				'description'  => __("New users can be registered by SPID authorities.", 'spid-wordpress'),
			]
		);

		add_settings_field(
			// String for use in the 'id' attribute of tags
			$this->settings_prefix.'_user_security_choice',

			// Title of the field
			__("Force SPID integration", 'spid-wordpress'),

			// Function that fills the field with the desired inputs as part of the larger form.
			// assed a single argument, the $args array.
			// Name and id of the input should match the $id given to this function.
			// The function should echo its output.
			array($this, 'settings_checkbox_callback'),

			// The menu page on which to display this field.
			// Should match $menu_slug from add_theme_page() or from do_settings_sections().
			$this->plugin_name,

			// The section of the settings page in which to show the box
			// (default or a section you added with add_settings_section(),
			// look at the page in the source to see what the existing ones are.)
			$this->settings_prefix.'_general',

			// Additional arguments that are passed to the $callback function.
			// The 'label_for' key/value pair can be used to format the field title like so: <label for="value">$title</label>.
			[
				'label_for'    => $this->settings_prefix . '_user_security_choice',
				'option'       => $this->settings_prefix . '_user_security_choice',
				'description'  => __("Leave this option unchecked if you care about user choice. Not all users may appreciate SPID centralization.", 'spid-wordpress'),
			]
		);

		register_setting( $this->plugin_name, $this->settings_prefix . '_general', array( $this, 'settings_general_sanitize' ) );
	}

	public function settings_general_callback() {
		echo '<p>' . __( 'General settings for SPID integration.', 'spid-wordpress' ) . '</p>';
	}

	public function settings_general_sanitize($input) {
		$values = array();

		if( isset( $input[$this->settings_prefix.'_registration'] ) ) {
			$values[$this->settings_prefix.'_registration'] = (int) $input[$this->settings_prefix.'_registration'];
		} else {
			$values[$this->settings_prefix.'_registration'] = $this->settings_defaults[$this->settings_prefix.'_registration'];
		}

		if( isset( $input[$this->settings_prefix.'_user_security_choice'] ) ) {
			$values[$this->settings_prefix.'_user_security_choice'] = (int) $input[$this->settings_prefix.'_user_security_choice'];
		} else {
			$values[$this->settings_prefix.'_user_security_choice'] = $this->settings_defaults[$this->settings_prefix.'_user_security_choice'];
		}

		return $values;
	}

	function spid_general_callback($args) {
		printf(
			'<p id="%s">%s</p>',
			$args['id'],
			$args['name']
		);
	}

	function settings_field_checkbox_callback($args) {
		if( ! isset( $args['default'] ) ) {
			$args['default'] = false;
		}

		$checked = spid_get_option( $args['option'], $args['default'] );
		?>

		<input type="checkbox" id="<?php echo $args['option'] ?>" value="1" name="<?php echo $args['label_for'] ?>" <?php checked($checked) ?> />
		<p class="description"><?php echo esc_html( $args['description'] ) ?></p>

		<?php
	}

}
