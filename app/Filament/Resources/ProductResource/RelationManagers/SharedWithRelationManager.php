<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Forms;
use Filament\Tables;

class SharedWithRelationManager extends RelationManager
{
    protected static string $relationship = 'sharedWith'; // Product->sharedWith()

    public function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            // RelationManager pour BelongsToMany n'affiche ici que l'user à ajouter/supprimer
            Forms\Components\Select::make('id')
                ->relationship('sharedWith','email') // 'id' côté related (users)
                ->searchable()->preload()->required(),
        ]);
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('email')->label('User'),
            Tables\Columns\TextColumn::make('pivot.created_at')->label('Since')->since(),
        ])->headerActions([
            Tables\Actions\AttachAction::make(), // pour attacher un user
        ])->actions([
            Tables\Actions\DetachAction::make(),
        ]);
    }
}
