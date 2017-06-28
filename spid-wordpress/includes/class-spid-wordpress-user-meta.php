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
	 * Another spawned settings from hell (TODO, to it well).
	 */
	private $settings;

	const SPID_DISABLED = 'spid_disabled';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		$this->settings = new Spid_Wordpress_Settings();
	}

	/**
	 * Brings into existence a magnificent settings page.
	 *
	 * @since    1.0.0
	 */
	public function add_user_settings_field( $user ) {
		?>

		<h3>SPID</h3>
		<table class="form-table">
			<tr>
				<th scope="row"><label for="spid_disabled"><?php echo __( "Untrust SPID", 'spid' ) ?></label></th>
				<td>
					<label for="spid_disabled">
						<?php if ( $this->settings->get_option_value( Spid_Wordpress_Settings::NO_USER_SECURITY_CHOICE ) ): ?>
							<input type="checkbox" id="spid_disabled" checked="checked" disabled="disabled"/>
							<?php echo __( "You can't disable SPID integration.", 'spid' ) ?>
						<?php else: ?>
							<?php $meta_value = self::get_user_has_disabled_spid($user->ID); ?>
							<input type="checkbox" id="spid_disabled" name="spid_disabled" value="1" <?php checked( $meta_value ) ?> />
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
		if ( ! $this->settings->get_option_value(Spid_Wordpress_Settings::NO_USER_SECURITY_CHOICE) && current_user_can( 'edit_user', $user_id )) {
			update_user_meta( $user_id, self::SPID_DISABLED, $_POST['spid_disabled'] );
		}
	}

	/**
	 * The user has disabled SPID?
	 *
	 * @return bool
	 */
	public static function get_user_has_disabled_spid( $user_id ) {
		return get_user_meta( $user_id, self::SPID_DISABLED, true );
	}

}
