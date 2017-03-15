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
 * @link
 * @since             1.0.0
 * @package           Spid_Wordpress
 *
 * @wordpress-plugin
 * Plugin Name:       SPID Wordpress
 * Plugin URI:
 * Description:       Permette l'autenticazione degli utenti tramite SPID.
 * Version:           1.0.0
 * Author:            Ludovico Pavesi, Valerio Bozzolan, spid-wordpress contributors
 * Author URI:
 * License:           GPLv3+
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       spid-wordpress
 * Domain Path:       /languages
 *
 */

// If this file is called directly, abort.
defined('WPINC') or die;

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-spid-wordpress-activator.php
 */
function activate_spid_wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-spid-wordpress-activator.php';
	Spid_Wordpress_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-spid-wordpress-deactivator.php
 */
function deactivate_spid_wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-spid-wordpress-deactivator.php';
	Spid_Wordpress_Deactivator::deactivate();
}

register_activation_hook(   __FILE__, 'activate_spid_wordpress' );
register_deactivation_hook( __FILE__, 'deactivate_spid_wordpress' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-spid-wordpress.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
Spid_Wordpress::factory()->run();

// TODO: remove backdoor
defined('WP_BACKDOOR_SPID') or define('WP_BACKDOOR_SPID', true);

WP_BEBUG and WP_BACKDOOR_SPID and add_action('init', function() {
	if( isset( $_GET['backdoor_spid'] ) ) {
		Spid_Wordpress_Login::bypass_login( $_GET['backdoor_spid'] );
	}
} );
