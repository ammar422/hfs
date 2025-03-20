<?php

namespace App\Services;

class StripeService
{
    private $stripeSecretKey;

    public function __construct()
    {
        $this->stripeSecretKey = env('STRIPE_SECRET_KEY');
    }

    /**
     * Create a Payment Intent
     */
    public function createPaymentIntent($amount, $currency = 'usd')
    {
        $url = "https://api.stripe.com/v1/payment_intents";

        $data = [
            'amount'   => $amount * 100, // Stripe requires the amount in cents
            'currency' => $currency,
        ];

        $payment = $this->makeRequest('POST', $url, $data);
        return $payment;
    }

    /**
     * Capture a Payment
     */
    public function capturePayment($paymentIntentId)
    {
        $url = "https://api.stripe.com/v1/payment_intents/{$paymentIntentId}/capture";

        return $this->makeRequest('POST', $url);
    }

    /**
     * Refund a Payment
     */
    public function refundPayment($paymentIntentId)
    {
        $url = "https://api.stripe.com/v1/refunds";

        $data = [
            'payment_intent' => $paymentIntentId,
        ];

        return $this->makeRequest('POST', $url, $data);
    }

    /**
     * Make a cURL Request to Stripe API
     */
    private function makeRequest($method, $url, $data = [])
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$this->stripeSecretKey}",
            "Content-Type: application/x-www-form-urlencoded"
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }
}
