<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScaleResource\Pages;
use App\Filament\Resources\ScaleResource\RelationManagers;
use App\Models\Scale;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScaleResource extends Resource
{
    protected static ?string $navigationGroup = 'Scales';

    protected static ?string $model = Scale::class;
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $navigationIcon = 'icons.scale';
    public static function  canCreate():bool
    {
        return false;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('title_ar')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('short_description')
                    ->required()
                    ->maxLength(16777215),
                Forms\Components\Textarea::make('short_description_ar')
                    ->required()
                    ->maxLength(16777215),
                Forms\Components\Toggle::make('show_in_app')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('title_ar')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('short_description'),
                Tables\Columns\TextColumn::make('short_description_ar'),
                Tables\Columns\ToggleColumn::make('show_in_app'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListScales::route('/'),
            'create' => Pages\CreateScale::route('/create'),
            'edit' => Pages\EditScale::route('/{record}/edit'),
        ];
    }    
}
