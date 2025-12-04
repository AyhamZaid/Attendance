<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\TrainingSession;
use App\Models\Attendance;
use App\Services\QrTokenService;
use App\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AttendanceFlowTest extends TestCase
{
    use RefreshDatabase;

    protected $qrTokenService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->qrTokenService = new QrTokenService();
    }

    /**
     * Test the complete attendance flow: create session, generate QR, check in.
     *
     * @return void
     */
    public function testAttendanceCheckInFlow()
    {
        // Create a training session
        $session = TrainingSession::create([
            'lms_session_id' => \Illuminate\Support\Str::uuid()->toString(),
            'title' => 'Test Training Session',
            'mode' => 'hybrid',
            'lat' => 40.7128,
            'lng' => -74.0060,
            'geo_radius_m' => 100,
            'starts_at' => Carbon::now()->subMinutes(10), // Started 10 minutes ago
            'ends_at' => Carbon::now()->addHours(2), // Ends in 2 hours
        ]);

        // Create and authenticate a user
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Note: The AttendanceController uses $user->lms_id ?? $user->id
        // Since lms_user_id is a UUID field, we'll use the user's id converted to string
        // In a real scenario, you might want to add an lms_id field to the users table

        // Authenticate as the user with lms guard
        $this->actingAs($user, 'lms');

        // Generate QR token
        $token = $this->qrTokenService->generate([
            'session_id' => $session->id,
            'mode' => 'onsite',
        ]);

        // Post to check-in endpoint
        $response = $this->postJson('/attendance/check-in', [
            'token' => $token,
            'mode' => 'onsite',
            'lat' => 40.7128,
            'lng' => -74.0060,
        ]);

        // Assert response
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message',
            'attendance' => [
                'id',
                'training_session_id',
                'lms_user_id',
                'mode',
            ],
        ]);

        // Assert database entry
        $this->assertDatabaseHas('attendances', [
            'training_session_id' => $session->id,
            'mode' => 'onsite',
        ]);

        // Assert attendance event was created
        $attendance = Attendance::where('training_session_id', $session->id)->first();
        $this->assertNotNull($attendance);
        $this->assertNotNull($attendance->checked_in_at);

        // Assert event was logged
        $this->assertDatabaseHas('attendance_events', [
            'attendance_id' => $attendance->id,
            'type' => 'check_in',
        ]);
    }
}

