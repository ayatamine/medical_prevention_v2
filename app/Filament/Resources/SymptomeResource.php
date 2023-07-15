<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SymptomeResource\Pages;
use App\Filament\Resources\SymptomeResource\RelationManagers;
use App\Models\Symptome;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SymptomeResource extends Resource
{
    protected static ?string $model = Symptome::class;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationIcon = 'icons.allergy';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->label(trans('name'))
                ->unique(table: Symptome::class,ignoreRecord:true)
                ->maxLength(150),
            Forms\Components\TextInput::make('name_ar')
                ->unique(table: Symptome::class,ignoreRecord:true)
                ->maxLength(150),
            Forms\Components\FileUpload::make('icon'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name_ar')->sortable()->searchable(),
                Tables\Columns\ImageColumn::make('icon'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSymptomes::route('/'),
        ];
    }    
}
