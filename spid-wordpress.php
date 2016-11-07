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

/*
Plugin Name: SPID-Wordpress
Plugin URI: http://
Description: Fa cose con SPID
Version: 1.0.0
Author: Ludovico Pavesi, Valerio Bozzolan, spid-wordpress contributors
Author URI: http://
License: GPLv3+
*/

/**
 * Custom options and settings
 */
function wp_spid_init() {

}



/**
 * Top level menu
 */
function spid_menu_page() {
	add_menu_page(
		__("General Settings", 'spid'),
		"SPID",
		'manage_options',
		'spid',
		'spid_options_page_html'
	);
}
add_action('admin_menu', 'spid_menu_page');

/**
 * Site option page
 */
function spid_options_page_html() {
	if( ! current_user_can('manage_options') ) {
		return;
	}

	// Check if the user have submitted the settings
	// Wordpress will add the "settings-updated" $_GET parameter to the url
	if( isset( $_GET['settings-updated'] ) ) {
		// Saved message
		add_settings_error('spid_messages', 'spid_message', __("SPID settings saved!", 'spid'), 'updated');
	}

	// Show error/update messages
	settings_errors('spid_messages');

	?>

	<div class="wrap">
		<h2><?php echo esc_html( get_admin_page_title() ) ?></h2>
		<form action="options.php" method="post">
			<?php
			// Output security fields for the registered option group
			settings_fields('spid');

			// Call sections of registered option group
			do_settings_sections('spid');

			// Save button
			submit_button( __("Save Settings", 'spid') );
			?>
		</form>
	</div>

	<?php
}

function spid_extra_profile_fields($user) {
	$meta_value = get_user_meta($user->ID, 'spid_disabled', true);
	?>

	<h3>SPID</h3>
	<table class="form-table">
	<tr>
		<th scope="row"><label for="spid_disabled"><?php echo __("Untrust SPID", 'spid') ?></label></th>
		<td>
			<label for="spid_disabled">
				<?php if( spid_get_option('user_security_choice') ): ?>
					<input type="checkbox" id="spid_disabled" checked="checked" disabled="disabled" />
					<?php echo __("You can't disable SPID integration.", 'spid') ?>
				<?php else: ?>
					<input type="checkbox" id="spid_disabled" name="spid_disabled" value="1" <?php checked($meta_value) ?> />
					<?php echo __("Disable SPID integration. Check this if you don't trust SPID authorities.", 'spid') ?>
				<?php endif ?>
			</label>
		</td>
	</tr>
	</table>

	<?php
}
add_action('profile_personal_options', 'spid_extra_profile_fields');

function spid_update_extra_profile_fields($user_id) {
	if( ! spid_get_option('user_security_choice') && current_user_can('edit_user', $user_id) ) {
		update_user_meta($user_id, 'spid_disabled', $_POST['spid_disabled']);
	}
}

add_action('personal_options_update',  'spid_update_extra_profile_fields');
//add_action('edit_user_profile_update', 'spid_update_extra_profile_fields');

function spid_get_option($killer, $default) {
	$serial = get_option('spid_options');
	return isset( $serial[ $killer] ) ? $serial[ $killer ] : $default;
}

function spid_options_sanitize($asd) {
	return $asd;
}

add_action('admin_init', 'wp_spid_init');
