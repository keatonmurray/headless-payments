<?php

namespace HP\Services;

/**
 * Class PayPalGateway
 *
 * Handles communication with the PayPal API for creating and capturing orders.
 * 
 * This class is responsible for:
 * - Authenticating with PayPal using client ID and secret
 * - Creating orders via the PayPal v2 Checkout API
 * - Capturing approved orders
 * - Managing dynamic configuration (live/sandbox mode, return/cancel URLs, etc.)
 * 
 * The credentials and settings are pulled from WordPress options,
 * configured via the Headless Payments plugin settings page.
 *
 * This class is intended to be called by the API layer (e.g., PayPalController)
 * and should not directly handle REST or UI logic.
 *
 * @package HP\Gateways
 */

use WP_Error;

class PayPalGateway {
    private $mode;
    private $client_id;
    private $secret;
    private $base_url;

    public function __construct() {
        $this->mode      = get_option('hp_paypal_mode', 'sandbox');
        $this->client_id = get_option('hp_paypal_client_id');
        $this->secret    = get_option('hp_paypal_secret');
        $this->base_url  = ($this->mode === 'live')
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';
    }

    public function create_order($amount, $currency) {
        if (!$this->client_id || !$this->secret) {
            return new WP_Error('missing_keys', 'PayPal credentials are not set.', ['status' => 403]);
        }

        $access_token = $this->get_access_token();
        if (is_wp_error($access_token)) return $access_token;

        $response = wp_remote_post("{$this->base_url}/v2/checkout/orders", [
            'headers' => [
                'Authorization' => "Bearer {$access_token}",
                'Content-Type'  => 'application/json'
            ],
            'body' => json_encode([
                'intent' => 'CAPTURE',
                'purchase_units' => [[
                    'amount' => [
                        'currency_code' => $currency,
                        'value' => number_format($amount, 2, '.', '')
                    ]
                ]],
                'application_context' => [
                    'return_url' => home_url('/checkout/success'),
                    'cancel_url' => home_url('/checkout/cancel'),
                ]
            ])
        ]);

        if (is_wp_error($response)) return $response;

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (empty($body['id']) || empty($body['links'])) {
            return new WP_Error('order_failed', 'Could not create PayPal order.');
        }

        $approval_url = null;
        foreach ($body['links'] as $link) {
            if ($link['rel'] === 'approve') {
                $approval_url = $link['href'];
                break;
            }
        }

        return [
            'order_id' => $body['id'],
            'approval_url' => $approval_url
        ];
    }

    public function capture_order($order_id) {
        $access_token = $this->get_access_token();
        if (is_wp_error($access_token)) return $access_token;

        $response = wp_remote_post("{$this->base_url}/v2/checkout/orders/{$order_id}/capture", [
            'headers' => [
                'Authorization' => "Bearer {$access_token}",
                'Content-Type'  => 'application/json'
            ]
        ]);

        if (is_wp_error($response)) return $response;

        $body = json_decode(wp_remote_retrieve_body($response), true);
        if (empty($body['status']) || $body['status'] !== 'COMPLETED') {
            return new WP_Error('capture_failed', 'Could not capture PayPal order.');
        }

        return [
            'status' => 'success',
            'details' => $body
        ];
    }

    private function get_access_token() {
        $auth = base64_encode("{$this->client_id}:{$this->secret}");

        $response = wp_remote_post("{$this->base_url}/v1/oauth2/token", [
            'headers' => [
                'Authorization' => "Basic {$auth}",
                'Content-Type'  => 'application/x-www-form-urlencoded'
            ],
            'body' => 'grant_type=client_credentials'
        ]);

        if (is_wp_error($response)) return $response;

        $body = json_decode(wp_remote_retrieve_body($response), true);
        return $body['access_token'] ?? new WP_Error('auth_failed', 'Unable to authenticate with PayPal.');
    }
}
