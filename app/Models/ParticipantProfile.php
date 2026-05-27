<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParticipantProfile extends Model
{
    protected $guarded = [];
    protected function casts(): array { return ['date_of_birth'=>'date','id_document_expires_at'=>'date']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
