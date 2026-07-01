<?php

namespace App\Filament\Widgets;

use App\Models\CustomerOrder;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Facades\DB;

class TopProductsTable extends BaseWidget
{
    protected static ?string $heading = 'Top products (last 30 days)';

    public function table(Tables\Table $table): Tables\Table
    {
        $query = CustomerOrder::query()
            ->select([
                'product_id',
                DB::raw('SUM(quantity) as total_qty'),
                DB::raw('COUNT(*) as lines_count'),
            ])
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('product_id')
            ->orderByDesc('total_qty');

        return $table
            ->query($query)
            ->columns([
                Tables\Columns\TextColumn::make('product.product_name')->label('Product')->limit(40),
                Tables\Columns\TextColumn::make('total_qty')->label('Qty'),
                Tables\Columns\TextColumn::make('lines_count')->label('Lines'),
            ])->paginated(false);
    }
}
