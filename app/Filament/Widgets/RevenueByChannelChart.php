<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\BarChartWidget;

class RevenueByChannelChart extends BarChartWidget
{
    protected static ?string $heading = 'Revenue by channel (paid)';

    protected function getData(): array
    {
        $rows = Payment::query()
            ->selectRaw('channel, SUM(amount) as s')
            ->where('status', 1)
            ->groupBy('channel')
            ->orderByDesc('s')
            ->get();

        return [
            'datasets' => [[
                'label' => 'Revenue',
                'data'  => $rows->pluck('s')->map(fn($v)=>(float)$v)->all(),
            ]],
            'labels' => $rows->pluck('channel')->map(fn($c)=>$c ?: 'unknown')->all(),
        ];
    }
}
