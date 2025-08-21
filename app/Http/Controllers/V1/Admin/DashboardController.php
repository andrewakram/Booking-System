<?php

namespace App\Http\Controllers\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function statitics(Request $request)
    {
        $query = Booking::query();

        if ($request->filled('provider_id')) {
            $query->where('provider_id', $request->provider_id);
        }

        if ($request->filled('service_id')) {
            $query->where('service_id', $request->service_id);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->whereBetween('date', [$request->date_from, $request->date_to]);
        }

        $filtered = fn() => (clone $query);

        // 1- Total bookings per provider
        $bookingsPerProvider = $filtered()
            ->select('provider_id')
            ->selectRaw('COUNT(*) as total_bookings')
            ->groupBy('provider_id')
            ->with('provider:id,name')
            ->get();

        // 2- Cancelled vs Confirmed rate per service
        $cancelledVsConfirmed = $filtered()
            ->select('service_id')
            ->selectRaw("
                SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed_count,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_count
            ")
            ->groupBy('service_id')
            ->with('service:id,name')
            ->get();

        // 3- Peak hours by day
        $peakHoursDaily = $filtered()
            ->selectRaw('HOUR(start_time) as hour, COUNT(*) as total')
            ->groupBy('hour')
            ->orderByDesc('total')
            ->get();

        // 3- Peak hours by week (day of week)
        $peakHoursWeekly = $filtered()
            ->selectRaw('DAYOFWEEK(date) as day, COUNT(*) as total')
            ->groupBy('day')
            ->orderByDesc('total')
            ->get();

        // 4- Average booking duration per customer
        $avgDurationPerCustomer = $filtered()
            ->select('customer_id')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, start_time, end_time)) as avg_duration')
            ->groupBy('customer_id')
            ->with('customer:id,name,email')
            ->get();

        return response()->json([
            'bookings_per_provider'   => $bookingsPerProvider,
            'cancelled_vs_confirmed'  => $cancelledVsConfirmed,
            'peak_hours_daily'        => $peakHoursDaily,
            'peak_hours_weekly'       => $peakHoursWeekly,
            'avg_duration_per_customer' => $avgDurationPerCustomer,
        ]);
    }
}
