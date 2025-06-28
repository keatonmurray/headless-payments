<?php

namespace HP\Services;

use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Exception\ApiErrorException;

/**
 * Class StripeGateway
 * 
 * Handles communication with the Stripe API for creating and capturing orders.
 * 
 * This class is responsible for:
 * - Authenticating with Stripe using Publishable Key and Secret Key
 * - Creating orders via the unified Stripe v1 Checkout API
 * - Capturing approved orders
 * - Managing dynamic configuration 
 * 
 * The credentials are settings are pulled from Wordpress options ,
 * configured via the Headless Payments plugin settings page
 * 
 * This class is intended to be called by the API layer (e.g., StripeController)
 * and should not directly handle REST or UI logic
 * 
 *  @package HP\Gateways
 */

class StripeGateway {
    private $mode; 
    private $publishable_key;
    private $secret_key;
    private $base_url;

    public function __construct() { 
        $this->mode = get_option('hp_stripe_mode', 'sandbox');
        $this->publishable_key = get_option('hp_stripe_publishable_key');
        $this->secret_key = get_option('hp_stripe_secret');
        $this->base_url = 'https://api.stripe.com/v1';
    
        // Initialize Stripe SDK globally
        Stripe::setApiKey($this->secret_key);
    }

    /**
     * Create a Stripe PaymentIntent
     *
     * @param int $amount Amount in cents
     * @param string $currency Currency (e.g. 'usd')
     * @param array $metadata Optional metadata
     * @return PaymentIntent|false
     */

     public function create_payment_intent($amount, $currency, $metadata = []) {
        try {
            $intent = PaymentIntent::create([
                'amount' => $amount,
                'currency' => $currency,
                'automatic_payment_methods' => ['enabled' => true],
                'metadata' => $metadata,
            ]);
            return $intent;
        } catch (ApiErrorException $e) {
            error_log('Stripe Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Retrieve an existing PaymentIntent
     *
     * @param string $intent_id
     * @return PaymentIntent|false
     */

    public function retrieve_payment_intent($intent_id) {
        try {
            return PaymentIntent::retrieve($intent_id);
        } catch (ApiErrorException $e) {
            error_log('Stripe Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Capture a manually-authorized PaymentIntent (optional)
     *
     * @param string $intent_id
     * @return PaymentIntent|false
     */
    public function capture_payment_intent($intent_id) {
        try {
            $intent = PaymentIntent::retrieve($intent_id);
            return $intent->capture();
        } catch (ApiErrorException $e) {
            error_log('Stripe Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Helper: Get the current mode (test/live)
     */
    public function is_live_mode() {
        return $this->mode === 'live';
    }

    public function get_publishable_key() {
        return $this->publishable_key;
    }

}