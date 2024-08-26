<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact_name',
        'contact_email',
        'contact_phone',
    ];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'client_id', 'id');
    }
}
