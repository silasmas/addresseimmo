<?php

namespace App\Filament\Resources\CartResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms;
use Filament\Tables;

class PaymentsRelationManager extends RelationManager
{
    protected static string $relationship = 'payments'; // Cart->payments()

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('reference')->maxLength(45),
            Forms\Components\TextInput::make('provider_reference')->maxLength(45),
            Forms\Components\TextInput::make('order_number'),
            Forms\Components\TextInput::make('amount')->numeric()->prefix('∑'),
            Forms\Components\TextInput::make('amount_customer')->numeric()->prefix('∑'),
            Forms\Components\TextInput::make('phone')->maxLength(45),
            Forms\Components\TextInput::make('currency')->maxLength(45)->default('USD'),
            Forms\Components\TextInput::make('channel')->maxLength(45)->placeholder('e.g. mpesa, card'),
            Forms\Components\TextInput::make('type')->numeric()->helperText('Libre: 0/1/2...'),
            Forms\Components\TextInput::make('status')->numeric()->helperText('Libre: 0=init,1=ok,2=fail...'),
        ])->columns(2);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('reference')->copyable(),
            Tables\Columns\TextColumn::make('provider_reference')->label('Provider ref'),
            Tables\Columns\TextColumn::make('amount')->money('USD', true),
            Tables\Columns\TextColumn::make('channel'),
            Tables\Columns\TextColumn::make('status'),
            Tables\Columns\TextColumn::make('created_at')->since(),
        ])->headerActions([Tables\Actions\CreateAction::make()])
          ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()]);
    }
}
