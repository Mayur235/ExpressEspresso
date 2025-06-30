<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChartController extends Controller
{
    public function salesChart()
    {
        $now = Carbon::now();
        $oneWeekAgo = $now->copy()->subDays(6);

        // Example query to get sales total grouped by day for the last 7 days
        $salesData = DB::table('transactions')
            ->selectRaw('DAY(created_at) as day, SUM(total) as sales_total')
            ->whereBetween('created_at', [$oneWeekAgo->startOfDay(), $now->endOfDay()])
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        return response()->json([
            'data' => $salesData,
            'one_week_ago' => $oneWeekAgo->toDateString(),
            'now' => $now->toDateString(),
        ]);
    }

    public function profitsChart()
    {
        $now = Carbon::now();
        $sixMonthsAgo = $now->copy()->subMonths(5)->startOfMonth();

        // Example query to get profits grouped by month for the last 6 months
        $profitsData = DB::table('transactions')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m-01") as date, SUM(profit) as profits')
            ->whereBetween('created_at', [$sixMonthsAgo, $now])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'data' => $profitsData,
            'six_month_ago' => $sixMonthsAgo->toDateString(),
            'now' => $now->toDateString(),
        ]);
    }
}
