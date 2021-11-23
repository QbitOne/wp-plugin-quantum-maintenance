<?php

/**
 * Update Checker Class
 *
 * @link       https://qbitone.de/
 * @since      1.0.0
 *
 * @package    Quaintenance
 * @subpackage Quaintenance/includes
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Update Checker Class
 *
 * @link       https://qbitone.de/
 * @since      1.0.0
 *
 * @package    Quaintenance
 * @subpackage Quaintenance/includes
 * @author Andreas Geyer <andreas@qbitone.de>
 */
if (!class_exists('Quaintenance_Update_Checker')) :
    /**
     * Update Checker Class
     */
    class Quaintenance_Update_Checker
    {
        /**
         * The ID of this plugin.
         *
         * @since    1.0.1
         * @access   private
         * @var      string    $plugin_name    The ID of this plugin.
         */
        private $plugin_name;

        /**
         * The version of this plugin.
         *
         * @since    1.0.1
         * @access   private
         * @var      string    $version    The current version of this plugin.
         */
        private $version;

        /**
         * Vendor file for update checker
         *
         * @var string
         */
        private $vendor_file = QUAINTENANCE_DIR . 'vendor/yahnis-elsts/plugin-update-checker/plugin-update-checker.php';

        /**
         * Remote path for the theme meta data
         *
         * @var string
         */
        private $remote_path = 'https://qbitone.de/quaintenance.json';

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

            $this->init();
        }

        /**
         * Initialize the vendor update checker.
         *
         * @return void
         * @since 1.0.1
         */
        public function init(): void
        {
            if (file_exists($this->vendor_file)) {

                require $this->vendor_file;

                $myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
                    $this->remote_path,
                    QUAINTENANCE_DIR . 'quaintenance.php',
                    'quaintenance'
                );

                if (defined('QT_ENV') && QT_ENV === 'dev') :
                    add_action('admin_notices', [$this, 'action_admin_notices_activ_plugin_checker']);
                endif;
            } else {
                add_action('admin_notices', [$this, 'action_admin_notices_no_composer']);
            }
        }

        /**
         * Prints admin screen notices.
         * 
         * @param none
         * @return void
         * @since 1.0.0
         */
        function action_admin_notices_no_composer(): void
        {
            echo '<div class="notice notice-info is-dismissible">';
            printf('<p>Composer ist nicht installiert! (%s v%s)</p>', $this->plugin_name, $this->version);
            echo '</div>';
        }

        /**
         * Prints admin screen notices.
         * 
         * @param none
         * @return void
         * @since 1.0.0
         */
        function action_admin_notices_activ_plugin_checker(): void
        {
            echo '<div class="notice notice-info is-dismissible">';
            printf('<p>Update Checker ist aktiv! (%s v%s)</p>', $this->plugin_name, $this->version);
            echo '</div>';
        }
    }
endif;
