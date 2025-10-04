<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers\ProductsRelationManager;
use App\Models\Category;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;
    protected static ?string $navigationIcon = 'heroicon-o-tag';
    protected static ?string $navigationGroup = 'Catalog';
    protected static ?string $navigationLabel = 'Categories';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('category_name')
                ->label('Name')->required()->maxLength(255),
            Forms\Components\Textarea::make('category_description')->label('Description'),
            Forms\Components\Toggle::make('for_service')->label('For services?')->inline(false),
            Forms\Components\TextInput::make('icon')->placeholder('e.g. heroicon-o-home'),
        ])->columns(2);
    }

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
            ->actions([Tables\Actions\EditAction::make()])
            ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getRelations(): array
    {
        return [ProductsRelationManager::class];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
