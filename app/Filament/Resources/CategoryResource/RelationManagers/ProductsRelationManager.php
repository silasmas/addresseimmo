<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;

/**
 * Gère les produits liés à une catégorie.
 */
class ProductsRelationManager extends RelationManager
{
  protected static string $relationship = 'products';

  /**
   * Définit le formulaire de création/édition.
   *
   * @param Schema $schema Schéma Filament
   * @return Schema
   */
  public function form(Schema $schema): Schema
  {
    return $schema->components([
      Forms\Components\TextInput::make('product_name')->required(),
      Forms\Components\Textarea::make('product_description'),
      Forms\Components\TextInput::make('price')->numeric()->prefix('$'),
      Forms\Components\Toggle::make('is_service'),
      Forms\Components\Select::make('action')
        ->options([
          'sell' => 'Sell',
          'rent' => 'Rent',
          'build' => 'Build',
          'design' => 'Design',
          'moving' => 'Moving',
        ])->required(),
      Forms\Components\Select::make('type')
        ->options([
          'equipped_house' => 'Equipped house',
          'empty_house' => 'Empty house',
          'unfinished_house' => 'Unfinished house',
          'equipped_apartment' => 'Equipped apartment',
          'empty_apartment' => 'Empty apartment',
          'empty_plot' => 'Empty plot',
          'house_plot' => 'House plot',
        ]),
      Forms\Components\Select::make('user_id')
        ->relationship('owner', 'email')->searchable()->preload()->required(),
    ])->columns(2);
  }

  /**
   * Définit la table des produits.
   *
   * @param Tables\Table $table Table Filament
   * @return Tables\Table
   */
  public function table(Tables\Table $table): Tables\Table
  {
    return $table->columns([
      Tables\Columns\TextColumn::make('product_name')->searchable()->limit(40),
      Tables\Columns\TextColumn::make('price')->money('USD', true),
      Tables\Columns\IconColumn::make('is_service')->boolean(),
      Tables\Columns\TextColumn::make('action')->badge(),
      Tables\Columns\TextColumn::make('owner.email')->label('Owner'),
    ])->headerActions([CreateAction::make()])
      ->recordActions([EditAction::make(), DeleteAction::make()]);
  }
}
