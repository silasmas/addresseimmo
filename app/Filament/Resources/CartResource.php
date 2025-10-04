<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CartResource\Pages;
use App\Filament\Resources\CartResource\RelationManagers\{OrdersRelationManager, PaymentsRelationManager};
use App\Models\Cart;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Filters\TernaryFilter;

class CartResource extends Resource
{
    protected static ?string $model = Cart::class;
    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';
    protected static ?string $navigationGroup = 'Sales';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('payment_code')->maxLength(45),
            Forms\Components\Toggle::make('is_paid')->inline(false),
            Forms\Components\Select::make('user_id')->relationship('user','email')->searchable()->preload()->required(),
        ])->columns(2);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('user.email')->label('Customer')->searchable(),
            Tables\Columns\TextColumn::make('payment_code')->label('Pay code')->copyable(),
            Tables\Columns\IconColumn::make('is_paid')->boolean(),
            Tables\Columns\TextColumn::make('computed_total')->label('Computed total')->money('USD', true),
            Tables\Columns\TextColumn::make('created_at')->since(),
        ])
        ->filters([
            TernaryFilter::make('is_paid')->label('Paid'),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class,
            PaymentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCarts::route('/'),
            'create' => Pages\CreateCart::route('/create'),
            'edit' => Pages\EditCart::route('/{record}/edit'),
            // 'view' => Pages\ViewCart::route('/{record}'),
        ];
    }
}
