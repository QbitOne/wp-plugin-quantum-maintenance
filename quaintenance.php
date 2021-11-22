<?php

/**
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://qbitone.de/
 * @since             1.0.0
 * @package           Quaintenance
 *
 * @wordpress-plugin
 * Plugin Name:       Quaintenance
 * Plugin URI:        https://qbitone.de/
 * Description:       Custom Maintenance Mode
 * Version:           1.0.2
 * Author:            Andreas Geyer
 * Author URI:        https://qbitone.de/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       quaintenance
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
define('QUAINTENANCE_VERSION', '1.0.2');

define('QUAINTENANCE_DIR', trailingslashit(plugin_dir_path(__FILE__)));
define('QUAINTENANCE_URL', trailingslashit(plugin_dir_url(__FILE__)));

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/activator.class.php
 */
function activate_quaintenance()
{
	require_once QUAINTENANCE_DIR . 'includes/activator.class.php';
	Quaintenance_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/deactivator.class.php
 */
function deactivate_quaintenance()
{
	require_once QUAINTENANCE_DIR . 'includes/deactivator.class.php';
	Quaintenance_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_quaintenance');
register_deactivation_hook(__FILE__, 'deactivate_quaintenance');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require QUAINTENANCE_DIR . 'includes/quaintenance.class.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_quaintenance()
{
	$plugin = new Quaintenance();
	$plugin->run();
}
run_quaintenance();
