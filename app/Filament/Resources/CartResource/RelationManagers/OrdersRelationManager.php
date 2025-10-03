<?php

namespace App\Filament\Resources\CartResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms;
use Filament\Tables;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders'; // Cart->orders()

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('product_id')
                ->relationship('product','product_name')->searchable()->preload()->required(),
            Forms\Components\TextInput::make('quantity')->numeric()->minValue(1)->required(),
            Forms\Components\TextInput::make('price_at_that_time')->numeric()->prefix('∑'),
            Forms\Components\TextInput::make('currency')->default('USD')->maxLength(10),
        ])->columns(2);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('product.product_name')->label('Product'),
            Tables\Columns\TextColumn::make('quantity'),
            Tables\Columns\TextColumn::make('price_at_that_time')->money('USD', true),
            Tables\Columns\TextColumn::make('currency'),
            Tables\Columns\TextColumn::make('created_at')->since(),
        ])->headerActions([Tables\Actions\CreateAction::make()])
          ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
          ->paginated([10,25,50]);
    }
}
