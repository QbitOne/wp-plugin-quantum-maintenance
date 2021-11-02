<?php

/**
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://qbitone.de/
 * @since             1.0.0
 * @package           Quantum_Maintenance
 *
 * @wordpress-plugin
 * Plugin Name:       Quantum Maintenance
 * Plugin URI:        https://qbitone.de/
 * Description:       Custom Maintenance Mode
 * Version:           1.0.0
 * Author:            Andreas Geyer
 * Author URI:        https://qbitone.de/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       quantum-maintenance
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * @link https://semver.org
 */
define('QUANTUM_MAINTENANCE_VERSION', '1.0.0');

define('QUANTUM_MAINTENANCE_DIR', trailingslashit(plugin_dir_path(__FILE__)));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-quantum-maintenance-activator.php
 */
function activate_quantum_maintenance()
{
	require_once QUANTUM_MAINTENANCE_DIR . 'includes/class-quantum-maintenance-activator.php';
	Quantum_Maintenance_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-quantum-maintenance-deactivator.php
 */
function deactivate_quantum_maintenance()
{
	require_once QUANTUM_MAINTENANCE_DIR . 'includes/class-quantum-maintenance-deactivator.php';
	Quantum_Maintenance_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_quantum_maintenance');
register_deactivation_hook(__FILE__, 'deactivate_quantum_maintenance');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require QUANTUM_MAINTENANCE_DIR . 'includes/class-quantum-maintenance.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_quantum_maintenance()
{
	$plugin = new Quantum_Maintenance();
	$plugin->run();
}
run_quantum_maintenance();
