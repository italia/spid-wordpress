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
 * The admin-specific functionality of the plugin.
 *
 * @since      1.0.0
 * @package    Spid_Wordpress
 * @subpackage Spid_Wordpress/admin
 * @author     Ludovico Pavesi, Valerio Bozzolan, spid-wordpress contributors
 */
class Spid_Wordpress_User_Meta {
	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Hook suffix for the options page.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      false|string $options_page_hook_suffix The hook suffix for the options page.
	 */
	private $options_page_hook_suffix = false;

	/**
	 * Another spawned settings from hell (TODO, to it well).
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $plugin_name The ID of this plugin.
	 */
	private $settings;

	const SPID_DISABLED = 'spid_disabled';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 *
	 * @param      string $plugin_name The name of this plugin.
	 * @param      string $version The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->settings    = new Spid_Wordpress_Settings( $plugin_name );
		$this->version     = $version;
	}

	/**
	 * Brings into existence a magnificent settings page.
	 *
	 * @since    1.0.0
	 */
	public function add_user_settings_field( $user ) {
		$meta_value = get_user_meta( $user->ID, self::SPID_DISABLED, true );
		?>

		<h3>SPID</h3>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="spid_disabled"><?php echo __( "Untrust SPID", 'spid' ) ?></label></th>
				<td>
					<label for="spid_disabled">
						<?php if ( $this->settings->get_option_value( Spid_Wordpress_Settings::USER_SECURITY_CHOICE ) ): ?>
							<input type="checkbox" id="spid_disabled" checked="checked" disabled="disabled"/>
							<?php echo __( "You can't disable SPID integration.", 'spid' ) ?>
						<?php else: ?>
							<input type="checkbox" id="spid_disabled" name="spid_disabled"
							       value="1" <?php checked( $meta_value ) ?> />
							<?php echo __( "Disable SPID integration. Check this if you don't trust SPID authorities.", 'spid' ) ?>
						<?php endif ?>
					</label>
				</td>
			</tr>
		</table>

		<?php
	}

	/**
	 * Manipulate the POST options in the user multiverse of asdding personalizations(C) <- copyright sign.
	 *
	 * @since    1.0.0
	 */
	public function personal_options_update( $user_id ) {
		if ( ! $this->get_user_security_choice($user_id) ) {
			update_user_meta( $user_id, 'spid_disabled', $_POST['spid_disabled'] );
		}
	}

	public function get_user_security_choice( $user_id ) {
		return $this->settings->get_option_value( Spid_Wordpress_Settings::USER_SECURITY_CHOICE ) && current_user_can( 'edit_user', $user_id );
	}

}
