<?php

namespace App\Filament\Widgets;

use App\Models\{Cart, Payment, Product};
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SalesStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $productsCount = Product::count();
        $paidCarts = Cart::where('is_paid', 1)->count();
        $revenue = (float) Payment::where('status', 1)->sum('amount'); // status=1 => payé (à adapter)

        return [
            Stat::make('Products', number_format($productsCount))
                ->description('Total actifs en catalogue')
                ->icon('heroicon-o-building-office'),
            Stat::make('Paid Carts', number_format($paidCarts))
                ->description('Paniers payés')
                ->icon('heroicon-o-shopping-cart'),
            Stat::make('Revenue', '$'.number_format($revenue, 2))
                ->description('Somme des paiements (status=1)')
                ->icon('heroicon-o-banknotes'),
        ];
    }
}
