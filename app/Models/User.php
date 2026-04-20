<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'username',
        'password',
        'nom',
        'prenom',
        'role',
        'cne',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAgent(): bool
    {
        return $this->role === 'agent';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function isStaff(): bool
    {
        return $this->isAdmin() || $this->isAgent();
    }

    public function movements()
    {
        return $this->hasMany(Movement::class);
    }

    public function lastMovement()
    {
        return $this->movements()->latest()->first();
    }

    public function currentStatus(): string
    {
        $last = $this->lastMovement();
        if (!$last) return 'jamais_scanne';
        return $last->type === 'entree' ? 'a_linterieur' : 'a_lexterieur';
    }
}
