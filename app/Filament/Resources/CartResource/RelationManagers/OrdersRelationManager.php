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
 * Gère les lignes de commande associées à un panier.
 */
class OrdersRelationManager extends RelationManager
{
  protected static string $relationship = 'orders';

  /**
   * Définit le formulaire de création/édition.
   *
   * @param Schema $schema Schéma Filament
   * @return Schema
   */
  public function form(Schema $schema): Schema
  {
    return $schema->components([
      Forms\Components\Select::make('product_id')
        ->relationship('product', 'product_name')->searchable()->preload()->required(),
      Forms\Components\TextInput::make('quantity')->numeric()->minValue(1)->required(),
      Forms\Components\TextInput::make('price_at_that_time')->numeric()->prefix('$'),
      Forms\Components\TextInput::make('currency')->default('USD')->maxLength(10),
    ])->columns(2);
  }

  /**
   * Définit la table des commandes.
   *
   * @param Tables\Table $table Table Filament
   * @return Tables\Table
   */
  public function table(Tables\Table $table): Tables\Table
  {
    return $table->columns([
      Tables\Columns\TextColumn::make('product.product_name')->label('Product'),
      Tables\Columns\TextColumn::make('quantity'),
      Tables\Columns\TextColumn::make('price_at_that_time')->money('USD', true),
      Tables\Columns\TextColumn::make('currency'),
      Tables\Columns\TextColumn::make('created_at')->since(),
    ])->headerActions([CreateAction::make()])
      ->recordActions([EditAction::make(), DeleteAction::make()])
      ->paginated([10, 25, 50]);
  }
}
