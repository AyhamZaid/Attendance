<?php

namespace App\Http\Controllers;

use App\Models\TrainingSession;
use App\Services\QrTokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class TrainerSessionController extends Controller
{
    protected $qrTokenService;

    public function __construct(QrTokenService $qrTokenService)
    {
        $this->qrTokenService = $qrTokenService;
    }

    /**
     * Display the session with QR codes for onsite and remote.
     *
     * @param  \App\Models\TrainingSession  $session
     * @return \Illuminate\Http\Response
     */
    public function show(TrainingSession $session)
    {
        $onsiteQrToken = null;
        $remoteQrToken = null;

        if (in_array($session->mode, ['onsite', 'hybrid'])) {
            $onsiteQrToken = $this->qrTokenService->generate([
                'session_id' => $session->id,
                'mode' => 'onsite',
            ]);
        }

        if (in_array($session->mode, ['remote', 'hybrid'])) {
            $remoteQrToken = $this->qrTokenService->generate([
                'session_id' => $session->id,
                'mode' => 'remote',
            ]);
        }

        return view('trainer.sessions.show', compact('session', 'onsiteQrToken', 'remoteQrToken'));
    }

    /**
     * Generate a 6-character keyword challenge stored in cache for 5 minutes.
     *
     * @param  \App\Models\TrainingSession  $session
     * @return \Illuminate\Http\JsonResponse
     */
    public function challenge(TrainingSession $session)
    {
        $keyword = strtoupper(Str::random(6));
        
        Cache::put("session:{$session->id}:challenge", $keyword, now()->addMinutes(5));

        return response()->json([
            'keyword' => $keyword,
            'expires_at' => now()->addMinutes(5)->toIso8601String(),
        ]);
    }
}


