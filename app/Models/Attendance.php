<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Attendance extends Model
{
    protected $fillable = [
        'training_session_id',
        'lms_user_id',
        'mode',
        'geo_confidence',
        'risk_score',
        'lat',
        'lng',
        'ip_hash',
        'checked_in_at',
        'check_out_at',
        'challenge_passed_at',
        'last_beacon_at',
        'flags',
    ];

    protected $casts = [
        'geo_confidence' => 'float',
        'risk_score' => 'integer',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
        'checked_in_at' => 'datetime',
        'check_out_at' => 'datetime',
        'challenge_passed_at' => 'datetime',
        'last_beacon_at' => 'datetime',
        'flags' => 'array',
    ];

    /**
     * Get the training session for this attendance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function trainingSession()
    {
        return $this->belongsTo(TrainingSession::class);
    }

    /**
     * Get all events for this attendance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function events()
    {
        return $this->hasMany(AttendanceEvent::class);
    }

    /**
     * Mark a beacon event, raising risk score and logging.
     *
     * @param array $payload
     * @return void
     */
    public function markBeacon(array $payload = [])
    {
        $this->increment('risk_score');
        $this->last_beacon_at = Carbon::now();
        $this->save();

        $this->events()->create([
            'type' => 'beacon',
            'payload' => $payload,
        ]);
    }
}


