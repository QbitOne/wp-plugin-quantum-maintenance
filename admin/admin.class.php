<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://qbitone.de/
 * @since      1.0.0
 *
 * @package    Quaintenance
 * @subpackage Quaintenance/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Quaintenance
 * @subpackage Quaintenance/admin
 * @author     Andreas Geyer <andreas@qbitone.de>
 */
class Quaintenance_Admin
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
	 * The settings controller.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var Quaintenance_Setting The settings controller class.
	 */
	private $setting;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct($plugin_name, $version)
	{

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->setting = new Quaintenance_Setting($plugin_name, $version);
		$this->admin_bar = new Quaintenance_Admin_Bar($plugin_name, $version);
	}

	/**
	 * Register the stylesheets for the admin area.
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

		wp_enqueue_style($this->plugin_name, QUAINTENANCE_URL . 'admin/css/admin.css', array(), $this->version, 'all');
	}

	/**
	 * Register the JavaScript for the admin area.
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

		// wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/admin.js', array('jquery'), $this->version, false);
	}

	/**
	 * This function provides all actions which are used in the admin_menu hook.
	 * 
	 * This action is used to add extra submenus and menu options to the 
	 * admin panel’s menu structure. 
	 * It runs after the basic admin panel menu structure is in place.
	 *
	 * @return void
	 * @access public
	 * @since 1.0.0
	 */
	public function admin_menu(): void
	{
		$this->setting->add_menus();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 * @since 1.0.0
	 */
	public function admin_init(): void
	{
		$this->setting->register_settings();
		$this->setting->add_settings_objects();
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 * @since 1.1.0
	 */
	public function admin_bar_menu($wp_admin_bar): void
	{
		$options = $this->setting->get_option();
		if ($this->setting->valid_value('mode') === 'enabled') :
			$this->admin_bar->initialize($wp_admin_bar);
		endif;
	}
}
