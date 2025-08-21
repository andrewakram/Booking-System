<?php

namespace Tests\Feature;

use Database\Seeders\RoleSeeder;
use Tests\TestCase;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use App\Events\BookingStatusChanged;

class BookingFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (class_exists(RoleSeeder::class)) {
            $this->seed(RoleSeeder::class);
        }
    }

    public function test_customer_can_create_booking(): void
    {
        $customer = User::factory()->create();
        if (method_exists($customer, 'assignRole')) $customer->assignRole('customer');

        $provider = User::factory()->create();
        if (method_exists($provider, 'assignRole')) $provider->assignRole('provider');

        $service = Service::factory()->create(['provider_id' => $provider->id, 'duration' => 60]);

        $payload = [
            'service_id' => $service->id,
            'location_id'=> 1,
            'date'       => Carbon::today()->addDay()->toDateString(),
            'start_time' => '10:00:00',
        ];

        $res = $this->actingAs($customer, 'sanctum')->postJson('/api/v1/customer/book', $payload);

        $res->assertCreated()
            ->assertJsonFragment(['status' => 'pending', 'service_id' => $service->id]);

        $this->assertDatabaseHas('bookings', [
            'customer_id'     => $customer->id,
            'provider_id' => $provider->id,
            'service_id'  => $service->id,
            'location_id' => 1,
            'date'        => $payload['date'],
            'start_time'  => '10:00:00',
            'end_time'    => '11:00:00',
            'status'      => 'pending',
        ]);
    }

    public function test_booking_cannot_be_in_the_past(): void
    {
        $customer = User::factory()->create();
        $provider = User::factory()->create();
        $service  = Service::factory()->create(['provider_id' => $provider->id]);

        $payload = [
            'service_id' => $service->id,
            'location_id'=> 1,
            'date'       => Carbon::yesterday()->toDateString(),
            'start_time' => '10:00:00',
        ];

        $res = $this->actingAs($customer, 'sanctum')->postJson('/api/v1/customer/book', $payload);

        $res->assertStatus(422)->assertJsonValidationErrors(['date']);
    }

    public function test_overlapping_booking_is_rejected(): void
    {
        $customer = User::factory()->create();
        $provider = User::factory()->create();
        $service  = Service::factory()->create(['provider_id' => $provider->id, 'duration' => 60]);

        // Existing booking 10:00–11:00
        Booking::factory()->create([
            'provider_id' => $provider->id,
            'service_id'  => $service->id,
            'date'        => Carbon::today()->addDay()->toDateString(),
            'start_time'  => '10:00:00',
            'end_time'    => '11:00:00',
            'status'      => 'confirmed',
        ]);

        $payload = [
            'service_id' => $service->id,
            'location_id'=> 1,
            'date'       => Carbon::today()->addDay()->toDateString(),
            'start_time' => '10:30:00',
        ];

        $res = $this->actingAs($customer, 'sanctum')->postJson('/api/v1/customer/book', $payload);

        $res->assertStatus(422); // your validator key e.g. ->assertJsonValidationErrors(['start_time']);
    }

    public function test_provider_can_confirm_booking_and_event_fired(): void
    {
        Event::fake();

        $provider = User::factory()->create();
        if (method_exists($provider, 'assignRole')) $provider->assignRole('provider');

        $booking = Booking::factory()->create(['status' => 'pending', 'provider_id' => $provider->id]);

        $res = $this->actingAs($provider, 'sanctum')
            ->patchJson("/api/bookings/{$booking->id}/status", ['status' => 'confirmed']);

        $res->assertOk();

        $this->assertDatabaseHas('bookings', ['id' => $booking->id, 'status' => 'confirmed']);

        Event::assertDispatched(BookingStatusChanged::class, function ($e) use ($booking) {
            return $e->booking->id === $booking->id && $e->newStatus === 'confirmed';
        });
    }
}
