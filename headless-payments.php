<?php
/**
 * Plugin Name: Headless Payments
 * Description: A modular WordPress plugin for handling PayPal payments via REST, designed specifically for headless setups (e.g. React frontend + WP backend). Currently supports PayPal, Stripe, and Square Payments.
 * Version: 1.0.0
 * Author: Keaton Murray
 * Text Domain: headless-payments
 */

defined('ABSPATH') || exit;
define('HP_PLUGIN_URL', plugin_dir_url(__FILE__));

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/includes/bootstrap.php';
