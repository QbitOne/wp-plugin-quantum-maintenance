<?php

/**
 * The admin-bar-specific functionality of the plugin.
 *
 * @link       https://qbitone.de/
 * @since      1.1.0
 *
 * @package    Quaintenance
 * @subpackage Quaintenance/admin/adminbar
 */

/**
 * The admin-bar-specific functionality of the plugin.
 *
 * Registers the admin bar of the plugin.
 *
 * @package    Quaintenance
 * @subpackage Quaintenance/admin/adminbar
 * @author     Andreas Geyer <andreas@qbitone.de>
 */
class Quaintenance_Admin_Bar
{
    /**
     * The ID of this plugin.
     *
     * @since    1.1.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.1.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @param   string    $plugin_name       The name of this plugin.
     * @param   string    $version    The version of this plugin.
     * @since   1.0.1
     */
    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
}
