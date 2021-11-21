<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://qbitone.de/
 * @since      1.0.0
 *
 * @package    Quaintenance
 * @subpackage Quaintenance/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Quaintenance
 * @subpackage Quaintenance/public
 * @author     Andreas Geyer <andreas@qbitone.de>
 */
class Quaintenance_Public
{

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Quaintenance_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Quaintenance_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/quaintenance-public.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts()
	{

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Quaintenance_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Quaintenance_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/quaintenance-public.js', array('jquery'), $this->version, false);
	}

	public function start_maintenance_mode(): void
	{
		// global $pagenow;
		// if ($pagenow !== 'wp-login.php' && !current_user_can('manage_options') && !is_admin()) {

		$option = get_option('quaintenance-main-option-name');

		$slug = $option['slug'] ?? '';
		$mode = $option['mode'] ?? '';

		if (!($mode === 'enabled')) return;
		if (is_admin() || current_user_can('manage_options')) return;

		if (empty($slug) || is_singular()) :
			$this->deliver_maintenance_mode();
			die();
		endif;
	}

	public function deliver_maintenance_mode(): void
	{
		header($_SERVER["SERVER_PROTOCOL"] . ' 503 Service Temporarily Unavailable', true, 503);
		header('Content-Type: text/html; charset=utf-8');

		if (file_exists(QUAINTENANCE_DIR . 'public/partials/public-display.php')) :
			require_once(QUAINTENANCE_DIR . 'public/partials/public-display.php');
		endif;
	}
}
