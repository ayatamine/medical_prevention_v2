<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Patient;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\PatientResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PatientResource\RelationManagers;
use App\Filament\Resources\PatientResource\Widgets\PatientOverview;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $navigationGroup = 'Patients';
    public static function form(Form $form): Form
    {
        return $form 
            ->schema([
                Forms\Components\TextInput::make('allergy_id'),
                Forms\Components\TextInput::make('chronic_diseases_id'),
                Forms\Components\TextInput::make('family_history_id'),
                Forms\Components\TextInput::make('full_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('birth_date')
                    ->maxLength(255),
                Forms\Components\TextInput::make('thumbnail')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('otp_verification_code')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('otp_expire_at'),
                Forms\Components\TextInput::make('height'),
                Forms\Components\TextInput::make('weight'),
                Forms\Components\TextInput::make('gender')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->maxLength(192),
                Forms\Components\TextInput::make('age'),
                Forms\Components\Toggle::make('notification_status')
                    ->required(),
                Forms\Components\Toggle::make('has_physical_activity')
                    ->required(),
                Forms\Components\Toggle::make('has_cancer_screening')
                    ->required(),
                Forms\Components\Toggle::make('has_depression_screening')
                    ->required(),
                Forms\Components\Textarea::make('other_problems')
                    ->maxLength(16777215),
                Forms\Components\Toggle::make('account_status')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Tables\Columns\TextColumn::make('allergy_id'),
                // Tables\Columns\TextColumn::make('chronic_diseases_id'),
                // Tables\Columns\TextColumn::make('family_history_id'),
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('full_name')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('birth_date')->sortable(),
                Tables\Columns\ImageColumn::make('thumbnail'),
                Tables\Columns\TextColumn::make('phone_number')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('gender')->sortable(),
              
                Tables\Columns\TextColumn::make('created_at')->label('Joined At')
                    ->dateTime(),
                Tables\Columns\IconColumn::make('account_status')->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                SelectFilter::make('account_status')->label('Account Status')->options([
                    1 => 'Active',
                    0 => 'Blocked'
                ]),
                SelectFilter::make('gender')->label('Select Gender')->options([
                    'male' => 'Male',
                    'female' => 'Female',
                ]),
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
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
            'index' => Pages\ListPatients::route('/'),
            // 'create' => Pages\CreatePatient::route('/create'),
            // 'edit' => Pages\EditPatient::route('/{record}/edit'),
            'view' => Pages\ViewPatient::route('/{record}'),
        ];
    } 
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }    
    public static function getWidgets(): array 
    {
        return [
            PatientOverview::class,
        ];
    }
}
