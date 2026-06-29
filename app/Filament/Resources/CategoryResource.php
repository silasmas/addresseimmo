<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers\ProductsRelationManager;
use App\Models\Category;
use BackedEnum;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use UnitEnum;

/**
 * Resource Filament pour la gestion des catégories.
 */
class CategoryResource extends Resource
{
  protected static ?string $model = Category::class;

  protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

  protected static string|UnitEnum|null $navigationGroup = 'Catalog';

  protected static ?string $navigationLabel = 'Categories';

  /**
   * Définit le formulaire de création/édition.
   *
   * @param Schema $schema Schéma Filament
   * @return Schema
   */
  public static function form(Schema $schema): Schema
  {
    return $schema->components([
      Forms\Components\TextInput::make('category_name')
        ->label('Name')->required()->maxLength(255),
      Forms\Components\Textarea::make('category_description')->label('Description'),
      Forms\Components\Toggle::make('for_service')->label('For services?')->inline(false),
      Forms\Components\TextInput::make('icon')->placeholder('e.g. heroicon-o-home'),
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
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('id')->sortable(),
        Tables\Columns\TextColumn::make('category_name')->searchable()->sortable(),
        Tables\Columns\IconColumn::make('for_service')->boolean(),
        Tables\Columns\TextColumn::make('products_count')
          ->counts('products')
          ->label('Products')
          ->sortable(),
        Tables\Columns\TextColumn::make('created_at')->dateTime()->since(),
      ])
      ->filters([
        Filter::make('services_only')->label('Services only')
          ->query(fn ($q) => $q->where('for_service', 1)),
      ])
      ->recordActions([EditAction::make()])
      ->toolbarActions([DeleteBulkAction::make()]);
  }

  /**
   * Retourne les relation managers associés.
   *
   * @return array<int, class-string>
   */
  public static function getRelations(): array
  {
    return [ProductsRelationManager::class];
  }

  /**
   * Retourne les pages du resource.
   *
   * @return array<string, class-string>
   */
  public static function getPages(): array
  {
    return [
      'index' => Pages\ListCategories::route('/'),
      'create' => Pages\CreateCategory::route('/create'),
      'edit' => Pages\EditCategory::route('/{record}/edit'),
    ];
  }
}
