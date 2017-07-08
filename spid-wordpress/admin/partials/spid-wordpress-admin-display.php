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
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       asd.asd.asd
 * @since      1.0.0
 *
 * @package    Spid_Wordpress
 * @subpackage Spid_Wordpress/admin/partials
 */

// If this file is called directly, abort.
defined( 'WPINC' ) or die;

?>
<div class="wrap">
	<h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
	<?php
	if (
		! isset( $this ) ||
		! is_object( $this ) ||
		! isset( $this->settings ) ||
		! is_object( $this->settings ) ||
		! method_exists( $this->settings, 'is_plugin_configured_correctly' )
	) {
		throw new LogicException( 'Can\'t check if configuration is correct' );
	}
	/** @var $this ->settings Spid_Wordpress_Settings */
	if ( ! $this->settings->is_plugin_configured_correctly() ) {
		echo '<div class="error notice"><p>';
		if ( ! file_exists( WP_SIMPLESAML_DIR ) ) {
			echo __( sprintf( 'Warning: supplied path (%s) for SimpleSpidPhp library is incorrect, edit WP_SIMPLESAML_DIR', WP_SIMPLESAML_DIR ), 'spid-wordpress' );
		} else if ( file_exists( WP_SIMPLESAML_DIR . DIRECTORY_SEPARATOR . WP_SIMPLESAML_AUTOLOADER_FILE ) ) {
			echo __( sprintf( 'Warning: supplied path (%s) for SimpleSpidPhp autoloader is incorrect, edit WP_SIMPLESAML_DIR and WP_SIMPLESAML_AUTOLOADER_FILE', WP_SIMPLESAML_DIR . DIRECTORY_SEPARATOR . WP_SIMPLESAML_AUTOLOADER_FILE ), 'spid-wordpress' );
		} else {
			echo __( sprintf( 'Warning: found SimpleSpidPhp autoloader in %s, but loading failed', WP_SIMPLESAML_DIR . DIRECTORY_SEPARATOR . WP_SIMPLESAML_AUTOLOADER_FILE ), 'spid-wordpress' );
		}
		echo '</p></div>';
	} ?>
	<form action="options.php" method="post">
		<?php
		// Output security fields for the registered option group
		settings_fields( Spid_Wordpress::PLUGIN_NAME );

		// Call sections of registered option group
		do_settings_sections( Spid_Wordpress::PLUGIN_NAME );

		// Save button
		submit_button( __( "Save Settings", 'spid-wordpress' ) ); //TODO: parametri addizionali a random?
		?>
	</form>
</div>
