<?php

namespace App\Filament\Resources\CartResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;

/**
 * Gère les paiements associés à un panier.
 */
class PaymentsRelationManager extends RelationManager
{
  protected static string $relationship = 'payments';

  /**
   * Définit le formulaire de création/édition.
   *
   * @param Schema $schema Schéma Filament
   * @return Schema
   */
  public function form(Schema $schema): Schema
  {
    return $schema->components([
      Forms\Components\TextInput::make('reference')->maxLength(45),
      Forms\Components\TextInput::make('provider_reference')->maxLength(45),
      Forms\Components\TextInput::make('order_number'),
      Forms\Components\TextInput::make('amount')->numeric()->prefix('$'),
      Forms\Components\TextInput::make('amount_customer')->numeric()->prefix('$'),
      Forms\Components\TextInput::make('phone')->maxLength(45),
      Forms\Components\TextInput::make('currency')->maxLength(45)->default('USD'),
      Forms\Components\TextInput::make('channel')->maxLength(45)->placeholder('e.g. mpesa, card'),
      Forms\Components\TextInput::make('type')->numeric()->helperText('Libre: 0/1/2...'),
      Forms\Components\TextInput::make('status')->numeric()->helperText('Libre: 0=init,1=ok,2=fail...'),
    ])->columns(2);
  }

  /**
   * Définit la table des paiements.
   *
   * @param Tables\Table $table Table Filament
   * @return Tables\Table
   */
  public function table(Tables\Table $table): Tables\Table
  {
    return $table->columns([
      Tables\Columns\TextColumn::make('reference')->copyable(),
      Tables\Columns\TextColumn::make('provider_reference')->label('Provider ref'),
      Tables\Columns\TextColumn::make('amount')->money('USD', true),
      Tables\Columns\TextColumn::make('channel'),
      Tables\Columns\TextColumn::make('status'),
      Tables\Columns\TextColumn::make('created_at')->since(),
    ])->headerActions([CreateAction::make()])
      ->recordActions([EditAction::make(), DeleteAction::make()]);
  }
}
