<?php

namespace App\Http\Controllers;

use App\Models\StudioBooking;
use Illuminate\Http\Request;

class StudioBookedController extends Controller
{
    public function index()
    {
        $bookings = StudioBooking::with([
            'user:id,name',

            'studio' => function ($query) {
                $query->select(
                    'id',
                    'studio_category_id',
                    'price'
                )->with('category:id,name');
            },

            'payments' => function ($query) {
                $query->latest('payment_date')
                    ->latest('id');
            }

        ])
        ->latest()
        ->get();

        return view('backend.studio-booked.index', compact('bookings'));
    }

    public function paymentHistory(StudioBooking $booking)
    {
        $booking->load([

            'user',

            'studio.category',

            'payments' => function ($query) {

                $query->with('creator')
                    ->orderBy('payment_date')
                    ->orderBy('id');

            }

        ]);

        $totalPayment = $booking->payments
            ->where('payment_status', 'Success')
            ->sum('amount');

        $failedPayment = $booking->payments
            ->whereIn('payment_status', ['Failed','Cancelled'])
            ->count();

        return view(
            'backend.studio-booked.payment-history',
            compact(
                'booking',
                'totalPayment',
                'failedPayment'
            )
        );
    }
}
