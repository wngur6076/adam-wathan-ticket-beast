<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use App\Models\Concert;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ViewConcertListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    function user_can_view_a_published_concert_listing()
    {
        // Arrange
        // Create a concert
        $concert = Concert::factory()->published()->create([
            'title' => 'The Red Chord',
            'subtitle' => 'with Animosity and Lethargy',
            'date' => Carbon::parse('December 13, 2016 8:00pm'),
            'ticket_price' => 3250,
            'venue' => 'The Mosh Pit',
            'venue_address' => '123 Example Lane',
            'city' => 'Laraveill',
            'state' => 'ON',
            'zip' => 17916,
            'additional_information' => 'For tickets, call (555) 555-5555.',
        ]);

        // Act
        // view the consert listing
        $response = $this->get('/concerts/'.$concert->id);

        // Assert
        // See the consert details
        $response->assertSee('The Red Chord');
        $response->assertSee('with Animosity and Lethargy');
        $response->assertSee('December 13, 2016');
        $response->assertSee('8:00pm');
        $response->assertSee('32.50');
        $response->assertSee('The Mosh Pit');
        $response->assertSee('123 Example Lane');
        $response->assertSee('Laraveill, ON 17916');
        $response->assertSee('For tickets, call (555) 555-5555.');
    }

    /** @test */
    function user_cannot_view_unpublished_concert_listings()
    {
        $concert = Concert::factory()->unpublished()->create();

        $response = $this->get('/concerts/'.$concert->id);

        $response->assertStatus(404);
    }
}
