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

class Spid_Wordpress_Settings {
	private $settings_prefix;
	private $settings_general;
	private $settings_defaults;

	private $settings;

	const USER_REGISTRATION = 'registration';

	/**
	 * true when enforcing SPID logins and no choice exists but to become part of the Borg Collective.
	 */
	const NO_USER_SECURITY_CHOICE = 'user_security_choice';

	// Path where simplesamlphp is installed
	const SIMPLESAMLPHP_PATH = 'simplesamlphp_path';

	// Authentication source name
	const SIMPLESAMLPHP_AUTHSOURCE = 'simplesamlphp_authsource';

	// UserID Attribute.
	const SIMPLESAMLPHP_UIDATTRIBUTE = 'simplesamlphp_uidattribute';

	function __construct() {
		$this->settings_prefix   = Spid_Wordpress::PLUGIN_NAME . '_settings';
		$this->settings_general  = $this->settings_prefix . '_general';

		$this->settings_defaults = array(
			// As default, users can choose to disable their SPID integration
			self::NO_USER_SECURITY_CHOICE => 0,

			// As default, SPID can register new users
			self::USER_REGISTRATION => 1,

			// @TODO: Why this should be database-definable?
			self::SIMPLESAMLPHP_AUTHSOURCE => WP_SIMPLESAML_AUTHSOURCE,

			// @TODO: Why this should be database-definable?
			self::SIMPLESAMLPHP_PATH => WP_SIMPLESAML_DIR . DIRECTORY_SEPARATOR . WP_SIMPLESAML_AUTOLOADER_FILE,

			// @TODO: What is this?
			self::SIMPLESAMLPHP_UIDATTRIBUTE => '-'
		);

		$this->settings = get_option( $this->get_group_id(), $this->settings_defaults );
	}

	public function get_group_id() {
		return $this->settings_general;
	}

	/**
	 * Restituisce il nome della label da usare nella pagina opzioni.
	 * Non ha senso che stia qui, ma usa il prefix invece del group_id e fare una funzione pubblica che restituisce
	 * il prefix aggiungeva solo caos e confusione. Almeno la distinzione tra group_id e label_id è autoevidente
	 * (uno si usa per le label, l'altro per tutto il resto)
	 *
	 * @param $option string nome dell'opzione (una costante di questa classe)
	 *
	 * @return string label
	 */
	function get_label_id( $option ) {
		return $this->settings_prefix . '_' . $option;
	}

	function get_option_value( $option ) {

		if ( isset( $this->settings[ $option ] ) ) {
			// Option supported and set by the user
			return $this->settings[ $option ];
		} else if( isset ( $this->settings_defaults[ $option ] ) ) {
			// Option supported but never set by the user
			return $this->settings_defaults[ $option ];
		} else {
			throw new LogicException( 'Option ' . $option . ' unsupported!' );
		}
	}
}
