<?php

namespace Tests\Unit\Billing;

use Tests\TestCase;
use PaymentGatewayContractTests;
use App\Billing\StripePaymentGateway;
use App\Billing\PaymentFailedException;


/**
 * @group integration
 */
class StripePaymentGatewayTest extends TestCase
{
    use PaymentGatewayContractTests;

    protected function getPaymentGateway()
    {
        return new StripePaymentGateway(config('services.stripe.secret'));
    }
}
