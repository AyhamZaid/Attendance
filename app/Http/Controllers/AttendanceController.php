<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckInRequest;
use App\Http\Requests\ChallengeRequest;
use App\Http\Requests\BeaconRequest;
use App\Models\TrainingSession;
use App\Models\Attendance;
use App\Services\QrTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    protected $qrTokenService;

    public function __construct(QrTokenService $qrTokenService)
    {
        $this->qrTokenService = $qrTokenService;
    }

    /**
     * Get LMS user ID from authenticated user.
     * Uses lms_id if available, otherwise generates a deterministic UUID from user ID.
     *
     * @param mixed $user
     * @return string
     */
    protected function getLmsUserId($user)
    {
        if (isset($user->lms_id) && $user->lms_id) {
            return (string) $user->lms_id;
        }

        // Generate deterministic UUID-like string from user ID for consistency
        // Using a fixed namespace UUID and user ID to create consistent UUIDs
        $namespace = '6ba7b810-9dad-11d1-80b4-00c04fd430c8'; // DNS namespace UUID
        $hash = md5($namespace . $user->id);
        
        // Format as UUID v5-like: xxxxxxxx-xxxx-5xxx-xxxx-xxxxxxxxxxxx
        return sprintf(
            '%08s-%04s-5%03s-%04x-%012s',
            substr($hash, 0, 8),
            substr($hash, 8, 4),
            substr($hash, 13, 3),
            (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000,
            substr($hash, 20, 12)
        );
    }

    /**
     * Handle check-in with signed token and geo info.
     *
     * @param  \App\Http\Requests\CheckInRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkIn(CheckInRequest $request)
    {
        $token = $request->input('token');
        $payload = $this->qrTokenService->validate($token);

        if (!$payload) {
            return response()->json(['error' => 'Invalid or expired token'], 422);
        }

        $session = TrainingSession::findOrFail($payload['session_id']);
        
        if (!$session->isActive()) {
            return response()->json(['error' => 'Session is not active'], 403);
        }

        $user = Auth::guard('lms')->user();
        $lmsUserId = $this->getLmsUserId($user);

        // Manual float/bool coercion for PHP 7.4 compatibility
        $lat = $request->has('lat') ? (float) $request->input('lat') : null;
        $lng = $request->has('lng') ? (float) $request->input('lng') : null;
        $geoConfidence = $request->has('geo_confidence') ? (float) $request->input('geo_confidence') : 0.0;

        $attendance = Attendance::updateOrCreate(
            [
                'training_session_id' => $session->id,
                'lms_user_id' => $lmsUserId,
            ],
            [
                'mode' => $payload['mode'],
                'lat' => $lat,
                'lng' => $lng,
                'geo_confidence' => $geoConfidence,
                'ip_hash' => $request->input('ip_hash'),
                'checked_in_at' => Carbon::now(),
            ]
        );

        $attendance->events()->create([
            'type' => 'check_in',
            'payload' => [
                'mode' => $payload['mode'],
                'lat' => $lat,
                'lng' => $lng,
            ],
        ]);

        return response()->json([
            'message' => 'Checked in successfully',
            'attendance' => $attendance,
        ]);
    }

    /**
     * Submit keyword challenge.
     *
     * @param  \App\Http\Requests\ChallengeRequest  $request
     * @param  \App\Models\TrainingSession  $session
     * @return \Illuminate\Http\JsonResponse
     */
    public function submitChallenge(ChallengeRequest $request, TrainingSession $session)
    {
        $keyword = strtoupper($request->input('keyword'));
        $cachedKeyword = Cache::get("session:{$session->id}:challenge");

        if (!$cachedKeyword || $cachedKeyword !== $keyword) {
            return response()->json(['error' => 'Invalid keyword'], 422);
        }

        $user = Auth::guard('lms')->user();
        $lmsUserId = $this->getLmsUserId($user);

        $attendance = Attendance::where('training_session_id', $session->id)
            ->where('lms_user_id', $lmsUserId)
            ->firstOrFail();

        $attendance->challenge_passed_at = Carbon::now();
        $attendance->save();

        $attendance->events()->create([
            'type' => 'challenge',
            'payload' => ['keyword' => $keyword],
        ]);

        Cache::forget("session:{$session->id}:challenge");

        return response()->json([
            'message' => 'Challenge passed successfully',
        ]);
    }

    /**
     * Handle beacon heartbeats.
     *
     * @param  \App\Http\Requests\BeaconRequest  $request
     * @param  \App\Models\TrainingSession  $session
     * @return \Illuminate\Http\JsonResponse
     */
    public function beacon(BeaconRequest $request, TrainingSession $session)
    {
        $user = Auth::guard('lms')->user();
        $lmsUserId = $this->getLmsUserId($user);

        $attendance = Attendance::where('training_session_id', $session->id)
            ->where('lms_user_id', $lmsUserId)
            ->firstOrFail();

        // Manual float coercion for PHP 7.4 compatibility
        $lat = $request->has('lat') ? (float) $request->input('lat') : null;
        $lng = $request->has('lng') ? (float) $request->input('lng') : null;

        $payload = [];
        if ($lat !== null) {
            $payload['lat'] = $lat;
        }
        if ($lng !== null) {
            $payload['lng'] = $lng;
        }

        $attendance->markBeacon($payload);

        return response()->json([
            'message' => 'Beacon recorded',
            'risk_score' => $attendance->risk_score,
        ]);
    }
}

