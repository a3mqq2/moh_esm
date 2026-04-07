<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospital extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
        'location',
        'email',
        'latitude',
        'longitude',
        'is_active',
        'notes',
    ];

    public function departments()
    {
        return $this->belongsToMany(Department::class)->withPivot('beds', 'vacant_beds')->withTimestamps();
    }

    public function wards()
    {
        return $this->belongsToMany(Ward::class)->withPivot('beds', 'vacant_beds')->withTimestamps();
    }
}
