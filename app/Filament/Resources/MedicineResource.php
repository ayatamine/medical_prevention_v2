<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Medicine;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;

use App\Filament\Resources\MedicineResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MedicineResource\RelationManagers;

class MedicineResource extends Resource
{
    protected static ?string $model = Medicine::class;

    protected static ?string $navigationIcon = 'icons.medicines';
    protected static string $resource = Medicine::class;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('registeration_number')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('registeration_year')->format('Y')->displayFormat('Y'),
                Forms\Components\TextInput::make('target')
                    ->maxLength(255),
                Forms\Components\TextInput::make('type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('branch')
                    ->maxLength(255),
                Forms\Components\TextInput::make('scientific_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('commercial_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('dose')->numeric()
                    ->maxLength(255),
                Forms\Components\TextInput::make('dose_unit')
                    ->maxLength(255),
                Forms\Components\TextInput::make('pharmaceutical_form')
                    ->maxLength(255),
                Forms\Components\TextInput::make('route')
                    ->maxLength(255),
                Forms\Components\TextInput::make('code1')
                    ->maxLength(255),
                Forms\Components\TextInput::make('code2')
                    ->maxLength(255),
                Forms\Components\TextInput::make('size')->numeric(),
                Forms\Components\TextInput::make('size_unit')
                    ->maxLength(255),
                Forms\Components\TextInput::make('package_type')
                    ->maxLength(255),
                Forms\Components\TextInput::make('package_size')->numeric(),
                Forms\Components\TextInput::make('prescription_method')
                    ->maxLength(255),
                Forms\Components\TextInput::make('control')
                    ->maxLength(255),
                Forms\Components\TextInput::make('marketing_company_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('representative')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registeration_number')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('registeration_year')->sortable()
                    ->date(),
                Tables\Columns\TextColumn::make('target')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('type')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('branch')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('scientific_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('commercial_name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('dose')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('dose_unit')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('pharmaceutical_form')->searchable()->sortable(),
                // Tables\Columns\TextColumn::make('route'),
                Tables\Columns\TextColumn::make('code1')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('code2')->searchable()->sortable(),
                // Tables\Columns\TextColumn::make('size'),
                // Tables\Columns\TextColumn::make('size_unit'),
                // Tables\Columns\TextColumn::make('package_type'),
                // Tables\Columns\TextColumn::make('package_size'),
                // Tables\Columns\TextColumn::make('prescription_method'),
                // Tables\Columns\TextColumn::make('control'),
                // Tables\Columns\TextColumn::make('marketing_company_name'),
                // Tables\Columns\TextColumn::make('representative'),
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
            'index' => Pages\ListMedicines::route('/'),
            'create' => Pages\CreateMedicine::route('/create'),
            'edit' => Pages\EditMedicine::route('/{record}/edit'),
        ];
    }    
}
