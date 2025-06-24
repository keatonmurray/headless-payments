<?php

namespace  HP\Admin;

/**
 * Class SettingsPage
 *
 * Handles the creation of the admin settings page for the Headless Payments plugin.
 * 
 * This class:
 * - Adds a new menu item ("Headless Payments") to the WordPress admin sidebar.
 * - Registers plugin settings for storing PayPal API credentials and mode (sandbox/live).
 * - Renders a form UI to allow the site admin to manage payment gateway configuration.
 *
 * The settings saved here are used by the payment gateway service (e.g. PayPalGateway)
 * and exposed internally for processing payments via REST API.
 *
 * This class is only initialized when in the WordPress admin (`is_admin()` check in Plugin.php).
 *
 * @package HP\Admin
 */

class SettingsPage {
    public function register()
    {
        add_action('admin_menu', [$this, 'add_menu_page']);
        add_action('admin_init', [$this, 'register_settings']);
        $this->enqueue_assets();
    }

    public function add_menu_page() {
        add_menu_page(
            'Headless Payments',
            'Headless Payments',
            'manage_options',
            'headless-payments',
            [$this, 'render_settings_page'],
            'dashicons-cart'
        );
    }

    public function register_settings() {
        register_setting('hp_settings_group', 'hp_paypal_mode');
        register_setting('hp_settings_group', 'hp_paypal_client_id');
        register_setting('hp_settings_group', 'hp_paypal_secret');
    }

    public function render_settings_page() {
        $view_path = plugin_dir_path(__FILE__) . '../../src/Views/admin-settings-page.php';
        if (file_exists($view_path)) {
            include $view_path;
        } else {
            echo '<div class="notice notice-error"><p>Settings view not found.</p></div>';
        }
    }

    public function enqueue_assets() {
        add_action('admin_enqueue_scripts', function ($hook) {
        if (strpos($hook, 'headless-payments') === false) return;
            wp_enqueue_script(
                'hp-admin-tabs',
                HP_PLUGIN_URL . 'assets/js/admin-tabs.js',
                [],
                '1.0.0',
                true
            );
        });
    }

}