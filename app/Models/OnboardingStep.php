<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OnboardingStep extends Model
{
    protected $guarded = []; protected function casts(): array { return ['is_active'=>'boolean']; } public function event(): BelongsTo { return $this->belongsTo(Event::class); } public function mailTemplate(): BelongsTo { return $this->belongsTo(MailTemplate::class); }
}
