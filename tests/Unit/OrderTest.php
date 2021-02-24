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
    function tickets_are_released_when_an_order_is_cancelled()
    {
        $concert = Concert::factory()->create()->addTickets(10);
        $order = $concert->orderTickets('jane@example.com', 5);
        $this->assertEquals(5, $concert->ticketsRemaining());

        $order->cancel();

        $this->assertEquals(10, $concert->ticketsRemaining());
        $this->assertNull(Order::find($order->id));
    }
}