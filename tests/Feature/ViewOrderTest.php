<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Order;
use App\Models\Ticket;
use App\Models\Concert;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewOrderTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    function user_can_view_their_order_confirmation()
    {
        $this->withoutExceptionHandling();
        // Create a concert
        $concert = Concert::factory()->create([
            'title' => 'The Red Chord',
            'subtitle' => 'with Animosity and Lethargy',
            'date' => Carbon::parse('March 12, 2017 8:00pm'),
            'ticket_price' => 4250,
            'venue' => 'The Mosh Pit',
            'venue_address' => '123 Example Lane',
            'city' => 'Laraville',
            'state' => 'ON',
            'zip' => '17916',
        ]);
        // Create an order
        $order = Order::factory()->create([
            'confirmation_number' => 'ORDERCONFIRMATION1234',
            'card_last_four' => '1881',
            'amount' => 8500,
            'email' => 'john@example.com',
        ]);
        // Create some ticket
        Ticket::factory()->create([
            'concert_id' => $concert->id,
            'order_id' => $order->id,
            'code' => 'TICKETCODE123'
        ]);

        Ticket::factory()->create([
            'concert_id' => $concert->id,
            'order_id' => $order->id,
            'code' => 'TICKETCODE456'
        ]);

        // Visit the order confirmation page
        $response = $this->get("/orders/ORDERCONFIRMATION1234");

        $response->assertStatus(200);
        // Assert we see the correct order details
        $response->assertViewHas('order', function ($viewOrder) use ($order) {
            return $order->id === $viewOrder->id;
        });

        $response->assertSee('ORDERCONFIRMATION1234');
        $response->assertSee('$85.00');
        $response->assertSee('**** **** **** 1881');
        $response->assertSee('TICKETCODE123');
        $response->assertSee('TICKETCODE456');
        $response->assertSee('The Red Chord');
        $response->assertSee('with Animosity and Lethargy');
        $response->assertSee('The Mosh Pit');
        $response->assertSee('123 Example Lane');
        $response->assertSee('Laraville, ON');
        $response->assertSee('17916');
        $response->assertSee('john@example.com');
        $response->assertSee('2017-03-12 20:00');
    }
}
