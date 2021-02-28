<?php

namespace Tests\Unit;

use App\Http\Resources\OrderResource;
use App\Models\Concert;
use App\Models\Order;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    /* function converting_to_an_array()
    {
        $this->withoutExceptionHandling();
        $concert = Concert::factory()->create(['ticket_price' => 1200])->addTickets(10);
        $order = $concert->orderTickets('jane@example.com', 5);

        $result = $order->toArray();

        $this->assertEquals([
            'email' => 'jane@example.com',
            'ticket_quantity' => 5,
            'amount' => 6000,
        ], $result);
    } */

    /** @test */
    function creating_an_order_from_tickets_and_email_and_amount()
    {
        $this->withoutExceptionHandling();
        $concert = Concert::factory()->create()->addTickets(5);
        $this->assertEquals(5, $concert->ticketsRemaining());

        $order = Order::forTickets($concert->findTickets(3), 'john@example.com', 3600);

        $this->assertEquals('john@example.com', $order->email);
        $this->assertEquals(3, $order->ticketQuantity());
        $this->assertEquals(3600, $order->amount);
        $this->assertEquals(2, $concert->ticketsRemaining());
    }
}
