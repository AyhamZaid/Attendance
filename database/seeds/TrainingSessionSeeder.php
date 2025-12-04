<?php

use Illuminate\Database\Seeder;
use App\Models\TrainingSession;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TrainingSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TrainingSession::create([
            'lms_session_id' => (string) Str::uuid(),
            'title' => 'Hybrid Training Session - Introduction to Laravel',
            'mode' => 'hybrid',
            'lat' => 40.7128,
            'lng' => -74.0060,
            'geo_radius_m' => 100,
            'starts_at' => Carbon::now()->addDays(1)->setTime(10, 0),
            'ends_at' => Carbon::now()->addDays(1)->setTime(12, 0),
        ]);
    }
}


