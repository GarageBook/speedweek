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
            'app.key' => 'base64:test-key',
            'app.url' => 'https://speedweek.bergmolen.nl',
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => '/app/database/database.sqlite',
            'session.driver' => 'database',
            'session.lifetime' => 43200,
            'session.expire_on_close' => false,
            'session.secure' => true,
            'session.same_site' => 'lax',
        ]);

        $exitCode = Artisan::call('ops:assert-production-persistence', ['--force-production' => true]);

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString('FATAL PRODUCTION PERSISTENCE GUARD', Artisan::output());
    }

    public function test_guard_fails_for_file_session_driver(): void
    {
        config([
            'app.key' => 'base64:test-key',
            'app.url' => 'https://speedweek.bergmolen.nl',
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => '/var/data/database.sqlite',
            'session.driver' => 'file',
            'session.lifetime' => 43200,
            'session.expire_on_close' => false,
            'session.secure' => true,
            'session.same_site' => 'lax',
        ]);

        $exitCode = Artisan::call('ops:assert-production-persistence', ['--force-production' => true]);

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString("SESSION_DRIVER must be 'database'", Artisan::output());
    }

    public function test_guard_fails_when_app_key_is_missing(): void
    {
        config([
            'app.key' => null,
            'app.url' => 'https://speedweek.bergmolen.nl',
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => '/var/data/database.sqlite',
            'session.driver' => 'database',
            'session.lifetime' => 43200,
            'session.expire_on_close' => false,
            'session.secure' => true,
            'session.same_site' => 'lax',
        ]);

        $exitCode = Artisan::call('ops:assert-production-persistence', ['--force-production' => true]);

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString('APP_KEY must be set and stable across deploys.', Artisan::output());
    }

    public function test_guard_fails_when_secure_cookie_is_disabled(): void
    {
        config([
            'app.key' => 'base64:test-key',
            'app.url' => 'https://speedweek.bergmolen.nl',
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => '/var/data/database.sqlite',
            'session.driver' => 'database',
            'session.lifetime' => 43200,
            'session.expire_on_close' => false,
            'session.secure' => false,
            'session.same_site' => 'lax',
        ]);

        $exitCode = Artisan::call('ops:assert-production-persistence', ['--force-production' => true]);

        $this->assertSame(1, $exitCode);
        $this->assertStringContainsString('SESSION_SECURE_COOKIE must be true in production.', Artisan::output());
    }

    public function test_guard_passes_for_paid_render_persistent_sqlite_setup(): void
    {
        config([
            'app.key' => 'base64:test-key',
            'app.url' => 'https://speedweek.bergmolen.nl',
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => '/var/data/database.sqlite',
            'session.driver' => 'database',
            'session.lifetime' => 43200,
            'session.expire_on_close' => false,
            'session.secure' => true,
            'session.same_site' => 'lax',
        ]);

        $exitCode = Artisan::call('ops:assert-production-persistence', ['--force-production' => true]);

        $this->assertSame(0, $exitCode);
        $this->assertStringContainsString('Production persistence guard passed.', Artisan::output());
    }
}
