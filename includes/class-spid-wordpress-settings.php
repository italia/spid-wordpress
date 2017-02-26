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

class Spid_Wordpress_Settings {
	private $settings_prefix;
	private $settings_general;
	private $settings_defaults;

	private $settings;
	const USER_REGISTRATION    = 'registration';
	const USER_SECURITY_CHOICE = 'user_security_choice';

	function __construct($plugin_name) {
		$this->settings_prefix = $plugin_name.'_settings';
		$this->settings_general = $this->settings_prefix . '_general';
		$this->settings_defaults = array(
			self::USER_SECURITY_CHOICE => 0,
			self::USER_REGISTRATION    => 1
		);
		$this->settings = get_option($this->get_group_id(), $this->settings_defaults);
	}

	public function get_group_id() {
		return $this->settings_general;
	}

	function get_label_id($option) {
		return $this->settings_prefix . '_' . $option;
	}

	function get_option_value($option) {
		if(isset($this->settings[$option])) {
			return $this->settings[$option];
		} else {
			// TODO: fare qualcosa di sensato (o lasciare questo se è abbastanza sensato)
			throw new LogicException('Option '.$option.' unsupported!');
		}
	}
}