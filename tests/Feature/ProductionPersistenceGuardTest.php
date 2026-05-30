<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class ProductionPersistenceGuardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guard_fails_for_non_persistent_sqlite_path(): void
    {
        config([
            'app.env' => 'production',
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => '/app/database/database.sqlite',
            'session.driver' => 'database',
            'session.lifetime' => 43200,
            'session.expire_on_close' => false,
        ]);

        $exitCode = Artisan::call('ops:assert-production-persistence', ['--force-production' => true]);

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString('FATAL PRODUCTION PERSISTENCE GUARD', Artisan::output());
    }

    public function test_guard_fails_for_file_session_driver(): void
    {
        config([
            'app.env' => 'production',
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => '/var/data/database.sqlite',
            'session.driver' => 'file',
            'session.lifetime' => 43200,
            'session.expire_on_close' => false,
        ]);

        $exitCode = Artisan::call('ops:assert-production-persistence', ['--force-production' => true]);

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString("SESSION_DRIVER must be 'database'", Artisan::output());
    }

    public function test_guard_passes_for_persistent_sqlite_and_database_sessions(): void
    {
        config([
            'app.env' => 'production',
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => '/var/data/database.sqlite',
            'session.driver' => 'database',
            'session.lifetime' => 43200,
            'session.expire_on_close' => false,
        ]);

        $exitCode = Artisan::call('ops:assert-production-persistence', ['--force-production' => true]);

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Production persistence guard passed.', Artisan::output());
    }
}
