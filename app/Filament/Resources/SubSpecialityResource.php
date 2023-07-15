<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use App\Models\SubSpeciality;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SubSpecialityResource\Pages;
use App\Filament\Resources\SubSpecialityResource\RelationManagers;

class SubSpecialityResource extends Resource
{
    protected static ?string $model = SubSpeciality::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Doctors';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('speciality_id')
                    ->relationship('speciality', 'name')
                    ->required(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->reactive()
                    ->afterStateUpdated(fn($state, callable $set)=> $set('slug',Str::slug($state))),
                Forms\Components\TextInput::make('name_ar')
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('icon'),
                    
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('speciality.name')->label('Speciality'),
                Tables\Columns\TextColumn::make('name')->label('Name'),
                Tables\Columns\TextColumn::make('name_ar')->label('Name in Arabic'),
                Tables\Columns\TextColumn::make('slug'),
                Tables\Columns\ImageColumn::make('icon'),
                // Tables\Columns\TextColumn::make('created_at')
                //     ->dateTime(),
                // Tables\Columns\TextColumn::make('updated_at')
                //     ->dateTime(),
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
            'index' => Pages\ManageSubSpecialities::route('/'),
        ];
    }    
}
