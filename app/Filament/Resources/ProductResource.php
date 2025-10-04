<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers\{
    OrdersRelationManager, FeedbacksRelationManager, SharedWithRelationManager
};
use App\Models\Product;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Filament\Forms\Components\Section;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationGroup = 'Catalog';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Section::make('Essentiel')->schema([
                Forms\Components\TextInput::make('product_name')->required()->maxLength(255),
                Forms\Components\Textarea::make('product_description')->rows(4),
                Forms\Components\TextInput::make('price')->numeric()->prefix('∑'),
                Forms\Components\TextInput::make('quantity')->numeric()->minValue(0),
                Forms\Components\Toggle::make('is_service')->inline(false),
                Forms\Components\Toggle::make('is_shared')->inline(false),
                Forms\Components\Select::make('action')
                    ->options([
                        'sell'=>'Sell','rent'=>'Rent','build'=>'Build','moving'=>'Moving'
                    ])->required()->native(false),
                Forms\Components\Select::make('type')
                    ->options([
                        'house'=>'House','apartment'=>'Apartment','plot'=>'Plot','equipment'=>'Equipment'
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
                    ->relationship('category','category_name')->searchable()->preload(),
                Forms\Components\Select::make('user_id')
                    ->relationship('owner','email')->searchable()->preload()->required()
                    ->helperText("Propriétaire (users.id)"),
            ])->columns(2),
        ])->columns(1);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id')->sortable(),
            Tables\Columns\TextColumn::make('product_name')->searchable()->limit(40)->weight('bold'),
            Tables\Columns\TextColumn::make('category.category_name')->label('Category')->badge(),
            Tables\Columns\BadgeColumn::make('type')->colors([
                'success' => 'house',
                'warning' => 'apartment',
                'info'    => 'plot',
                'primary' => 'equipment',
            ])->label('Type'),
            Tables\Columns\BadgeColumn::make('action')->colors([
                'success' => 'sell',
                'warning' => 'rent',
                'info'    => 'build',
                'gray'    => 'moving',
            ])->label('Action'),
            Tables\Columns\TextColumn::make('price')->money('USD', true)->sortable(),
            Tables\Columns\IconColumn::make('is_service')->boolean(),
            Tables\Columns\IconColumn::make('is_shared')->boolean(),
            Tables\Columns\TextColumn::make('owner.email')->label('Owner'),
            Tables\Columns\TextColumn::make('created_at')->since(),
        ])
        ->filters([
            SelectFilter::make('category_id')->relationship('category','category_name')->label('Category'),
            SelectFilter::make('type')->options([
                'house'=>'House','apartment'=>'Apartment','plot'=>'Plot','equipment'=>'Equipment'
            ]),
            SelectFilter::make('action')->options([
                'sell'=>'Sell','rent'=>'Rent','build'=>'Build','moving'=>'Moving'
            ]),
            Filter::make('services_only')->label('Services only')->query(fn($q)=>$q->where('is_service',1)),
            Filter::make('shared_only')->label('Shared only')->query(fn($q)=>$q->where('is_shared',1)),
            // Exemple de filtre prix min/max
            Filter::make('price_range')->form([
                Forms\Components\TextInput::make('min')->numeric()->label('Min'),
                Forms\Components\TextInput::make('max')->numeric()->label('Max'),
            ])->query(function ($q, array $data) {
                return $q
                    ->when($data['min'] ?? null, fn($qq,$v)=>$qq->where('price','>=',$v))
                    ->when($data['max'] ?? null, fn($qq,$v)=>$qq->where('price','<=',$v));
            }),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ])
        ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getRelations(): array
    {
        return [
            OrdersRelationManager::class,
            FeedbacksRelationManager::class,
            SharedWithRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
            // 'view'   => Pages\ViewProduct::route('/{record}'),
        ];
    }
}
