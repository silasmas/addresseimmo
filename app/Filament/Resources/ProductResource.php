<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers\{
  FeedbacksRelationManager, OrdersRelationManager, SharedWithRelationManager
};
use App\Models\Product;
use BackedEnum;
use Illuminate\Database\Eloquent\Builder;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use UnitEnum;

/**
 * Resource Filament pour la gestion des annonces immobilières.
 */
class ProductResource extends Resource
{
  protected static ?string $model = Product::class;

  protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-building-office';

  protected static string|UnitEnum|null $navigationGroup = 'Catalog';

  /**
   * Définit le formulaire de création/édition.
   *
   * @param Schema $schema Schéma Filament
   * @return Schema
   */
  public static function form(Schema $schema): Schema
  {
    return $schema->components([
      Section::make('Essentiel')->schema([
        Forms\Components\TextInput::make('product_name')->required()->maxLength(255),
        Forms\Components\Textarea::make('product_description')->rows(4),
        Forms\Components\TextInput::make('price')->numeric()->prefix('$'),
        Forms\Components\TextInput::make('quantity')->numeric()->minValue(0),
        Forms\Components\Toggle::make('is_service')->inline(false),
        Forms\Components\Toggle::make('is_shared')->inline(false),
        Forms\Components\Select::make('action')
          ->options([
            'sell' => 'Sell',
            'rent' => 'Rent',
            'build' => 'Build',
            'design' => 'Design',
            'moving' => 'Moving',
          ])->required()->native(false),
        Forms\Components\Select::make('type')
          ->options([
            'equipped_house' => 'Equipped house',
            'empty_house' => 'Empty house',
            'unfinished_house' => 'Unfinished house',
            'equipped_apartment' => 'Equipped apartment',
            'empty_apartment' => 'Empty apartment',
            'empty_plot' => 'Empty plot',
            'house_plot' => 'House plot',
          ])->native(false),
      ])->columns(2),

      Section::make('Localisation')->schema([
        Forms\Components\TextInput::make('country'),
        Forms\Components\TextInput::make('city'),
        Forms\Components\TextInput::make('municipality'),
        Forms\Components\TextInput::make('neighborhood'),
        Forms\Components\TextInput::make('street'),
        Forms\Components\Textarea::make('address')->columnSpanFull(),
      ])->columns(3)->collapsible(),

      Section::make('Liens')->schema([
        Forms\Components\Select::make('category_id')
          ->relationship('category', 'category_name')->searchable()->preload(),
        Forms\Components\Select::make('user_id')
          ->relationship('owner', 'email')->searchable()->preload()->required()
          ->helperText('Propriétaire (users.id)'),
      ])->columns(2),
    ])->columns(1);
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
      Tables\Columns\TextColumn::make('product_name')->searchable()->limit(40)->weight('bold'),
      Tables\Columns\TextColumn::make('category.category_name')->label('Category')->badge(),
      Tables\Columns\TextColumn::make('type')->badge()->label('Type'),
      Tables\Columns\TextColumn::make('action')->badge()->label('Action'),
      Tables\Columns\TextColumn::make('price')->money('USD', true)->sortable(),
      Tables\Columns\IconColumn::make('is_service')->boolean(),
      Tables\Columns\IconColumn::make('is_shared')->boolean(),
      Tables\Columns\TextColumn::make('owner.email')->label('Owner'),
      Tables\Columns\TextColumn::make('created_at')->since(),
    ])
      ->filters([
        SelectFilter::make('category_id')->relationship('category', 'category_name')->label('Category'),
        SelectFilter::make('type')->options([
          'equipped_house' => 'Equipped house',
          'empty_house' => 'Empty house',
          'unfinished_house' => 'Unfinished house',
          'equipped_apartment' => 'Equipped apartment',
          'empty_apartment' => 'Empty apartment',
          'empty_plot' => 'Empty plot',
          'house_plot' => 'House plot',
        ]),
        SelectFilter::make('action')->options([
          'sell' => 'Sell',
          'rent' => 'Rent',
          'build' => 'Build',
          'design' => 'Design',
          'moving' => 'Moving',
        ]),
        Filter::make('services_only')->label('Services only')
          ->query(fn (Builder $query): Builder => $query->where('is_service', 1)),
        Filter::make('shared_only')->label('Shared only')
          ->query(fn (Builder $query): Builder => $query->where('is_shared', 1)),
        Filter::make('price_range')->schema([
          Forms\Components\TextInput::make('min')->numeric()->label('Min'),
          Forms\Components\TextInput::make('max')->numeric()->label('Max'),
        ])->query(function (Builder $query, array $data): Builder {
          return $query
            ->when(
              $data['min'] ?? null,
              fn (Builder $query, $min): Builder => $query->where('price', '>=', $min),
            )
            ->when(
              $data['max'] ?? null,
              fn (Builder $query, $max): Builder => $query->where('price', '<=', $max),
            );
        }),
      ])
      ->recordActions([
        ViewAction::make(),
        EditAction::make(),
        DeleteAction::make(),
      ])
      ->toolbarActions([DeleteBulkAction::make()]);
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
      FeedbacksRelationManager::class,
      SharedWithRelationManager::class,
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
      'index' => Pages\ListProducts::route('/'),
      'create' => Pages\CreateProduct::route('/create'),
      'edit' => Pages\EditProduct::route('/{record}/edit'),
    ];
  }
}
