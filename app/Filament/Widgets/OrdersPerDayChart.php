<?php

namespace App\Filament\Widgets;

use App\Models\CustomerOrder;
use Filament\Widgets\LineChartWidget;
use Illuminate\Support\Facades\DB;

class OrdersPerDayChart extends LineChartWidget
{
    protected ?string $heading = 'Orders per day (30d)';

    protected function getData(): array
    {
        $rows = CustomerOrder::select(DB::raw('DATE(created_at) as d'), DB::raw('COUNT(*) as c'))
            ->where('created_at','>=',now()->subDays(30))
            ->groupBy('d')->orderBy('d')->get();

        return [
            'datasets' => [[
                'label' => 'Orders',
                'data' => $rows->pluck('c')->all(),
            ]],
            'labels'   => $rows->pluck('d')->map(fn($d)=>\Carbon\Carbon::parse($d)->format('m-d'))->all(),
        ];
    }
}
