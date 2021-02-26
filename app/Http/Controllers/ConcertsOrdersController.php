<?php

namespace App\Http\Controllers;

use App\Models\Concert;
use Illuminate\Http\Request;
use App\Billing\PaymentGateway;
use App\Billing\PaymentFailedException;
use App\Exceptions\NotEnoughTicketsException;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\Reservation;

class ConcertsOrdersController extends Controller
{
    private $paymentGateway;

    public function __construct(PaymentGateway $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function store($concertId)
    {
        $concert = Concert::published()->findOrFail($concertId);

        $this->validate(request(), [
            'email' => ['required', 'email'],
            'ticket_quantity' => ['required', 'integer', 'min:1'],
            'payment_token' => ['required'],
        ]);

        try {
            // 티켓을 찾는다
            $tickets = $concert->reserveTickets(request('ticket_quantity'));

            // 티켓 예약을 한다.
            $reservation = new Reservation($tickets);

            // 고객에게 티켓을 청구
            $this->paymentGateway->charge($reservation->totalCost(), request('payment_token'));

            // 해당 티켓들에 대한 주문 생성
            $order = Order::forTickets($tickets, request('email'), $reservation->totalCost());

            return response()->json(new OrderResource($order), 201);
        } catch (PaymentFailedException $e) {
            return response()->json([], 422);
        } catch (NotEnoughTicketsException $e) {
            return response()->json([], 422);
        }
    }
}
