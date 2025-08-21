<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class AdminDashboardFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_fetch_dashboard_with_filters(): void
    {
        $admin = User::factory()->create();
        if (method_exists($admin, 'assignRole')) $admin->assignRole('admin');

        // Seed some bookings
        Booking::factory()->count(3)->create();
        Booking::factory()->create([
            'status' => 'cancelled',
            'date'   => Carbon::today()->toDateString(),
        ]);

        $res = $this->actingAs($admin, 'sanctum')
            ->getJson('/api/v1/admin/dashboard?date_from='.Carbon::today()->subWeek()->toDateString().'&date_to='.Carbon::today()->toDateString());

        $res->assertOk()
            ->assertJsonStructure([
                'bookings_per_provider',
                'cancelled_vs_confirmed',
                'peak_hours_daily',
                'peak_hours_weekly',
                'avg_duration_per_customer',
            ]);
    }
}
