<?php

/**
 * The file that defines the core plugin class.
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://qbitone.de/
 * @since      1.0.0
 *
 * @package    Quaintenance
 * @subpackage Quaintenance/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Quaintenance
 * @subpackage Quaintenance/includes
 * @author     Andreas Geyer <andreas@qbitone.de>
 */
class Quaintenance
{
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Quaintenance_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The update checker for this plugin.
	 *
	 * @var Quaintenance_Update_Checker $update_checker The update checker for this plugin.
	 */
	protected $update_checker;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct()
	{
		if (defined('QUAINTENANCE_VERSION')) {
			$this->version = QUAINTENANCE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'quaintenance';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->init_update_checker();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Quaintenance_Loader. Orchestrates the hooks of the plugin.
	 * - Quaintenance_i18n. Defines internationalization functionality.
	 * - Quaintenance_Admin. Defines all hooks for the admin area.
	 * - Quaintenance_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once QUAINTENANCE_DIR . 'includes/loader.class.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once QUAINTENANCE_DIR . 'includes/i18n.class.php';

		/**
		 * The class responsible for plugin updates.
		 */
		require_once QUAINTENANCE_DIR . 'includes/update-checker.class.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once QUAINTENANCE_DIR . 'admin/admin.class.php';

		/**
		 * The class responsible for defining all settings that occur in the admin area.
		 */
		require_once QUAINTENANCE_DIR . 'admin/settings.class.php';

		/**
		 * The class responsible for adding admin bar menus
		 */
		require_once QUAINTENANCE_DIR . 'admin/admin-bar.class.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once QUAINTENANCE_DIR . 'public/public.class.php';


		$this->loader = new Quaintenance_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Quaintenance_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Quaintenance_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Quaintenance_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		$this->loader->add_action('admin_menu', $plugin_admin, 'admin_menu');
		$this->loader->add_action('admin_init', $plugin_admin, 'admin_init');

		$this->loader->add_action('admin_bar_menu', $plugin_admin, 'admin_bar_menu');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Quaintenance_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		$this->loader->add_action('wp', $plugin_public, 'start_maintenance_mode');
		// $this->loader->add_action('wp_loaded', $plugin_public, 'start_maintenance_mode');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Quaintenance_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}

	public function init_update_checker()
	{
		$this->update_checker = new Quaintenance_Update_Checker($this->get_plugin_name(), $this->get_version());
	}
}
