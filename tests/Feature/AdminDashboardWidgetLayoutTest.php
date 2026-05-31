<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardWidgetLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_renders_structured_operations_widget_markup(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->get('/admin')
            ->assertOk()
            ->assertSee('sw-ops-shell', false)
            ->assertSee('sw-ops-event-list', false)
            ->assertSee('sw-ops-stat', false)
            ->assertSee('sw-ops-action', false)
            ->assertSee('Operations dashboard');
    }
}
