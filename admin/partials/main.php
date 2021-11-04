<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://qbitone.de/
 * @since      1.0.0
 *
 * @package    Quaintenance
 * @subpackage Quaintenance/admin/partials
 */

if (!defined('ABSPATH')) exit; // Exit if accessed directly
?>

<div class="wrap">
    <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
    <?php settings_errors(); ?>
    <form action="options.php" method="post">

        <?php

        // output security fields (hidden setting fields)
        settings_fields(self::$main_option_group); // should match the value defined in register_setting()

        // output setting sections
        do_settings_sections(self::$main_page_slug);

        submit_button();

        ?>

    </form>
</div>