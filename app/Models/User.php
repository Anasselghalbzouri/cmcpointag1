<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isSecurity(): bool
    {
        return $this->role === 'security';
    }

    public function isStudent(): bool
    {
        return $this->role === 'etudiant';
    }

    public function isStaff(): bool
    {
        return $this->isAdmin() || $this->isSecurity();
    }

    /**
     * Kept for backward compatibility — alias for isSecurity().
     */
    public function isAgent(): bool
    {
        return $this->isSecurity();
    }
}
