<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Car;
use App\Models\Booking;
use App\Models\ActivityLog;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Create regular users
        $user1 = User::create([
            'name' => 'Maulidin Firdaus',
            'email' => 'maulidin@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $user2 = User::create([
            'name' => 'Reno Kurniawan',
            'email' => 'reno@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        $user3 = User::create([
            'name' => 'Arya Ramadhani',
            'email' => 'arya@example.com',
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);

        // Create cars
        Car::create([
            'make' => 'Toyota',
            'model' => 'Avanza',
            'year' => 2023,
            'price_per_day' => 300000,
            'available' => true,
        ]);

        Car::create([
            'make' => 'Honda',
            'model' => 'Civic',
            'year' => 2022,
            'price_per_day' => 400000,
            'available' => true,
        ]);

        Car::create([
            'make' => 'Daihatsu',
            'model' => 'Xenia',
            'year' => 2021,
            'price_per_day' => 250000,
            'available' => true,
        ]);

        // Create sample bookings
        Booking::create([
            'user_id' => $user1->id,
            'car_id' => 1,
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(3),
            'status' => 'pending',
        ]);

        Booking::create([
            'user_id' => $user2->id,
            'car_id' => 2,
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(7),
            'status' => 'approved',
        ]);

        // Create activity logs
        ActivityLog::create([
            'user_id' => $admin->id,
            'action' => 'created_car',
            'meta' => ['car_id' => 1, 'make' => 'Toyota'],
        ]);

        ActivityLog::create([
            'user_id' => $user1->id,
            'action' => 'created_booking',
            'meta' => ['booking_id' => 1, 'car_id' => 1],
        ]);
    }
}
