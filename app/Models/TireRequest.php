<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TireRequest extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['brings_own_tires'=>'boolean','wants_tire_service'=>'boolean','wants_to_order_tires'=>'boolean']; }
    public function registration(): BelongsTo { return $this->belongsTo(Registration::class); }
    public function motorcycle(): BelongsTo { return $this->belongsTo(Motorcycle::class); }
}
