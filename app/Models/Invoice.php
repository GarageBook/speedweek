<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $guarded = []; protected function casts(): array { return ['due_at'=>'date','sent_at'=>'datetime','paid_at'=>'datetime']; } public function registration(): BelongsTo { return $this->belongsTo(Registration::class); }
}
