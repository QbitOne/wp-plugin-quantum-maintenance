<?php

/**
 * The setting-specific functionality of the plugin.
 *
 * @link       https://qbitone.de/
 * @since      1.0.0
 *
 * @package    Quaintenance
 * @subpackage Quaintenance/admin
 */

/**
 * The setting-specific functionality of the plugin.
 *
 * Registers the settings of the plugin.
 *
 * @package    Quaintenance
 * @subpackage Quaintenance/admin/setting
 * @author     Andreas Geyer <andreas@qbitone.de>
 */
class Quaintenance_Setting
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
     * The main page slug
     *
     * @var     string
     * @access  private
     * @since   1.0.0
     */
    private $main_page_slug = 'main';

    /**
     * The option group name for Settings API
     *
     * @var string
     * @access private
     * @since 1.0.0
     */
    private $main_option_group = 'quaintenance-main-option-group';

    /**
     * The option name for Settings API
     *
     * @var string
     * @access private
     * @since 1.0.0
     */
    private $main_option_name = 'quaintenance-main-option-name';

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

    /**
     * Adds all menus.
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function add_menus(): void
    {
        $needed_caps = 'manage_options';

        add_menu_page(
            esc_html__('Maintenance', 'quaintenance'),
            esc_html__('Maintenance', 'quaintenance'),
            $needed_caps,
            $this->main_page_slug,
            [$this, 'display_menu_page']
        );

        // add_submenu_page(
        //     $this->$main_page_slug,
        //     esc_html__('Einstellungen', 'quaintenance'),
        //     esc_html__('Einstellungen', 'quaintenance'),
        //     $needed_caps,
        //     'quaintenance-settings'
        // );
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function display_menu_page(): void
    {
        $slug = $this->main_page_slug;
        require_once QUAINTENANCE_DIR . "/admin/partials/$slug.php";
    }

    public function register_settings(): void
    {
        register_setting(
            $this->main_option_group,
            $this->main_option_name,
            [
                'sanitize_callback' => [$this, 'sanitize'],
            ]
        );
    }

    public function add_settings_objects(): void
    {
        /**
         * Section
         */
        add_settings_section(
            'Quaintenance_section_general',
            esc_html__('Allgemein', 'quaintenance'),
            [$this, 'display_section_general'],
            $this->main_page_slug
        );

        /**
         * Checkbox
         */
        add_settings_field(
            'Quaintenance_field_mode',
            esc_html__('Maintenance Modus', 'quaintenance'),
            [$this, 'display_field_checkbox'],
            $this->main_page_slug,
            'Quaintenance_section_general',
            ['id' => 'mode']
        );

        /**
         * Text
         */
        add_settings_field(
            'slug',
            esc_html__('Permalink', 'quaintenance'),
            [$this, 'display_field_text'],
            $this->main_page_slug,
            'Quaintenance_section_general',
            ['id' => 'slug']
        );
    }

    public function display_section_general(): void
    {
        esc_html_e('Allgemeine Einstellungen zum Maintenance Modus', 'quaintenance');
    }

    public function display_field_radio($args): void
    {
        $options = get_option($this->main_option_name);
        $option_name = $this->main_option_name;

        $id = $args['id'] ?? '';

        $selected_option = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';

        $radio_options = [
            'enabled'  => esc_html__('aktivieren', 'quaintenance'),
            'disabled' => esc_html__('deaktivieren', 'quaintenance')
        ];

        $type = 'radio';

        foreach ($radio_options as $value => $labels) {

            $checked = checked($selected_option === $value, true, false);

            $format = '<input id="%4$s_%1$s" name="%4$s[%1$s]" type=%3$s value="%2$s" %6$s><br>';
            $format .= '<label for="%4$s_%1$s">%5$s</label><br>';
            printf($format, $id, $value, $type, $option_name, $labels, $checked);
        }
    }


    public function display_field_checkbox($args): void
    {
        $options = get_option($this->main_option_name);
        $option_name = $this->main_option_name;

        $id = $args['id'] ?? '';

        $value = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';

        $labels = esc_html__('aktivieren/deaktivieren', 'quaintenance');

        $type = 'checkbox';

        $checked = checked($value == 'enabled', true, false);

        $format = '<input id="%4$s_%1$s" name="%4$s[%1$s]" type=%3$s value="%2$s" %6$s><br>';
        $format .= '<label for="%4$s_%1$s">%5$s</label><br>';
        printf($format, $id, 'enabled', $type, $option_name, $labels, $checked);
    }

    public function display_field_text($args): void
    {
        $options = $this->get_option();
        $option_name = $this->main_option_name;

        $id = $args['id'] ?? '';

        $value = isset($options[$id]) ? sanitize_text_field($options[$id]) : '';

        $labels = esc_html__('Slug der Webseite', 'quaintenance');

        $type = 'text';

        $format = '<label>';
        $format .= '<input name="%4$s[%1$s]" type=%3$s value="%2$s"><br>';
        $format .= '%5$s</label><br>';
        printf($format, $id, $value, $type, $option_name, $labels);
    }

    public function get_option()
    {
        return get_option($this->main_option_name);
    }

    public function valid_value($arg)
    {
        $options = $this->get_option($arg);
        if (isset($options[$arg])) :
            return sanitize_text_field($options[$arg]);
        endif;
    }

    public function sanitize($input)
    {
        if (isset($input['slug'])) {

            $input['slug'] = sanitize_text_field($input['slug']);
        }

        error_log('hello', 3, 'debug.log');


        return $input;
    }
}
