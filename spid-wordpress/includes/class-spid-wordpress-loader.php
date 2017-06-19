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
 * Register all actions and filters for the plugin
 *
 * @link       asd.asd.asd
 * @since      1.0.0
 *
 * @package    Spid_Wordpress
 * @subpackage Spid_Wordpress/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Spid_Wordpress
 * @subpackage Spid_Wordpress/includes
 * @author     Ludovico Pavesi, Valerio Bozzolan, spid-wordpress contributors
 */
class Spid_Wordpress_Loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array $actions The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array $filters The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();

	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 *
	 * @param    string $hook The name of the WordPress action that is being registered.
	 * @param    object $component A reference to the instance of the object on which the action is defined.
	 * @param    string $callback The name of the function definition on the $component.
	 * @param    int $priority Optional. he priority at which the function should be fired. Default is 10.
	 * @param    int $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 *
	 * @param    string $hook The name of the WordPress filter that is being registered.
	 * @param    object $component A reference to the instance of the object on which the filter is defined.
	 * @param    string $callback The name of the function definition on the $component.
	 * @param    int $priority Optional. he priority at which the function should be fired. Default is 10.
	 * @param    int $accepted_args Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 *
	 * @param    array $hooks The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string $hook The name of the WordPress filter that is being registered.
	 * @param    object $component A reference to the instance of the object on which the filter is defined.
	 * @param    string $callback The name of the function definition on the $component.
	 * @param    int $priority The priority at which the function should be fired.
	 * @param    int $accepted_args The number of arguments that should be passed to the $callback.
	 *
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {

		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array(
				$hook['component'],
				$hook['callback']
			), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array(
				$hook['component'],
				$hook['callback']
			), $hook['priority'], $hook['accepted_args'] );
		}

	}

}
