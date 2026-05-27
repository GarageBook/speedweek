<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['starts_at'=>'date','ends_at'=>'date','final_payment_due_at'=>'date','bike_dropoff_datetime'=>'datetime','return_datetime'=>'datetime']; }
    public function packages(): HasMany { return $this->hasMany(Package::class); }
    public function registrations(): HasMany { return $this->hasMany(Registration::class); }
    public function checklistItems(): HasMany { return $this->hasMany(ChecklistItem::class); }
    public function programmeItems(): HasMany { return $this->hasMany(ProgrammeItem::class); }
}
