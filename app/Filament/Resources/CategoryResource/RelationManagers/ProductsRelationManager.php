<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\RelationManagers\RelationManager;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products'; // -> Category->products()

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('product_name')->required(),
            Forms\Components\Textarea::make('product_description'),
            Forms\Components\TextInput::make('price')->numeric()->prefix('∑'),
            Forms\Components\Toggle::make('is_service'),
            Forms\Components\Select::make('action')
                ->options([
                    'sell'=>'Sell','rent'=>'Rent','build'=>'Build','moving'=>'Moving'
                ])->required(),
            Forms\Components\Select::make('type')
                ->options([
                    'house'=>'House','apartment'=>'Apartment','plot'=>'Plot','equipment'=>'Equipment'
                ]),
            Forms\Components\Select::make('user_id')
                ->relationship('owner','email')->searchable()->preload()->required(),
        ])->columns(2);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('product_name')->searchable()->limit(40),
            Tables\Columns\TextColumn::make('price')->money('USD', true),
            Tables\Columns\IconColumn::make('is_service')->boolean(),
            Tables\Columns\BadgeColumn::make('action')->colors([
                'primary','warning' => 'rent','success' => 'sell','info' => 'build','gray' => 'moving'
            ]),
            Tables\Columns\TextColumn::make('owner.email')->label('Owner'),
        ])->headerActions([Tables\Actions\CreateAction::make()])
          ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }
}
