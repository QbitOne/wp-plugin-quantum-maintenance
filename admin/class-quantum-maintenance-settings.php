<?php

/**
 * The setting-specific functionality of the plugin.
 *
 * @link       https://qbitone.de/
 * @since      1.0.0
 *
 * @package    Quantum_Maintenance
 * @subpackage Quantum_Maintenance/admin/setting
 */

/**
 * The setting-specific functionality of the plugin.
 *
 * Registers the settings of the plugin.
 *
 * @package    Quantum_Maintenance
 * @subpackage Quantum_Maintenance/admin/setting
 * @author     Andreas Geyer <andreas@qbitone.de>
 */
class Quantum_Maintenance_Setting
{
    /**
     * The main page slug
     *
     * @var     string
     * @access  private
     * @since   1.0.0
     */
    private static $main_page_slug = 'quantum-maintenance-main';

    private static $main_option_group = 'quantum-maintenance-main-option-group';
    private static $main_option_name = 'quantum-maintenance-main-option-name';

    /**
     * Initialize the class and set its properties.
     *
     * @since   1.0.0
     * @param   string    $plugin_name       The name of this plugin.
     * @param   string    $version    The version of this plugin.
     */
    // public function __construct($plugin_name, $version)
    // {
    //     $this->plugin_name = $plugin_name;
    //     $this->version = $version;
    // }

    /**
     * Adds all menus.
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public static function add_menus(): void
    {
        $needed_caps = 'manage_options';

        add_menu_page(
            esc_html__('Maintenance', 'quantum-maintenance'),
            esc_html__('Maintenance', 'quantum-maintenance'),
            $needed_caps,
            self::$main_page_slug,
            ['Quantum_Maintenance_Setting', 'display_menu_page']
        );

        // add_submenu_page(
        //     self::$main_page_slug,
        //     esc_html__('Einstellungen', 'quantum-maintenance'),
        //     esc_html__('Einstellungen', 'quantum-maintenance'),
        //     $needed_caps,
        //     'quantum-maintenance-settings'
        // );
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function display_menu_page(): void
    {
        $slug = self::$main_page_slug;
        require_once QUANTUM_MAINTENANCE_DIR . "/admin/partials/$slug.php";
    }

    public static function register_settings(): void
    {
        register_setting(
            self::$main_option_group,
            self::$main_option_name
        );
    }

    public static function add_settings_objects(): void
    {
        add_settings_section(
            'quantum_maintenance_section_general',
            esc_html__('Allgemein', 'quantum-maintenance'),
            ['Quantum_Maintenance_Setting', 'display_section_general'],
            self::$main_page_slug
        );

        add_settings_field(
            'quantum_maintenance_field_mode',
            esc_html__('Maintenance Modus', 'quantum-maintenance'),
            ['Quantum_Maintenance_Setting', 'display_field_checkbox'],
            self::$main_page_slug,
            'quantum_maintenance_section_general',
            ['id' => 'mode']
        );
    }

    public static function display_section_general(): void
    {
        esc_html_e('Allgemeine Einstellungen zum Maintenance Modus', 'quantum-maintenance');
    }

    public static function display_field_radio($args): void
    {
        $options = get_option(self::$main_option_name);
        $option_name = self::$main_option_name;

        $id = $args['id'] ?? '';

        $selected_option = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';

        $radio_options = [
            'enabled'  => esc_html__('aktivieren', 'quantum-maintenance'),
            'disabled' => esc_html__('deaktivieren', 'quantum-maintenance')
        ];

        $type = 'radio';

        foreach ($radio_options as $value => $labels) {

            $checked = checked($selected_option === $value, true, false);

            $format = '<input id="%4$s_%1$s" name="%4$s[%1$s]" type=%3$s value="%2$s" %6$s><br>';
            $format .= '<label for="%4$s_%1$s">%5$s</label><br>';
            printf($format, $id, $value, $type, $option_name, $labels, $checked);
        }
    }


    public static function display_field_checkbox($args): void
    {
        $options = get_option(self::$main_option_name);
        $option_name = self::$main_option_name;

        $id = $args['id'] ?? '';

        $value = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';

        $labels = esc_html__('aktivieren/deaktivieren', 'quantum-maintenance');

        $type = 'checkbox';

        $checked = checked($value == 'enabled', true, false);

        $format = '<input id="%4$s_%1$s" name="%4$s[%1$s]" type=%3$s value="%2$s" %6$s><br>';
        $format .= '<label for="%4$s_%1$s">%5$s</label><br>';
        printf($format, $id, 'enabled', $type, $option_name, $labels, $checked);
    }
}
