<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use App\Models\ScaleQuestion;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ScaleQuestionResource\Pages;
use App\Filament\Resources\ScaleQuestionResource\RelationManagers;

class ScaleQuestionResource extends Resource
{
    protected static ?string $navigationGroup = 'Scales';

    protected static ?string $model = ScaleQuestion::class;

    protected static ?string $navigationIcon = 'icons.scale_question';
    public static function  canCreate():bool
    {
        return false;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('scale_id')
                    ->relationship('scale','title')
                    ->required(),
                Forms\Components\TextInput::make('question')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('question_ar')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('scale.title'),
                Tables\Columns\TextColumn::make('question'),
                Tables\Columns\TextColumn::make('question_ar'),
            ])
            ->filters([
                SelectFilter::make('account_status')->label('Scale')
                            ->relationship('scale', 'title')
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
            'index' => Pages\ListScaleQuestions::route('/'),
            'create' => Pages\CreateScaleQuestion::route('/create'),
            'edit' => Pages\EditScaleQuestion::route('/{record}/edit'),
        ];
    }    
}
