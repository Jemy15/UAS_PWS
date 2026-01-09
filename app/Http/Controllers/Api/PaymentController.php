<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric',
            'method' => 'required|in:cash,transfer'
        ]);

        // Buat payment baru dengan status 'paid'
        $payment = Payment::create([
            'booking_id' => $request->booking_id,
            'amount' => $request->amount,
            'method' => $request->method,
            'status' => 'paid'
        ]);

        // Update status booking menjadi 'paid'
        $booking = Booking::find($request->booking_id);
        $booking->update(['status' => 'paid']);

        // Kembalikan response JSON sukses
        return response()->json([
            'status' => 'success',
            'message' => 'Payment successful',
            'data' => $payment
        ]);
    }
}
