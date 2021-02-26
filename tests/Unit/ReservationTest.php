<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Concert;
use App\Models\Reservation;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReservationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function calculating_the_total_cost()
    {
        // $concert = Concert::factory()->create(['ticket_price' => 1200])->addTickets(3);
        // $tickets = $concert->findTickets(3);
        $tickets = collect([
            (object) ['price' => 1200],
            (object) ['price' => 1200],
            (object) ['price' => 1200],
        ]);

        $reservation = new Reservation($tickets);

        $this->assertEquals(3600, $reservation->totalCost());
    }
}
