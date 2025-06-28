<?php

namespace HP\API;

/**
 * Class StripeController
 * 
 * Registers REST API routes related to PayPal operations for the Headless Payments plugin.
 * 
 * This controller handles endpoints like: 
 * - POST /wp-json/headless-payments/v1/stripe/create-payment-intent
 * 
 * Responsibilities: 
 * - Registers Stripe-related routes on `rest_api_init`
 * - Receives JSON requests for creating payment intent
 * - Delegate actual processing tp to the Stripe service layer 
 * 
 * This controller is intended for internal use by headless frontends (e.g. React, Vue)
 * that communicate with WordPress via REST API to process payments securely.
 * 
 * @package HP\API
 */

use HP\Services\StripeGateway;
use WP_REST_Request;
use WP_Error;
use WP_REST_Response;

class StripeController {
    public function register_routes() {
        register_rest_route('headless-payments/v1', '/stripe/create-payment-intent', [
            'methods'  => 'POST',
            'callback' => [$this, 'handle_create_payment_intent'],
            'permission_callback' => '__return_true', 
        ]);
    }

    /**
     * Handles creation of a Stripe PaymentIntent.
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error
     */
    public function handle_create_payment_intent(WP_REST_Request $request) {
        $amount = $request->get_param('amount');
        $currency = $request->get_param('currency') ?: 'usd';

        if (!$amount || !is_numeric($amount)) {
            return new WP_Error('invalid_amount', 'Amount must be a numeric value.', ['status' => 400]);
        }

        $stripe = new StripeGateway();
        $intent = $stripe->create_payment_intent((int) $amount, $currency);

        if (!$intent) {
            return new WP_Error('stripe_error', 'Could not create PaymentIntent.', ['status' => 500]);
        }

        return new WP_REST_Response([
            'client_secret'    => $intent->client_secret,
            'publishable_key'  => $stripe->get_publishable_key(),
        ], 200);
    }
}