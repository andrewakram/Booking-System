<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Services\AvailabilityService;

class AvailabilityServiceTest extends TestCase
{
    public function test_generate_slots_respects_duration_and_boundaries(): void
    {
        $svc = new AvailabilityService();

        $slots = $svc->generateSlots('2025-08-20', '09:00:00', '12:00:00', 60);
        $this->assertSame(['09:00:00','10:00:00','11:00:00'], $slots);

        $slots30 = $svc->generateSlots('2025-08-20', '09:00:00', '10:30:00', 30);
        $this->assertSame(['09:00:00','09:30:00','10:00:00'], $slots30);
    }
}
