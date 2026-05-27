<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistItem extends Model
{
    protected $guarded = []; protected function casts(): array { return ['is_default'=>'boolean']; } public function event(): BelongsTo { return $this->belongsTo(Event::class); }
}
