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
Version: 0.1
Author:
Author URI: http://
License: GPLv3+
*/

/**
 * Custom options and settings
 */
function wp_spid_init() {
	register_setting(
		// Settings group name.
		// Must exist prior to the register_setting call.
		// This must match the group name in settings_fields()
		'spid',

		// The name of an option to sanitize and save.
		'spid_options',

		'spid_options_sanitize'
	);

	add_settings_section(
		// String for use in the 'id' attribute of tags
		'spid_section_general',

		// Title of the section
		null, // __("Website", 'spid'),

		// Callback
		'spid_section_title_callback',

		// The menu page on which to display this section. Should match $menu_slug from Function Reference/add theme page
		'spid'
	);

	add_settings_field(
		// String for use in the 'id' attribute of tags
		'spid_field_registration',

		// Title of the field
		__("Registration", 'spid'),

		// Function that fills the field with the desired inputs as part of the larger form.
		// assed a single argument, the $args array.
		// Name and id of the input should match the $id given to this function.
		// The function should echo its output.
		'spid_field_checkbox_callback',

		// The menu page on which to display this field.
		// Should match $menu_slug from add_theme_page() or from do_settings_sections().
		'spid',

		// The section of the settings page in which to show the box
		// (default or a section you added with add_settings_section(),
		// look at the page in the source to see what the existing ones are.)
		'spid_section_general',

		// Additional arguments that are passed to the $callback function.
		// The 'label_for' key/value pair can be used to format the field title like so: <label for="value">$title</label>.
		[
			'label_for'    => 'spid_field_registration',
			'option'       => 'registration',
			'class'        => 'spid_row',
			'description'  => __("New users can be registered by SPID authorities.", 'spid'),
		]
	);

	add_settings_field(
		// String for use in the 'id' attribute of tags
		'spid_field_user_security_choice',

		// Title of the field
		__("Force SPID integration", 'spid'),

		// Function that fills the field with the desired inputs as part of the larger form.
		// assed a single argument, the $args array.
		// Name and id of the input should match the $id given to this function.
		// The function should echo its output.
		'spid_field_checkbox_callback',

		// The menu page on which to display this field.
		// Should match $menu_slug from add_theme_page() or from do_settings_sections().
		'spid',

		// The section of the settings page in which to show the box
		// (default or a section you added with add_settings_section(),
		// look at the page in the source to see what the existing ones are.)
		'spid_section_general',

		// Additional arguments that are passed to the $callback function.
		// The 'label_for' key/value pair can be used to format the field title like so: <label for="value">$title</label>.
		[
			'label_for'    => 'spid_field_user_security_choice',
			'option'       => 'user_security_choice',
			'class'        => 'spid_row',
			'description'  => __("Leave this option unchecked if you care about user choice. Not all users may appreciate SPID centralization.", 'spid'),
		]
	);
}

function spid_section_title_callback($args) {
	printf(
		'<p id="%s">%s</p>',
		$args['id'],
		$args['name']
	);
}

function spid_field_checkbox_callback($args) {
	if( ! isset( $args['default'] ) ) {
		$args['default'] = false;
	}

	$checked = spid_get_option( $args['option'], $args['default'] );
	?>

	<input type="checkbox" id="<?php echo $args['label_for'] ?>" value="1" name="spid_options[<?php echo $args['option'] ?>]" <?php checked($checked) ?> />

	<p class="description"><?php echo esc_html( $args['description'] ) ?></p>

	<?php
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
