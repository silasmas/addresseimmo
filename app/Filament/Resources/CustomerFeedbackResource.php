<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerFeedbackResource\Pages;
use App\Models\CustomerFeedback;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use UnitEnum;

/**
 * Resource Filament pour la gestion des avis clients.
 */
class CustomerFeedbackResource extends Resource
{
  protected static ?string $model = CustomerFeedback::class;

  protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';

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
      Forms\Components\Select::make('user_id')
        ->relationship('author', 'email')->searchable()->preload()->label('Author')->required(),
      Forms\Components\Select::make('for_user_id')
        ->relationship('targetUser', 'email')->searchable()->preload()->label('For user'),
      Forms\Components\Select::make('for_product_id')
        ->relationship('product', 'product_name')->searchable()->preload()->label('Product'),
      Forms\Components\TextInput::make('rating')->numeric()->minValue(0)->maxValue(5),
      Forms\Components\Textarea::make('comment')->rows(5),
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
      Tables\Columns\TextColumn::make('author.email')->label('Author'),
      Tables\Columns\TextColumn::make('product.product_name')->label('Product')->toggleable(),
      Tables\Columns\TextColumn::make('targetUser.email')->label('For user')->toggleable(),
      Tables\Columns\TextColumn::make('rating')->badge(),
      Tables\Columns\TextColumn::make('comment')->limit(60),
      Tables\Columns\TextColumn::make('created_at')->since(),
    ])->filters([])
      ->recordActions([EditAction::make(), DeleteAction::make()])
      ->toolbarActions([DeleteBulkAction::make()]);
  }

  /**
   * Retourne les pages du resource.
   *
   * @return array<string, class-string>
   */
  public static function getPages(): array
  {
    return [
      'index' => Pages\ListCustomerFeedback::route('/'),
      'create' => Pages\CreateCustomerFeedback::route('/create'),
      'edit' => Pages\EditCustomerFeedback::route('/{record}/edit'),
    ];
  }
}
