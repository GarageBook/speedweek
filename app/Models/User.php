<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;
    protected $fillable = ['name','email','password','phone','is_admin'];
    protected $hidden = ['password','remember_token'];
    protected function casts(): array { return ['email_verified_at' => 'datetime', 'password' => 'hashed', 'is_admin' => 'boolean']; }
    public function canAccessPanel(Panel $panel): bool { return (bool) $this->is_admin; }
    public function participantProfile(): HasOne { return $this->hasOne(ParticipantProfile::class); }
    public function registrations(): HasMany { return $this->hasMany(Registration::class); }
    public function motorcycles(): HasMany { return $this->hasMany(Motorcycle::class); }
}
