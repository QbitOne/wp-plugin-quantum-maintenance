<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://qbitone.de/
 * @since      1.0.0
 *
 * @package    Quantum_Maintenance
 * @subpackage Quantum_Maintenance/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Quantum_Maintenance
 * @subpackage Quantum_Maintenance/includes
 * @author     Andreas Geyer <andreas@qbitone.de>
 */
class Quantum_Maintenance_i18n
{

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain()
	{

		load_plugin_textdomain(
			'quantum-maintenance',
			false,
			dirname(dirname(QUANTUM_MAINTENANCE_DIR)) . '/languages/'
		);
	}
}
