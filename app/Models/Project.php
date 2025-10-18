<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'client_id',
        'description',
        'deadline',
        'hour_estimate',
    ];

    protected $appends = ['hours_consumption'];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function time_entries(): HasMany
    {
        return $this->hasMany(TimeEntry::class, 'project_id', 'id');
    }

    public function hours_consumption(): float
    {
        $totalMinutes = $this->time_entries()
            ->whereNotNull('end')
            ->get()
            ->sum(fn ($entry) => $entry->start->diffInMinutes($entry->end));

        $totalHours = $totalMinutes / 60;

        if (! is_null($this->hour_estimate) && $this->hour_estimate != 0) {
            return ($totalHours / $this->hour_estimate) * 100;
        }

        return 0;
    }

    public function getHoursConsumptionAttribute()
    {
        // Assuming hours_consumption() is a method that calculates the value
        return $this->hours_consumption();
    }
}
