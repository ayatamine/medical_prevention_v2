<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Models\Recommendation;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RecommendationResource\Pages;
use App\Filament\Resources\RecommendationResource\RelationManagers;

class RecommendationResource extends Resource
{
    protected static ?string $model = Recommendation::class;

    protected static ?string $navigationIcon = 'icons.recommended';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->unique(table: Recommendation::class,ignoreRecord:true)
                    ->maxLength(255),
                Forms\Components\TextInput::make('title_ar')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('content')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\Textarea::make('content_ar')
                    ->required()
                    ->maxLength(65535),
                Forms\Components\TextInput::make('duration')
                    ->required(),
                Forms\Components\DatePicker::make('publish_date')
                    ->required(),
                Forms\Components\TextInput::make('sex')
                    ->maxLength(255),
                Forms\Components\TextInput::make('min_age'),
                Forms\Components\TextInput::make('max_age'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('title_ar')->sortable()->searchable(),
                
                Tables\Columns\TextColumn::make('duration')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('publish_date')->sortable()
                    ->date(),
                Tables\Columns\TextColumn::make('sex')->label('Gender'),
                Tables\Columns\TextColumn::make('min_age'),
                Tables\Columns\TextColumn::make('max_age'),
                Tables\Columns\TextColumn::make('status')
                ->label('status')->sortable()->searchable(),
            ])
            ->filters([
                Filter::make('publishable')
                        ->query(fn (Builder $query): Builder => $query->publishable()),
                Filter::make('Finished')
                        ->query(fn (Builder $query): Builder => $query->finished()),
                Filter::make('not published yet')
                        ->query(fn (Builder $query): Builder => $query->unpublished())
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
            'index' => Pages\ListRecommendations::route('/'),
            'create' => Pages\CreateRecommendation::route('/create'),
            'edit' => Pages\EditRecommendation::route('/{record}/edit'),
        ];
    }    
}
