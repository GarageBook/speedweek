<?php

namespace App\Services;

use App\Models\Registration;
use App\Models\TrackLapResult;
use App\Models\User;
use Illuminate\Support\Collection;

class TrackResultsService
{
    private const TRACKDAY_TITLE = 'Trackdays 1 t/m 3, donderdag 1 oktober t/m zaterdag 3 oktober 2026';

    private const TRACKDAY_BLUEPRINTS = [
        [
            'trackday_title' => 'Trackday 1',
            'date_label' => 'donderdag 1 oktober 2026',
            'sessions' => [
                ['start_time' => '09:00', 'end_time' => '09:25', 'weather' => 'Droog, 18 graden', 'focus' => 'Opbouw', 'profile' => 'opening', 'target_best_lap_ms' => 98231],
                ['start_time' => '10:15', 'end_time' => '10:40', 'weather' => 'Droog, 21 graden', 'focus' => 'Ritme vinden', 'profile' => 'rhythm', 'target_best_lap_ms' => 97108],
                ['start_time' => '11:45', 'end_time' => '12:10', 'weather' => 'Licht bewolkt, 22 graden', 'focus' => 'Referentie pace', 'profile' => 'reference', 'target_best_lap_ms' => 96284],
                ['start_time' => '14:00', 'end_time' => '14:25', 'weather' => 'Droog, 24 graden', 'focus' => 'Time attack', 'profile' => 'attack', 'target_best_lap_ms' => 95742],
                ['start_time' => '15:15', 'end_time' => '15:40', 'weather' => 'Warm, 25 graden', 'focus' => 'Race-simulatie', 'profile' => 'race', 'target_best_lap_ms' => 95984],
                ['start_time' => '16:30', 'end_time' => '16:55', 'weather' => 'Droog, 23 graden', 'focus' => 'Kwalificatie-run', 'profile' => 'qualifying', 'target_best_lap_ms' => 95421],
            ],
        ],
        [
            'trackday_title' => 'Trackday 2',
            'date_label' => 'vrijdag 2 oktober 2026',
            'sessions' => [
                ['start_time' => '09:00', 'end_time' => '09:25', 'weather' => 'Fris en droog, 19 graden', 'focus' => 'Herstart in clean air', 'profile' => 'opening', 'target_best_lap_ms' => 95018],
                ['start_time' => '10:15', 'end_time' => '10:40', 'weather' => 'Zonnig, 22 graden', 'focus' => 'Sectors koppelen', 'profile' => 'rhythm', 'target_best_lap_ms' => 94782],
                ['start_time' => '11:45', 'end_time' => '12:10', 'weather' => 'Licht bewolkt, 23 graden', 'focus' => 'Constante mid-1:34 pace', 'profile' => 'reference', 'target_best_lap_ms' => 94436],
                ['start_time' => '14:00', 'end_time' => '14:25', 'weather' => 'Droog, 25 graden', 'focus' => 'Rempunten maximaliseren', 'profile' => 'attack', 'target_best_lap_ms' => 94188],
                ['start_time' => '15:15', 'end_time' => '15:40', 'weather' => 'Warm, 26 graden', 'focus' => 'Long-run consistentie', 'profile' => 'race', 'target_best_lap_ms' => 94351],
                ['start_time' => '16:30', 'end_time' => '16:55', 'weather' => 'Rustige wind, 24 graden', 'focus' => 'Late qualifying push', 'profile' => 'qualifying', 'target_best_lap_ms' => 93974],
            ],
        ],
        [
            'trackday_title' => 'Trackday 3',
            'date_label' => 'zaterdag 3 oktober 2026',
            'sessions' => [
                ['start_time' => '09:00', 'end_time' => '09:25', 'weather' => 'Droog, 20 graden', 'focus' => 'Direct op vrijdagpace', 'profile' => 'opening', 'target_best_lap_ms' => 93842],
                ['start_time' => '10:15', 'end_time' => '10:40', 'weather' => 'Zonnig, 23 graden', 'focus' => 'Sector 1 en 2 combineren', 'profile' => 'rhythm', 'target_best_lap_ms' => 93618],
                ['start_time' => '11:45', 'end_time' => '12:10', 'weather' => 'Helder, 24 graden', 'focus' => 'Racepace onder 1:34', 'profile' => 'reference', 'target_best_lap_ms' => 93477],
                ['start_time' => '14:00', 'end_time' => '14:25', 'weather' => 'Warm, 26 graden', 'focus' => 'Hot-lap voorbereiding', 'profile' => 'attack', 'target_best_lap_ms' => 93295],
                ['start_time' => '15:15', 'end_time' => '15:40', 'weather' => 'Droog en stabiel, 26 graden', 'focus' => 'Volle stint zonder verval', 'profile' => 'race', 'target_best_lap_ms' => 93386],
                ['start_time' => '16:30', 'end_time' => '16:55', 'weather' => 'Avondzon, 24 graden', 'focus' => 'Laatste kwalificatie-run', 'profile' => 'qualifying', 'target_best_lap_ms' => 93058],
            ],
        ],
    ];

    private const LAP_PROFILE_LIBRARY = [
        'opening' => [
            ['offset' => 1642, 'trend' => 'outlap', 'note' => 'Outlap, banden en remmen op temperatuur'],
            ['offset' => 1288, 'trend' => 'build', 'note' => 'Rustig insturen en referenties zoeken'],
            ['offset' => 1174, 'trend' => 'build', 'note' => 'Meer snelheid mee uit bocht 5'],
            ['offset' => 1048, 'trend' => 'build', 'note' => 'Eerste nette complete ronde'],
            ['offset' => 923, 'trend' => 'build', 'note' => 'Rempunt iets later'],
            ['offset' => 1116, 'trend' => 'traffic', 'note' => 'Verkeer in sector 2'],
            ['offset' => 834, 'trend' => 'green', 'note' => 'Strakkere exit op het rechte stuk'],
            ['offset' => 746, 'trend' => 'green', 'note' => 'Meer vertrouwen op de voorkant'],
            ['offset' => 680, 'trend' => 'green', 'note' => 'Referentie komt in beeld'],
            ['offset' => 611, 'trend' => 'green', 'note' => 'Constante sector 1'],
            ['offset' => 552, 'trend' => 'green', 'note' => 'Meer apex-snelheid'],
            ['offset' => 501, 'trend' => 'green', 'note' => 'Eerste ronde onder de eerdere referentie'],
            ['offset' => 447, 'trend' => 'green', 'note' => 'Nettere lijn door de snelle doordraaier'],
            ['offset' => 395, 'trend' => 'green', 'note' => 'Vloeiende overgang gas-op'],
            ['offset' => 348, 'trend' => 'green', 'note' => 'Steeds stabieler remmen'],
            ['offset' => 291, 'trend' => 'green', 'note' => 'Sector 3 opbouwend snel'],
            ['offset' => 244, 'trend' => 'green', 'note' => 'Controle en ritme aanwezig'],
            ['offset' => 190, 'trend' => 'green', 'note' => 'Laatste sector bijna op pace'],
            ['offset' => 123, 'trend' => 'green', 'note' => 'Beste eerste en tweede sector'],
            ['offset' => 0, 'trend' => 'purple', 'note' => 'Beste lap van deze openingssessie'],
        ],
        'rhythm' => [
            ['offset' => 1398, 'trend' => 'outlap', 'note' => 'Outlap en druk op voorband opbouwen'],
            ['offset' => 1192, 'trend' => 'build', 'note' => 'Sneller in de eerste richtingwissel'],
            ['offset' => 1033, 'trend' => 'build', 'note' => 'Motor eerder recht op exit'],
            ['offset' => 954, 'trend' => 'build', 'note' => 'Rempunt achterop aangescherpt'],
            ['offset' => 880, 'trend' => 'green', 'note' => 'Tijdwinst in de lange linker'],
            ['offset' => 771, 'trend' => 'green', 'note' => 'Constante eerste sector'],
            ['offset' => 704, 'trend' => 'green', 'note' => 'Betere drive uit bocht 8'],
            ['offset' => 651, 'trend' => 'green', 'note' => 'Ronde sluit goed aan op de vorige'],
            ['offset' => 826, 'trend' => 'traffic', 'note' => 'Inhaalactie kost momentum'],
            ['offset' => 548, 'trend' => 'green', 'note' => 'Weer terug in ritme'],
            ['offset' => 501, 'trend' => 'green', 'note' => 'Sector 2 groen'],
            ['offset' => 472, 'trend' => 'green', 'note' => 'Meer rotatiesnelheid bij insturen'],
            ['offset' => 449, 'trend' => 'green', 'note' => 'Stabiel in de snelle knik'],
            ['offset' => 398, 'trend' => 'green', 'note' => 'Minder correcties op de exit'],
            ['offset' => 351, 'trend' => 'green', 'note' => 'Rijder komt op pace'],
            ['offset' => 312, 'trend' => 'green', 'note' => 'Rem los en laten rollen'],
            ['offset' => 266, 'trend' => 'green', 'note' => 'Aanscherping in de laatste hairpin'],
            ['offset' => 219, 'trend' => 'green', 'note' => 'Snellere eerste helft van de ronde'],
            ['offset' => 142, 'trend' => 'green', 'note' => 'Nette push-ronde'],
            ['offset' => 0, 'trend' => 'purple', 'note' => 'Sessie-afsluiter met duidelijke progressie'],
        ],
        'reference' => [
            ['offset' => 1221, 'trend' => 'outlap', 'note' => 'Outlap, direct meer vertrouwen'],
            ['offset' => 1044, 'trend' => 'build', 'note' => 'Warme band, tempo snel terug'],
            ['offset' => 931, 'trend' => 'green', 'note' => 'Hogere minimumsnelheid'],
            ['offset' => 844, 'trend' => 'green', 'note' => 'Stabiele lijn in sector 1'],
            ['offset' => 778, 'trend' => 'green', 'note' => 'Gas eerder open op exit'],
            ['offset' => 702, 'trend' => 'green', 'note' => 'Remmen rustiger en dieper'],
            ['offset' => 648, 'trend' => 'green', 'note' => 'Betere wissel door de chicane'],
            ['offset' => 609, 'trend' => 'green', 'note' => 'Stevige referentieronde'],
            ['offset' => 569, 'trend' => 'green', 'note' => 'Pace nu reproduceerbaar'],
            ['offset' => 521, 'trend' => 'green', 'note' => 'Eerste ronde in dit venster'],
            ['offset' => 487, 'trend' => 'green', 'note' => 'Ritme vergelijkbaar met kopgroep'],
            ['offset' => 461, 'trend' => 'green', 'note' => 'Soepele motor uit snelle bocht'],
            ['offset' => 433, 'trend' => 'green', 'note' => 'Constante sector 3'],
            ['offset' => 392, 'trend' => 'green', 'note' => 'Beter gebruik van de curbs'],
            ['offset' => 341, 'trend' => 'green', 'note' => 'Snelle exit uit langzame bochten'],
            ['offset' => 297, 'trend' => 'green', 'note' => 'Lijn zit erin'],
            ['offset' => 245, 'trend' => 'green', 'note' => 'Extra vertrouwen bij aanremmen'],
            ['offset' => 199, 'trend' => 'green', 'note' => 'Sterke referentie voor de volgende sessie'],
            ['offset' => 121, 'trend' => 'green', 'note' => 'Push-lap zonder foutjes'],
            ['offset' => 0, 'trend' => 'purple', 'note' => 'Beste sessie tot nu toe'],
        ],
        'attack' => [
            ['offset' => 1084, 'trend' => 'outlap', 'note' => 'Outlap met frisse achterband'],
            ['offset' => 956, 'trend' => 'build', 'note' => 'Direct hoge basissnelheid'],
            ['offset' => 873, 'trend' => 'green', 'note' => 'Meer commit in de snelle linker'],
            ['offset' => 781, 'trend' => 'green', 'note' => 'Remdruk netter opgebouwd'],
            ['offset' => 695, 'trend' => 'green', 'note' => 'Tijdwinst in sector 1'],
            ['offset' => 618, 'trend' => 'green', 'note' => 'Harde remzone stabiel'],
            ['offset' => 557, 'trend' => 'green', 'note' => 'Betere bochtsnelheid over de top'],
            ['offset' => 509, 'trend' => 'green', 'note' => 'Motor sneller omgelegd'],
            ['offset' => 468, 'trend' => 'green', 'note' => 'Mooie doorloop in sector 2'],
            ['offset' => 431, 'trend' => 'green', 'note' => 'Geen wielspin op exit'],
            ['offset' => 399, 'trend' => 'green', 'note' => 'Kwalificatie-achtig ritme'],
            ['offset' => 362, 'trend' => 'green', 'note' => 'Snelle eerste helft'],
            ['offset' => 328, 'trend' => 'green', 'note' => 'Constante push'],
            ['offset' => 284, 'trend' => 'green', 'note' => 'Nog later remmen'],
            ['offset' => 245, 'trend' => 'green', 'note' => 'Richting persoonlijk venster'],
            ['offset' => 211, 'trend' => 'green', 'note' => 'Strakke apex in laatste bocht'],
            ['offset' => 183, 'trend' => 'green', 'note' => 'Sector 2 beste van de dag'],
            ['offset' => 141, 'trend' => 'green', 'note' => 'Volledige ronde bijna clean'],
            ['offset' => 94, 'trend' => 'green', 'note' => 'Laatste checks voor hot lap'],
            ['offset' => 0, 'trend' => 'purple', 'note' => 'Time attack geland'],
        ],
        'race' => [
            ['offset' => 512, 'trend' => 'outlap', 'note' => 'Race-simulatie start, volle tank-gevoel'],
            ['offset' => 434, 'trend' => 'steady', 'note' => 'Direct in een stabiel ritme'],
            ['offset' => 388, 'trend' => 'steady', 'note' => 'Constante eerste sector'],
            ['offset' => 352, 'trend' => 'steady', 'note' => 'Geen onnodige risico\'s'],
            ['offset' => 319, 'trend' => 'steady', 'note' => 'Sterke referentiepace'],
            ['offset' => 287, 'trend' => 'steady', 'note' => 'Zuivere exits en weinig correcties'],
            ['offset' => 264, 'trend' => 'steady', 'note' => 'Pace blijft staan'],
            ['offset' => 248, 'trend' => 'steady', 'note' => 'Bandenslijtage goed onder controle'],
            ['offset' => 233, 'trend' => 'steady', 'note' => 'Constante race pace'],
            ['offset' => 219, 'trend' => 'green', 'note' => 'Klein stapje sneller in vrije ruimte'],
            ['offset' => 241, 'trend' => 'traffic', 'note' => 'Lichte hinder bij inhalen'],
            ['offset' => 228, 'trend' => 'steady', 'note' => 'Snel terug in ritme'],
            ['offset' => 214, 'trend' => 'steady', 'note' => 'Lage variatie tussen de ronden'],
            ['offset' => 198, 'trend' => 'steady', 'note' => 'Goede tractie uit de hairpin'],
            ['offset' => 183, 'trend' => 'steady', 'note' => 'Tempo blijft reproduceerbaar'],
            ['offset' => 169, 'trend' => 'green', 'note' => 'Laatste derde van de stint nog fris'],
            ['offset' => 143, 'trend' => 'green', 'note' => 'Nagenoeg clean hot lap'],
            ['offset' => 112, 'trend' => 'green', 'note' => 'Richting persoonlijk record'],
            ['offset' => 87, 'trend' => 'green', 'note' => 'Nog later op het gas'],
            ['offset' => 0, 'trend' => 'purple', 'note' => 'Sterkste ronde van de lange stint'],
        ],
        'qualifying' => [
            ['offset' => 267, 'trend' => 'outlap', 'note' => 'Korte outlap met vrije baan zoeken'],
            ['offset' => 214, 'trend' => 'build', 'note' => 'Direct op tempo in sector 1'],
            ['offset' => 186, 'trend' => 'green', 'note' => 'Meer snelheid mee over de top'],
            ['offset' => 163, 'trend' => 'green', 'note' => 'Eerste representatieve push'],
            ['offset' => 142, 'trend' => 'green', 'note' => 'Rempunten precies op referentie'],
            ['offset' => 131, 'trend' => 'green', 'note' => 'Minder stuurinput in de snelle knik'],
            ['offset' => 118, 'trend' => 'green', 'note' => 'Snelle eerste helft'],
            ['offset' => 104, 'trend' => 'green', 'note' => 'Doorkomen in sector 2 voelt sterk'],
            ['offset' => 89, 'trend' => 'green', 'note' => 'Kleine stap onder de vorige beste'],
            ['offset' => 76, 'trend' => 'green', 'note' => 'Strakke laatste hairpin'],
            ['offset' => 64, 'trend' => 'green', 'note' => 'Bike staat mooi stil bij aanremmen'],
            ['offset' => 49, 'trend' => 'green', 'note' => 'Vrije ronde zonder verkeer'],
            ['offset' => 33, 'trend' => 'green', 'note' => 'Bijna perfecte middensector'],
            ['offset' => 21, 'trend' => 'green', 'note' => 'Laatste details vallen op hun plek'],
            ['offset' => 12, 'trend' => 'green', 'note' => 'Push tot aan de laatste apex'],
            ['offset' => 6, 'trend' => 'green', 'note' => 'Minieme winst in sector 3'],
            ['offset' => 3, 'trend' => 'green', 'note' => 'Nog een bijna-PR'],
            ['offset' => 0, 'trend' => 'purple', 'note' => 'Absolute best lap van de dag'],
            ['offset' => 118, 'trend' => 'cooldown', 'note' => 'Cooldown met verkeer vooraan'],
            ['offset' => 267, 'trend' => 'cooldown', 'note' => 'Uitrollen en terug naar de pits'],
        ],
    ];

    public function trackdayTitle(): string
    {
        return self::TRACKDAY_TITLE;
    }

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

        foreach ($this->sessionBlueprints() as $session) {
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

        return collect($this->sessionBlueprints())
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
                    'day_number' => $session['day_number'],
                    'start_time' => $session['start_time'],
                    'end_time' => $session['end_time'],
                    'session_label' => $this->sessionLabel($session),
                    'weather' => $session['weather'],
                    'focus' => $session['focus'],
                    'laps' => $this->decorateLaps($sessionResults),
                ];
            })
            ->all();
    }

    public function personalLapTimesForUser(User $user): array
    {
        $registrations = $this->latestRiderRegistrations();
        $registrations->each(fn (Registration $registration) => $this->ensureForRegistration($registration));

        $registration = $registrations->firstWhere('user_id', $user->id);

        if (! $registration) {
            return collect($this->sessionBlueprints())
                ->map(fn (array $session): array => $this->emptyPersonalSession($session))
                ->all();
        }

        $registration->loadMissing('user', 'motorcycle', 'trackLapResults');
        $motorcycle = $registration->motorcycle ?? $registration->user?->motorcycles()->latest()->first();
        $bikeLabel = $this->motorcycleLabel($motorcycle);

        return collect($this->sessionBlueprints())
            ->map(function (array $session) use ($registration, $bikeLabel): array {
                $result = $registration->trackLapResults->firstWhere('session_number', $session['session_number']);
                $bestLapMs = (int) ($result?->best_lap_ms ?? $this->lapMilliseconds($session['session_number'], (int) $registration->user_id));
                $laps = $this->buildDetailedLaps($session, $bestLapMs);
                $lapMilliseconds = collect($laps)->pluck('lap_ms');
                $averageLapMs = (int) round($lapMilliseconds->avg());
                $firstLapMs = (int) ($laps[0]['lap_ms'] ?? $bestLapMs);

                return [
                    'session_number' => $session['session_number'],
                    'day_number' => $session['day_number'],
                    'start_time' => $session['start_time'],
                    'end_time' => $session['end_time'],
                    'session_label' => $this->sessionLabel($session),
                    'weather' => $session['weather'],
                    'focus' => $session['focus'],
                    'rider' => $registration->user?->name ?? 'Onbekende rijder',
                    'bike' => $bikeLabel,
                    'best_lap' => $this->formatLapTime($bestLapMs),
                    'best_lap_ms' => $bestLapMs,
                    'average_lap' => $this->formatLapTime($averageLapMs),
                    'average_lap_ms' => $averageLapMs,
                    'improvement' => $this->formatImprovement($firstLapMs - $bestLapMs),
                    'lap_count' => count($laps),
                    'laps' => $laps,
                ];
            })
            ->all();
    }

    public function summarizePersonalLapTimes(array $sessions): array
    {
        $completedSessions = collect($sessions)->filter(fn (array $session): bool => ! empty($session['laps']))->values();

        if ($completedSessions->isEmpty()) {
            return [
                'best_lap' => '-',
                'average_lap' => '-',
                'improvement' => '-',
                'session_count' => 0,
                'lap_count' => 0,
            ];
        }

        $allLaps = $completedSessions->flatMap(fn (array $session): array => $session['laps']);
        $overallBestMs = (int) $allLaps->min('lap_ms');
        $overallAverageMs = (int) round($allLaps->avg('lap_ms'));
        $firstSessionBest = (int) $completedSessions->first()['best_lap_ms'];
        $lastSessionBest = (int) $completedSessions->last()['best_lap_ms'];

        return [
            'best_lap' => $this->formatLapTime($overallBestMs),
            'average_lap' => $this->formatLapTime($overallAverageMs),
            'improvement' => $this->formatImprovement($firstSessionBest - $lastSessionBest),
            'session_count' => $completedSessions->count(),
            'lap_count' => $allLaps->count(),
        ];
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
                $lap['gap'] = $index === 0 ? '-' : '+' . $this->formatDelta((int) $lap['lap_ms'] - $fastestLap);
                unset($lap['lap_ms']);

                return $lap;
            })
            ->all();
    }

    private function buildDetailedLaps(array $session, int $bestLapMs): array
    {
        $profile = self::LAP_PROFILE_LIBRARY[$session['profile']] ?? [];

        return collect($profile)
            ->values()
            ->map(function (array $entry, int $index) use ($bestLapMs): array {
                $lapMs = $bestLapMs + (int) $entry['offset'];
                $trend = (string) $entry['trend'];

                return [
                    'lap_number' => $index + 1,
                    'lap' => $this->formatLapTime($lapMs),
                    'lap_ms' => $lapMs,
                    'delta_to_best' => (int) $entry['offset'] === 0 ? '-' : '+' . $this->formatDelta((int) $entry['offset']),
                    'note' => $entry['note'],
                    'trend_label' => $this->trendLabel($trend),
                    'trend_class' => $this->trendClass($trend),
                    'is_best' => (int) $entry['offset'] === 0,
                ];
            })
            ->all();
    }

    private function lapMilliseconds(int $sessionNumber, int $userId): int
    {
        $session = $this->sessionBlueprint($sessionNumber);
        $baseMilliseconds = (int) ($session['target_best_lap_ms'] ?? 98231);

        return $baseMilliseconds + (($userId % 7) * 19);
    }

    private function sessionBlueprints(): array
    {
        static $blueprints;

        if ($blueprints !== null) {
            return $blueprints;
        }

        $blueprints = [];
        $sessionNumber = 1;

        foreach (self::TRACKDAY_BLUEPRINTS as $dayIndex => $trackday) {
            foreach ($trackday['sessions'] as $daySessionIndex => $session) {
                $blueprints[] = [
                    'session_number' => $sessionNumber++,
                    'day_number' => $dayIndex + 1,
                    'day_session_number' => $daySessionIndex + 1,
                    'trackday_title' => $trackday['trackday_title'],
                    'date_label' => $trackday['date_label'],
                    ...$session,
                ];
            }
        }

        return $blueprints;
    }

    private function sessionBlueprint(int $sessionNumber): ?array
    {
        foreach ($this->sessionBlueprints() as $session) {
            if ($session['session_number'] === $sessionNumber) {
                return $session;
            }
        }

        return null;
    }

    private function sessionLabel(array $session): string
    {
        return sprintf(
            '%s • Sessie %d: %s - %s',
            $session['trackday_title'],
            $session['day_session_number'],
            $session['start_time'],
            $session['end_time']
        );
    }

    private function emptyPersonalSession(array $session): array
    {
        return [
            'session_number' => $session['session_number'],
            'day_number' => $session['day_number'],
            'start_time' => $session['start_time'],
            'end_time' => $session['end_time'],
            'session_label' => $this->sessionLabel($session),
            'weather' => $session['weather'],
            'focus' => $session['focus'],
            'best_lap' => '-',
            'best_lap_ms' => null,
            'average_lap' => '-',
            'average_lap_ms' => null,
            'improvement' => '-',
            'lap_count' => 0,
            'laps' => [],
        ];
    }

    private function formatLapTime(int $milliseconds): string
    {
        $minutes = intdiv($milliseconds, 60000);
        $seconds = intdiv($milliseconds % 60000, 1000);
        $remainingMilliseconds = $milliseconds % 1000;

        return sprintf('%d:%02d.%03d', $minutes, $seconds, $remainingMilliseconds);
    }

    private function formatDelta(int $milliseconds): string
    {
        $seconds = intdiv($milliseconds, 1000);
        $remainingMilliseconds = abs($milliseconds % 1000);

        return sprintf('%d.%03d', $seconds, $remainingMilliseconds);
    }

    private function formatImprovement(int $milliseconds): string
    {
        if ($milliseconds <= 0) {
            return 'Geen winst';
        }

        return $this->formatDelta($milliseconds) . ' sneller';
    }

    private function trendLabel(string $trend): string
    {
        return match ($trend) {
            'outlap' => 'Outlap',
            'build' => 'Opbouw',
            'traffic' => 'Verkeer',
            'green' => 'Groen',
            'purple' => 'Paars',
            'steady' => 'Stabiel',
            'cooldown' => 'Cooldown',
            default => 'Tempo',
        };
    }

    private function trendClass(string $trend): string
    {
        return match ($trend) {
            'outlap' => 'bg-zinc-700 text-zinc-100',
            'build' => 'bg-sky-600/20 text-sky-300',
            'traffic' => 'bg-amber-500/20 text-amber-300',
            'green' => 'bg-emerald-500/20 text-emerald-300',
            'purple' => 'bg-fuchsia-500/20 text-fuchsia-200',
            'steady' => 'bg-cyan-500/20 text-cyan-200',
            'cooldown' => 'bg-zinc-600/30 text-zinc-200',
            default => 'bg-zinc-700 text-zinc-100',
        };
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
