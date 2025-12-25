<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    /**
     * Create a new booking.
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            $validated = $request->validate([
                'car_id' => 'required|exists:cars,id',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
            ]);

            // Check if car is available for the requested period
            $car = Car::findOrFail($validated['car_id']);

            $existingBooking = Booking::where('car_id', $validated['car_id'])
                ->where('status', '!=', 'cancelled')
                ->whereBetween('start_date', [$validated['start_date'], $validated['end_date']])
                ->orWhere(function ($query) use ($validated) {
                    $query->where('car_id', $validated['car_id'])
                        ->where('status', '!=', 'cancelled')
                        ->whereBetween('end_date', [$validated['start_date'], $validated['end_date']]);
                })
                ->first();

            if ($existingBooking) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Car is not available for the requested period',
                ], 409);
            }

            $booking = Booking::create([
                'user_id' => $user->id,
                'car_id' => $validated['car_id'],
                'start_date' => $validated['start_date'],
                'end_date' => $validated['end_date'],
                'status' => 'pending',
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Booking created successfully',
                'data' => $booking,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Car not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create booking: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get bookings for the authenticated user.
     */
    public function myBookings(Request $request)
    {
        try {
            $user = Auth::user();

            $bookings = Booking::where('user_id', $user->id)
                ->with('car')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' => $bookings,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch bookings: ' . $e->getMessage(),
            ], 500);
        }
    }
     // ===============================
    // ADMIN: lihat semua booking
    // ===============================
    public function index()
    {
        $bookings = Booking::with(['user', 'car'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $bookings
        ]);
    }
    
     // ADMIN: update status booking (approve/reject/completed)
        public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,completed'
        ]);

        $booking = Booking::findOrFail($id);
        $booking->status = $request->status;
        $booking->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Booking status updated successfully'
        ]);
    }
}
