<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['is_active'=>'boolean']; }
    public function event(): BelongsTo { return $this->belongsTo(Event::class); }
    public function features(): HasMany { return $this->hasMany(PackageFeature::class); }
    public function registrations(): HasMany { return $this->hasMany(Registration::class); }
    public function includesFeature(string $key): bool { return $this->features->firstWhere('key', $key)?->included ?? false; }
    public function isSpectator(): bool { return $this->slug === 'spectator'; }
}
