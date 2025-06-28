<?php

namespace HP;

/**
 * Class Plugin
 *
 * The core plugin class for Headless Payments.
 * This class is responsible for initializing the plugin's functionality by:
 * 
 * 1. Registering admin interfaces (like settings pages) when in the WordPress admin dashboard.
 * 2. Registering custom REST API routes for external communication (e.g. payment operations).
 * 
 * This class is instantiated and run in `includes/bootstrap.php`, which acts as the main
 * bootstrapper for the plugin, handling autoloading and startup logic.
 * 
 * @package HP
 */

use HP\Admin\SettingsPage;
use HP\API\PayPalController;
use HP\API\StripeController;

class Plugin {
    public function run() {
        // Admin panel
        if (is_admin()) {
            (new SettingsPage())->register();
        }

         // REST API routes
        add_action('rest_api_init', function () {
            (new PayPalController())->register_routes();
            (new StripeController())->register_routes();
        });
    }
}
