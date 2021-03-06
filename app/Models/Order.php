<?php

namespace App\Models;

use App\Facades\OrderConfirmationNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];

    public static function forTickets($tickets, $email, $charge)
    {
        $order = self::create([
            // 'confirmation_number' => app(OrderConfirmationNumberGenerator::class)->generate(),
            'confirmation_number' => OrderConfirmationNumber::generate(),
            'email' => $email,
            'amount' => $charge->amount(),
            'card_last_four' => $charge->cardLastFour(),
        ]);

        foreach ($tickets as $ticket) {
            $order->tickets()->save($ticket);
        }
        return $order;
    }

    public static function findByConfirmationNumber($confirmationNumber)
    {
        return Self::where('confirmation_number', $confirmationNumber)->firstOrFail();
    }

    public function concert()
    {
        return $this->belongsTo(Concert::class);
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function ticketQuantity()
    {
        return $this->tickets()->count();
    }


    /* public function toArray()
    {
        return [
            'email' => $this->email,
            'ticket_quantity' => $this->ticketQuantity(),
            'amount' => $this->ticketQuantity() * $this->concert->ticket_price,
        ];
    } */
}
