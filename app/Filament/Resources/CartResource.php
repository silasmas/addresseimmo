<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CartResource\Pages;
use App\Filament\Resources\CartResource\RelationManagers\{OrdersRelationManager, PaymentsRelationManager};
use App\Models\Cart;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\TernaryFilter;
use UnitEnum;

/**
 * Resource Filament pour la gestion des paniers.
 */
class CartResource extends Resource
{
  protected static ?string $model = Cart::class;

  protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-shopping-cart';

  protected static string|UnitEnum|null $navigationGroup = 'Sales';

  /**
   * Définit le formulaire de création/édition.
   *
   * @param Schema $schema Schéma Filament
   * @return Schema
   */
  public static function form(Schema $schema): Schema
  {
    return $schema->components([
      Forms\Components\TextInput::make('payment_code')->maxLength(45),
      Forms\Components\Toggle::make('is_paid')->inline(false),
      Forms\Components\Select::make('user_id')->relationship('user', 'email')->searchable()->preload()->required(),
    ])->columns(2);
  }

  /**
   * Définit la table de listing.
   *
   * @param Tables\Table $table Table Filament
   * @return Tables\Table
   */
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
      ->recordActions([
        ViewAction::make(),
        EditAction::make(),
      ]);
  }

  /**
   * Retourne les relation managers associés.
   *
   * @return array<int, class-string>
   */
  public static function getRelations(): array
  {
    return [
      OrdersRelationManager::class,
      PaymentsRelationManager::class,
    ];
  }

  /**
   * Retourne les pages du resource.
   *
   * @return array<string, class-string>
   */
  public static function getPages(): array
  {
    return [
      'index' => Pages\ListCarts::route('/'),
      'create' => Pages\CreateCart::route('/create'),
      'edit' => Pages\EditCart::route('/{record}/edit'),
    ];
  }
}
