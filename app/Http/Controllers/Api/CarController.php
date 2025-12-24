<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CarController extends Controller
{
    /**
     * List all available cars.
     */
    public function index(Request $request)
    {
        try {
            $cars = Car::all();

            return response()->json([
                'status' => 'success',
                'data' => $cars,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch cars: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create a new car (admin only).
     */
    public function store(Request $request)
    {
        try {
            // Check if user is admin
            $user = Auth::user();
            if ($user->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized - admin only',
                ], 403);
            }

            $validated = $request->validate([
                'make' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
                'price_per_day' => 'required|numeric|min:0',
            ]);

            $car = Car::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Car created successfully',
                'data' => $car,
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create car: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update a car (admin only).
     */
    public function update(Request $request, $id)
    {
        try {
            // Check if user is admin
            $user = Auth::user();
            if ($user->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized - admin only',
                ], 403);
            }

            $car = Car::findOrFail($id);

            $validated = $request->validate([
                'make' => 'sometimes|string|max:255',
                'model' => 'sometimes|string|max:255',
                'year' => 'sometimes|integer|min:1900|max:' . (date('Y') + 1),
                'price_per_day' => 'sometimes|numeric|min:0',
                'available' => 'sometimes|boolean',
            ]);

            $car->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Car updated successfully',
                'data' => $car,
            ], 200);
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
                'message' => 'Failed to update car: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a car (admin only).
     */
    public function destroy($id)
    {
        try {
            // Check if user is admin
            $user = Auth::user();
            if ($user->role !== 'admin') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized - admin only',
                ], 403);
            }

            $car = Car::findOrFail($id);
            $car->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Car deleted successfully',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'status' => 'error',
                'message' => 'Car not found',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete car: ' . $e->getMessage(),
            ], 500);
        }
    }
}
