<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TravelInfo extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['outbound_departure_at'=>'datetime','outbound_arrival_at'=>'datetime','return_departure_at'=>'datetime','return_arrival_at'=>'datetime']; }
    public function registration(): BelongsTo { return $this->belongsTo(Registration::class); }
}
