<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;

class FeedbacksRelationManager extends RelationManager
{
    protected static string $relationship = 'feedbacks'; // Product->feedbacks()

    public function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id'),
            Tables\Columns\IconColumn::make('rating')->boolean() // tu peux remplacer par TextColumn si tu veux 1-5
                ->label('Has rating')->tooltip(fn($record)=>'Note: '.$record->rating),
            Tables\Columns\TextColumn::make('author.email')->label('Author'),
            Tables\Columns\TextColumn::make('comment')->limit(60),
            Tables\Columns\TextColumn::make('created_at')->since(),
        ]);
    }
}
