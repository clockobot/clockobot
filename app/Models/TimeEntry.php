<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'start',
        'end',
        'client_id',
        'project_id',
        'user_id',
        'work_type_id',
        'description',
        'link',
        'billable',
    ];

    protected $appends = ['hourly_duration'];

    protected function casts(): array
    {
        return [
            'start' => 'datetime',
            'end' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function work_type(): BelongsTo
    {
        return $this->belongsTo(WorkType::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function calculateDurationInDecimal(): float|int
    {
        // start and end are already Carbon instances from casts
        $minutes = $this->start->diffInMinutes($this->end);

        // Convert minutes to decimal format (e.g., 2 hours and 30 minutes becomes 2.5 hours)
        return $minutes / 60;
    }

    public function getHourlyDurationAttribute(): string
    {
        return $this->calculateDurationInHours();
    }

    public function calculateDurationInHours(): string
    {
        // start and end are already Carbon instances from casts
        $minutes = $this->start->diffInMinutes($this->end);

        // Convert minutes to hours and minutes format (e.g., 2 hours and 30 minutes becomes "02:30")
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;

        return sprintf('%02d:%02d', $hours, $remainingMinutes);
    }
}
