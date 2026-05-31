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
            ->assertSee('swops-widget', false)
            ->assertSee('swops-event-list', false)
            ->assertSee('swops-kpis', false)
            ->assertSee('swops-actions', false)
            ->assertSee('Operations dashboard')
            ->assertSee('Speedweek beheer')
            ->assertSee('Evenementsoverzicht');
    }
}
