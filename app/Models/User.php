<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'hospital_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function hospital()
    {
        return $this->belongsTo(\App\Models\Hospital::class);
    }

    public function getRoleLabelAttribute(): string
    {
        return match ($this->role) {
            'admin' => 'مدير نظام',
            'hospital_manager' => 'مسؤول مستشفى',
            'observer' => 'مراقب',
            default => $this->role,
        };
    }

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
