<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TrainingSession extends Model
{
    protected $fillable = [
        'lms_session_id',
        'title',
        'mode',
        'lat',
        'lng',
        'geo_radius_m',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
        'geo_radius_m' => 'integer',
    ];

    /**
     * Get all attendances for this session.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Check if the session is currently active.
     * Active 15 minutes before start to 30 minutes after end.
     *
     * @return bool
     */
    public function isActive()
    {
        $now = Carbon::now();
        $activeStart = $this->starts_at->copy()->subMinutes(15);
        $activeEnd = $this->ends_at->copy()->addMinutes(30);

        return $now->between($activeStart, $activeEnd);
    }
}


