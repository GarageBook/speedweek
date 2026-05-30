<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\TrackLapResult;
use App\Models\User;
use Illuminate\Support\Collection;

class TrackResultsService
{
    public const SESSION_BLUEPRINTS = [
        [
            'session_number' => 1,
            'start_time' => '10:00',
            'end_time' => '11:00',
            'weather' => 'Droog, 18 graden',
        ],
        [
            'session_number' => 2,
            'start_time' => '11:30',
            'end_time' => '12:30',
            'weather' => 'Droog, 21 graden',
        ],
        [
            'session_number' => 3,
            'start_time' => '14:00',
            'end_time' => '15:00',
            'weather' => 'Licht bewolkt, 22 graden',
        ],
    ];

    public function ensureForUser(User $user): void
    {
        if ($registration = $user->registration) {
            $this->ensureForRegistration($registration);
        }
    }

    public function ensureForRegistration(Registration $registration): void
    {
        $registration->loadMissing('user', 'motorcycle', 'package');

        if (! $registration->requiresMotorcycle()) {
            return;
        }

        $motorcycle = $registration->motorcycle
            ?? $registration->user?->motorcycles()->latest()->first();

        if (! $motorcycle) {
            return;
        }

        foreach (self::SESSION_BLUEPRINTS as $session) {
            TrackLapResult::firstOrCreate(
                [
                    'registration_id' => $registration->id,
                    'session_number' => $session['session_number'],
                ],
                [
                    'best_lap_ms' => $this->lapMilliseconds($session['session_number'], (int) $registration->user_id),
                ]
            );
        }
    }

    public function sessionsForUser(User $user): array
    {
        $registrations = $this->latestRiderRegistrations();
        $registrations->each(fn (Registration $registration) => $this->ensureForRegistration($registration));
        $registrations = $this->latestRiderRegistrations();

        return collect(self::SESSION_BLUEPRINTS)
            ->map(function (array $session) use ($registrations): array {
                $sessionResults = $registrations
                    ->flatMap(function (Registration $registration) use ($session): Collection {
                        $motorcycle = $registration->motorcycle
                            ?? $registration->user?->motorcycles()->latest()->first();

                        return $registration->trackLapResults
                            ->where('session_number', $session['session_number'])
                            ->map(function (TrackLapResult $result) use ($registration, $motorcycle): array {
                                return [
                                    'user_id' => (int) $registration->user_id,
                                    'rider' => $registration->user?->name ?? 'Onbekende rijder',
                                    'bike' => $this->motorcycleLabel($motorcycle),
                                    'lap_ms' => (int) $result->best_lap_ms,
                                    'lap' => $this->formatLapTime((int) $result->best_lap_ms),
                                ];
                            });
                    })
                    ->sortBy('lap_ms')
                    ->values();

                return [
                    'session_number' => $session['session_number'],
                    'start_time' => $session['start_time'],
                    'end_time' => $session['end_time'],
                    'session_label' => sprintf('Sessie %d: %s - %s', $session['session_number'], $session['start_time'], $session['end_time']),
                    'weather' => $session['weather'],
                    'laps' => $this->decorateLaps($sessionResults),
                ];
            })
            ->all();
    }

    public function personalLapTimesForUser(User $user): array
    {
        return array_map(
            function (array $session) use ($user): array {
                return [
                    'session_number' => $session['session_number'],
                    'start_time' => $session['start_time'],
                    'end_time' => $session['end_time'],
                    'session_label' => $session['session_label'],
                    'weather' => $session['weather'],
                    'laps' => array_values(array_filter(
                        $session['laps'],
                        static fn (array $lap): bool => (int) $lap['user_id'] === $user->id
                    )),
                ];
            },
            $this->sessionsForUser($user)
        );
    }

    private function latestRiderRegistrations(): Collection
    {
        return Registration::query()
            ->with(['user', 'motorcycle', 'trackLapResults'])
            ->latest()
            ->get()
            ->unique('user_id')
            ->filter(fn (Registration $registration) => $registration->requiresMotorcycle())
            ->values();
    }

    private function decorateLaps(Collection $sessionResults): array
    {
        if ($sessionResults->isEmpty()) {
            return [];
        }

        $fastestLap = (int) $sessionResults->first()['lap_ms'];

        return $sessionResults
            ->map(function (array $lap, int $index) use ($fastestLap): array {
                $lap['position'] = $index + 1;
                $lap['gap'] = $index === 0 ? '-' : '+' . $this->formatGap((int) $lap['lap_ms'] - $fastestLap);
                unset($lap['lap_ms']);

                return $lap;
            })
            ->all();
    }

    private function lapMilliseconds(int $sessionNumber, int $userId): int
    {
        $baseMilliseconds = match ($sessionNumber) {
            1 => 108231,
            2 => 107908,
            default => 107551,
        };

        return $baseMilliseconds + (($userId % 7) * 19) + ($sessionNumber * 11);
    }

    private function formatLapTime(int $milliseconds): string
    {
        $minutes = intdiv($milliseconds, 60000);
        $seconds = intdiv($milliseconds % 60000, 1000);
        $remainingMilliseconds = $milliseconds % 1000;

        return sprintf('%d:%02d.%03d', $minutes, $seconds, $remainingMilliseconds);
    }

    private function formatGap(int $milliseconds): string
    {
        return $this->formatLapTime($milliseconds);
    }

    private function motorcycleLabel($motorcycle): string
    {
        if (! $motorcycle) {
            return 'Onbekende motor';
        }

        $label = trim(implode(' ', array_filter([
            $motorcycle->brand,
            $motorcycle->model,
        ])));

        if ($motorcycle->year) {
            $label .= sprintf(' (%d)', $motorcycle->year);
        }

        if ($motorcycle->license_plate) {
            $label .= sprintf(' - %s', $motorcycle->license_plate);
        }

        return $label !== '' ? $label : 'Onbekende motor';
    }
}
