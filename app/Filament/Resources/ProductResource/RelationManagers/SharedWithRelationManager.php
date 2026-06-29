<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Actions\AttachAction;
use Filament\Actions\DetachAction;
use Filament\Forms;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Tables;

/**
 * Gère les utilisateurs avec lesquels une annonce est partagée.
 */
class SharedWithRelationManager extends RelationManager
{
  protected static string $relationship = 'sharedWith';

  /**
   * Définit le formulaire d'attachement.
   *
   * @param Schema $schema Schéma Filament
   * @return Schema
   */
  public function form(Schema $schema): Schema
  {
    return $schema->components([
      Forms\Components\Select::make('id')
        ->relationship('sharedWith', 'email')
        ->searchable()->preload()->required(),
    ]);
  }

  /**
   * Définit la table des utilisateurs partagés.
   *
   * @param Tables\Table $table Table Filament
   * @return Tables\Table
   */
  public function table(Tables\Table $table): Tables\Table
  {
    return $table->columns([
      Tables\Columns\TextColumn::make('email')->label('User'),
      Tables\Columns\TextColumn::make('pivot.created_at')->label('Since')->since(),
    ])->headerActions([
      AttachAction::make(),
    ])->recordActions([
      DetachAction::make(),
    ]);
  }
}
