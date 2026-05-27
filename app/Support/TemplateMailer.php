<?php

namespace App\Support;

use App\Mail\TemplateMail;
use App\Models\MailLog;
use App\Models\MailTemplate;
use App\Models\Registration;
use Illuminate\Support\Facades\Mail;

class TemplateMailer
{
    public static function send(Registration $registration, MailTemplate $template): MailLog
    {
        $subject = self::render($template->subject, $registration);
        $body = self::render($template->body, $registration);

        Mail::to($registration->user->email)->send(new TemplateMail($subject, $body));

        return MailLog::create([
            'user_id' => $registration->user_id,
            'registration_id' => $registration->id,
            'mail_template_id' => $template->id,
            'subject' => $subject,
            'body' => $body,
            'sent_at' => now(),
            'status' => 'sent',
        ]);
    }

    public static function sendByType(Registration $registration, string $type): ?MailLog
    {
        $template = MailTemplate::where('type', $type)->where('is_active', true)->first();
        return $template ? self::send($registration, $template) : null;
    }

    private static function render(string $text, Registration $registration): string
    {
        return strtr($text, [
            '{{ name }}' => $registration->user->name,
            '{{ event }}' => $registration->event->name,
            '{{ package }}' => $registration->package->name,
        ]);
    }
}
