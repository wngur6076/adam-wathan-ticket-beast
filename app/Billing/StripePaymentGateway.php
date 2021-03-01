<?php

namespace App\Billing;

use Stripe\Charge;
use Stripe\Exception\InvalidRequestException;

class StripePaymentGateway implements PaymentGateway
{
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getValidTestToken()
    {
        return \Stripe\Token::create([
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 1,
                'exp_year' => date('Y') + 1,
                'cvc' => '123',
            ],
        ], ['api_key' => $this->apiKey])->id;
    }

    public function charge($amount, $token)
    {
        try {
            Charge::create([
                'amount' => $amount,
                'source' => $token,
                'currency' => 'usd',
            ], ['api_key' => $this->apiKey]);
        } catch (InvalidRequestException $e) {
            throw new PaymentFailedException;
        }

        /* (new \GuzzleHttp\Client)->post('https://api.stripe.com/v1/charges', [
            'headers' => [
                'Authorization' => "Bearer {$this->apiKey}",
            ],
            'form_params' => [
                'amount' => $amount,
                'source' => $token,
                'currency' => 'usd',
            ]
        ]); */
    }

    public function newChargesDuring($callback)
    {
        $latestCharge = $this->lastCharge();
        $callback($this);
        return $this->newChargesSince($latestCharge)->pluck('amount');
    }

    private function lastCharge()
    {
        return \Stripe\Charge::all(
            ['limit' => 1],
            ['api_key' => $this->apiKey]
        )['data'][0];
    }

    private function newChargesSince($charge = null)
    {
        $newCharges = \Stripe\Charge::all(['ending_before' => $charge ? $charge->id : null],
            ['api_key' => $this->apiKey]
        )['data'];

        return collect($newCharges);
    }
}
