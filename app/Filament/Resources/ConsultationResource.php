<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Consultation;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ConsultationResource\Pages;
use App\Filament\Resources\ConsultationResource\RelationManagers;

class ConsultationResource extends Resource
{
    protected static ?string $model = Consultation::class;

    protected static ?string $navigationIcon = 'icons.consult';
    protected static ?string $navigationGroup = 'Consultations';

    public static function form(Form $form): Form
    {    
        return $form
            ->schema([
                Forms\Components\Select::make('doctor_id')
                ->relationship('doctor', 'full_name')
                ->required(),
                Forms\Components\Select::make('patient_id')
                    ->relationship('patient', 'full_name')
                    ->required(),
                Forms\Components\DateTimePicker::make('start_time'),
                Forms\Components\DateTimePicker::make('end_time'),
                // Forms\Components\Textarea::make('notes')
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('doctor.full_name')->label('Doctor'),
                Tables\Columns\TextColumn::make('patient.full_name')->label('Patient'),
                Tables\Columns\TextColumn::make('start_time')->label('Start time')->dateTime(),
                Tables\Columns\TextColumn::make('end_time')->label('End time')->dateTime(),
                Tables\Columns\SelectColumn::make('status')->label('Consultation Status') 
                ->options([
                    'pending' => 'Pending',
                    'in_progress' => 'In progress',
                    'incompleted' => 'Incompleted',
                    'completed' => 'completed',
                    'canceled' => 'Canceled',
                    'completed' => 'Completed',
                    'rejected' => 'Rejected',
                ]),
            ])
            ->filters([
                SelectFilter::make('status')->label('Select Status')->options([
                    'pending' => 'Pending',
                    'in_progress' => 'In progress',
                    'incompleted' => 'Incompleted',
                    'completed' => 'completed',
                    'canceled' => 'Canceled',
                    'completed' => 'Completed',
                    'rejected' => 'Rejected',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListConsultations::route('/'),
            'create' => Pages\CreateConsultation::route('/create'),
            'edit' => Pages\EditConsultation::route('/{record}/edit'),
        ];
    }    
}
