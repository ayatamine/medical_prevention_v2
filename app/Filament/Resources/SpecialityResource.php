<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Speciality;
use Illuminate\Support\Str;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SpecialityResource\Pages;
use App\Filament\Resources\SpecialityResource\RelationManagers;

class SpecialityResource extends Resource
{
    protected static ?string $model = Speciality::class;
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $navigationIcon = 'icons.specialities';
    protected static ?string $navigationGroup = 'Doctors';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->required()
                ->label(trans('name'))
                ->unique(table: Speciality::class,ignoreRecord:true)
                ->reactive()
                ->afterStateUpdated(fn($state, callable $set)=> $set('slug',Str::slug($state)))
                ->maxLength(150),
            Forms\Components\TextInput::make('name_ar')
                ->unique(table: Speciality::class,ignoreRecord:true)
                ->maxLength(150),
            Forms\Components\TextInput::make('slug')
                ->unique(table: Speciality::class,ignoreRecord:true)
                ->maxLength(150),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('name_ar')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSpecialities::route('/'),
            'create' => Pages\CreateSpeciality::route('/create'),
            'edit' => Pages\EditSpeciality::route('/{record}/edit'),
        ];
    }    
    public static function getRelations(): array
    {
        return [
            RelationManagers\SubSpecialitiesRelationManager::class,
        ];
    }
}
