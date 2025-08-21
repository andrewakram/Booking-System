<?php
// app/Services/AvailabilityService.php
namespace App\Services;

use Carbon\Carbon;

class AvailabilityService
{
    public function generateSlots(string $date, string $from, string $to, int $durationMinutes): array
    {
        $start = Carbon::createFromFormat('Y-m-d H:i:s', "$date $from");
        $end   = Carbon::createFromFormat('Y-m-d H:i:s', "$date $to");

        $out = [];
        while ($start->copy()->addMinutes($durationMinutes)->lte($end)) {
            $out[] = $start->format('H:i:s');
            $start->addMinutes($durationMinutes);
        }
        return $out;
    }
}
