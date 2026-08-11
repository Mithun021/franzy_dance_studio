<?php

namespace App\Http\Controllers;

use App\Models\StudioBooking;
use App\Models\StudioPayment;
use Illuminate\Http\Request;

class StudioBookedController extends Controller
{
    // public function index()
    // {
    //     $bookings = StudioBooking::with([
    //         'user:id,name',

    //         'studio' => function ($query) {
    //             $query->select(
    //                 'id',
    //                 'studio_category_id',
    //                 'price'
    //             )->with('category:id,name');
    //         },

    //         'payments' => function ($query) {
    //             $query->latest('payment_date')
    //                 ->latest('id');
    //         }

    //     ])
    //     ->latest()
    //     ->get();

    //     return view('backend.studio-booked.index', compact('bookings'));
    // }

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
                $query->where('payment_status', 'Success')
                    ->latest('payment_date')
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
            ->whereIn('payment_status', ['Pending','Failed','Cancelled'])
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

    public function studioPaymentHistory(Request $request)
    {
        $query = StudioPayment::with([
            'booking:id,booking_id,customer_name,email,phone,studio_id,booking_from_date,booking_to_date',
            'booking.studio:id,studio_category_id,price',
            'booking.studio.category:id,name',
            'creator:id,name',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Payment ID Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('payment_id')) {

            $query->where('payment_id', 'like', '%' . $request->payment_id . '%');

        }

        /*
        |--------------------------------------------------------------------------
        | Booking ID Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('booking_id')) {

            $query->whereHas('booking', function ($q) use ($request) {

                $q->where(
                    'booking_id',
                    'like',
                    '%' . $request->booking_id . '%'
                );

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Customer Name Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('customer_name')) {

            $query->whereHas('booking', function ($q) use ($request) {

                $q->where(
                    'customer_name',
                    'like',
                    '%' . $request->customer_name . '%'
                );

            });

        }

        /*
        |--------------------------------------------------------------------------
        | Studio Category Filter
        |--------------------------------------------------------------------------
        */
        if ($request->filled('studio_name')) {

            $query->whereHas('booking.studio.category', function ($q) use ($request) {

                $q->where(
                    'name',
                    'like',
                    '%' . $request->studio_name . '%'
                );

            });

        }

        /*
        |--------------------------------------------------------------------------
        | From Date
        |--------------------------------------------------------------------------
        */
        if ($request->filled('from_date')) {

            $query->whereDate(
                'payment_date',
                '>=',
                $request->from_date
            );

        }

        /*
        |--------------------------------------------------------------------------
        | To Date
        |--------------------------------------------------------------------------
        */
        if ($request->filled('to_date')) {

            $query->whereDate(
                'payment_date',
                '<=',
                $request->to_date
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Payment Status
        |--------------------------------------------------------------------------
        */
        if ($request->filled('payment_status')) {

            $query->where(
                'payment_status',
                $request->payment_status
            );

        }

        /*
        |--------------------------------------------------------------------------
        | Payment Records
        |--------------------------------------------------------------------------
        */
        $payments = $query
            ->latest('payment_date')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | Studio Categories
        |--------------------------------------------------------------------------
        */
        $studioCategories = \App\Models\StudioCategory::orderBy('name')
            ->get(['id', 'name']);


        /*
        |--------------------------------------------------------------------------
        | Payment Statuses
        |--------------------------------------------------------------------------
        */
        $paymentStatuses = [
            'Pending',
            'Success',
            'Failed',
            'Refunded',
        ];


        return view(
            'backend.payment-history.studio-payments',
            compact(
                'payments',
                'studioCategories',
                'paymentStatuses'
            )
        );
    }
}
