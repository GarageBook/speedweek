<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgrammeItem extends Model
{
    protected $guarded = []; protected function casts(): array { return ['starts_at'=>'datetime','ends_at'=>'datetime']; } public function event(): BelongsTo { return $this->belongsTo(Event::class); }
}
