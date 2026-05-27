<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParticipantChecklistItem extends Model
{
    protected $guarded = []; protected function casts(): array { return ['completed_at'=>'datetime']; } public function registration(): BelongsTo { return $this->belongsTo(Registration::class); } public function checklistItem(): BelongsTo { return $this->belongsTo(ChecklistItem::class); }
}
