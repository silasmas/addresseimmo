<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Resources\RelationManagers\RelationManager;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders'; // Product->orders()

    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            TextColumn::make('id'),
            TextColumn::make('cart.id')->label('Cart #')->sortable(),
            TextColumn::make('quantity'),
            TextColumn::make('price_at_that_time')->money('USD', true),
            TextColumn::make('currency')->label('Cur'),
            TextColumn::make('created_at')->since(),
        ])->paginated([10,25,50]);
    }
}
