<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    protected $with = ['client', 'project', 'work_type', 'user'];

    protected $appends = ['hourly_duration'];

    protected function casts(): array
    {
        return [
            'start' => 'datetime',
            'end' => 'datetime',
        ];
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function project(): HasOne
    {
        return $this->hasOne(Project::class, 'id', 'project_id');
    }

    public function work_type(): HasOne
    {
        return $this->hasOne(WorkType::class, 'id', 'work_type_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function calculateDurationInDecimal(): float|int
    {
        // Parse the date strings into Carbon objects
        $start = Carbon::createFromFormat('Y-m-d H:i', $this->start->format('Y-m-d H:i'));
        $end = Carbon::createFromFormat('Y-m-d H:i', $this->end->format('Y-m-d H:i'));

        $minutes = $start->diffAsCarbonInterval($end)->totalMinutes;

        // Convert minutes to decimal format (e.g., 2 hours and 30 minutes becomes 2.5 hours)
        $decimalDuration = $minutes / 60;

        return $decimalDuration;
    }

    public function getHourlyDurationAttribute(): string
    {
        return $this->calculateDurationInHours();
    }

    public function calculateDurationInHours(): string
    {
        $start = Carbon::parse($this->start);
        $end = Carbon::parse($this->end);

        $minutes = $start->diffAsCarbonInterval($end)->totalMinutes;

        // Convert minutes to hours and minutes format (e.g., 2 hours and 30 minutes becomes "02:30")
        $hours = floor($minutes / 60);
        $remainingMinutes = $minutes % 60;
        $formattedDuration = sprintf('%02d:%02d', $hours, $remainingMinutes);

        return $formattedDuration;
    }
}
