<?php

namespace HP\API;

/**
 * Class PayPalController
 *
 * Registers REST API routes related to PayPal operations for the Headless Payments plugin.
 * 
 * This controller handles endpoints like:
 * - POST /wp-json/hp/v1/paypal/create-order
 * - POST /wp-json/hp/v1/paypal/capture-order
 * 
 * Responsibilities:
 * - Register PayPal-related routes on `rest_api_init`
 * - Receive JSON requests for creating and capturing PayPal orders
 * - Delegate actual processing to the PayPal service layer
 *
 * This controller is intended for internal use by headless frontends (e.g. React, Vue)
 * that communicate with WordPress via REST API to process payments securely.
 *
 * @package HP\API
 */

use HP\Services\PayPalGateway;
use WP_REST_Request;
use WP_Error;

class PayPalController {
    public function register_routes()
    {
        register_rest_route('hp/v1', '/paypal/create-order', [
            'methods'  => 'POST',
            'callback' => [$this, 'handle_create_order'],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route('hp/v1', '/paypal/capture-order', [
            'methods'  => 'POST',
            'callback' => [$this, 'handle_capture_order'],
            'permission_callback' => '__return_true',
        ]);
    }

    public function handle_create_order(WP_REST_Request $request) {
        $params = $request->get_json_params();
        $amount = $params['amount'] ?? null;
        $currency = $params['currency'] ?? 'USD';

        if (!$amount) {
            return new WP_Error('missing_amount', 'Missing payment amount.', ['status' => 400]);
        }

        $paypal = new PayPalGateway();
        return $paypal->create_order($amount, $currency);
    }

    public function handle_capture_order(WP_REST_Request $request) {
        $params = $request->get_json_params();
        $order_id = $params['order_id'] ?? null;

        if (!$order_id) {
            return new WP_Error('missing_order_id', 'Missing PayPal Order ID.', ['status' => 400]);
        }

        $paypal = new PayPalGateway();
        return $paypal->capture_order($order_id);
    }
}