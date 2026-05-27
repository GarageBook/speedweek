<?php

namespace App\Support;

class SpeedweekLabels
{
    public static function registrationStatus(?string $status): string
    {
        return self::map($status, [
            'draft' => 'concept',
            'pending' => 'in behandeling',
            'confirmed' => 'bevestigd',
            'cancelled' => 'geannuleerd',
            'waiting_list' => 'wachtlijst',
        ]);
    }

    public static function paymentStatus(?string $status): string
    {
        return self::map($status, [
            'unpaid' => 'nog niet betaald',
            'deposit_invoiced' => 'aanbetaling verstuurd',
            'deposit_paid' => 'aanbetaling betaald',
            'fully_paid' => 'volledig betaald',
            'refunded' => 'terugbetaald',
            'credited' => 'gecrediteerd',
        ]);
    }

    public static function invoiceType(?string $type): string
    {
        return self::map($type, [
            'deposit' => 'aanbetaling',
            'final' => 'eindfactuur',
            'credit' => 'creditfactuur',
        ]);
    }

    public static function invoiceStatus(?string $status): string
    {
        return self::map($status, [
            'draft' => 'concept',
            'sent' => 'verstuurd',
            'paid' => 'betaald',
            'cancelled' => 'geannuleerd',
            'credited' => 'gecrediteerd',
        ]);
    }

    public static function programmeType(?string $type): string
    {
        return self::map($type, [
            'travel' => 'reis',
            'briefing' => 'briefing',
            'track_session' => 'baansessie',
            'meal' => 'maaltijd',
            'bus_transfer' => 'bustransfer',
            'rest_day' => 'rustdag',
            'technical' => 'technisch',
            'other' => 'overig',
        ]);
    }

    public static function registrationStatusOptions(): array
    {
        return [
            'draft' => self::registrationStatus('draft'),
            'pending' => self::registrationStatus('pending'),
            'confirmed' => self::registrationStatus('confirmed'),
            'cancelled' => self::registrationStatus('cancelled'),
            'waiting_list' => self::registrationStatus('waiting_list'),
        ];
    }

    public static function paymentStatusOptions(): array
    {
        return [
            'unpaid' => self::paymentStatus('unpaid'),
            'deposit_invoiced' => self::paymentStatus('deposit_invoiced'),
            'deposit_paid' => self::paymentStatus('deposit_paid'),
            'fully_paid' => self::paymentStatus('fully_paid'),
            'refunded' => self::paymentStatus('refunded'),
            'credited' => self::paymentStatus('credited'),
        ];
    }

    public static function invoiceTypeOptions(): array
    {
        return [
            'deposit' => self::invoiceType('deposit'),
            'final' => self::invoiceType('final'),
            'credit' => self::invoiceType('credit'),
        ];
    }

    public static function invoiceStatusOptions(): array
    {
        return [
            'draft' => self::invoiceStatus('draft'),
            'sent' => self::invoiceStatus('sent'),
            'paid' => self::invoiceStatus('paid'),
            'cancelled' => self::invoiceStatus('cancelled'),
            'credited' => self::invoiceStatus('credited'),
        ];
    }

    public static function programmeTypeOptions(): array
    {
        return [
            'travel' => self::programmeType('travel'),
            'briefing' => self::programmeType('briefing'),
            'track_session' => self::programmeType('track_session'),
            'meal' => self::programmeType('meal'),
            'bus_transfer' => self::programmeType('bus_transfer'),
            'rest_day' => self::programmeType('rest_day'),
            'technical' => self::programmeType('technical'),
            'other' => self::programmeType('other'),
        ];
    }

    private static function map(?string $value, array $labels): string
    {
        if ($value === null || $value === '') {
            return '-';
        }

        return $labels[$value] ?? str_replace('_', ' ', $value);
    }
}
