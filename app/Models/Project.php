<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    protected $with = ['client'];

    protected $appends = ['hours_consumption'];

    protected function casts(): array
    {
        return [
            'deadline' => 'date',
        ];
    }

    public function client(): hasOne
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function time_entries(): HasMany
    {
        return $this->hasMany(TimeEntry::class, 'project_id', 'id');
    }

    public function hours_consumption()
    {
        $count = 0;
        $time_entries = TimeEntry::where('project_id', $this->id)->get();
        foreach ($time_entries as $entry) {
            if (! is_null($entry->end)) {
                $count += $entry->calculateDurationInDecimal();
            }
        }

        if (! is_null($this->hour_estimate) && $this->hour_estimate != 0) {
            $percentage = ($count / $this->hour_estimate) * 100;

            return $percentage;
        }

        return 0;
    }

    public function getHoursConsumptionAttribute()
    {
        // Assuming hours_consumption() is a method that calculates the value
        return $this->hours_consumption();
    }

    public function scopeFilterSearch($query){
        return $query->when(request()->get('query'), function ($q){
            $q->where('title', 'like', '%'.request()->get('query').'%')
                ->orWhere('description', 'like', '%'.request()->get('query').'%')
                ->orWhereHas('client', function ($qrClient) {
                    $qrClient->where('name', 'like', '%'.request()->get('query').'%');
                });
        });
    }
}
