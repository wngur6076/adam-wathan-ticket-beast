<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Concert;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Concert::factory()->published()->create([
            'title' => 'The Red Chord',
            'subtitle' => 'with Animosity and Lethargy',
            'venue' => 'The Mosh Pit',
            'venue_address' => '123 Example Lane',
            'city' => 'laraville',
            'state' => 'ON',
            'zip' => "17916",
            'date' => Carbon::parse('2021-12-13 8:00pm'),
            'ticket_price' => 3250,
            'additional_information' => 'This concert is 19+.',
        ])->addTickets(10);
    }
}
