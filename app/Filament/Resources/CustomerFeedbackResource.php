<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerFeedbackResource\Pages;
use App\Models\CustomerFeedback;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class CustomerFeedbackResource extends Resource
{
    protected static ?string $model = CustomerFeedback::class;
    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?string $navigationGroup = 'Sales';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\Select::make('user_id')
                ->relationship('author','email')->searchable()->preload()->label('Author')->required(),
            Forms\Components\Select::make('for_user_id')
                ->relationship('targetUser','email')->searchable()->preload()->label('For user'),
            Forms\Components\Select::make('for_product_id')
                ->relationship('product','product_name')->searchable()->preload()->label('Product'),
            Forms\Components\TextInput::make('rating')->numeric()->minValue(0)->maxValue(5),
            Forms\Components\Textarea::make('comment')->rows(5),
        ])->columns(2);
    }

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
          ->actions([Tables\Actions\EditAction::make(), Tables\Actions\DeleteAction::make()])
          ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomerFeedback::route('/'),
            'create' => Pages\CreateCustomerFeedback::route('/create'),
            'edit' => Pages\EditCustomerFeedback::route('/{record}/edit'),
        ];
    }
}
