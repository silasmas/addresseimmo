<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;

class Dashboard extends BaseDashboard
{
   public function getWidgets(): array
{
    return [
        \App\Filament\Widgets\SalesStatsOverview::class,
        \App\Filament\Widgets\OrdersPerDayChart::class,
        \App\Filament\Widgets\RevenueByChannelChart::class,
        \App\Filament\Widgets\TopProductsTable::class,
    ];
}

}
