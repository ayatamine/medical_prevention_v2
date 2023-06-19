<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Doctor;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\DoctorResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\DoctorResource\RelationManagers;
use App\Filament\Resources\DoctorResource\Widgets\DoctorOverview;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static ?string $navigationIcon = 'icons.doctor';
    protected static ?string $navigationGroup = 'Doctors';

    public static function canCreate():bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('full_name')
                ->label(trans('full_name')),
                Forms\Components\TextInput::make('email')
                ->label(trans('email')),
                
                Forms\Components\TextInput::make('phone_number')
                ->label(trans('phone_number')),
                Forms\Components\Toggle::make('is_phone_verified')
                ->label(trans('is_phone_verified')),
                Forms\Components\Select::make('sub_specialities')
                ->multiple()
                ->relationship('sub_specialities', 'name'),
                Forms\Components\TextInput::make('job_title')
                ->label(trans('job_title')),



                Forms\Components\TextInput::make('id_number')
                ->label(trans('Identification Number')),
                Forms\Components\TextInput::make('classification_number')
                ->label(trans('classification_number')),
                Forms\Components\TextInput::make('insurance_number')
                ->label(trans('insurance_number')),
                
                Forms\Components\FileUpload::make('thumbnail')
                ->label(trans('Thumbnail')),
                Forms\Components\FileUpload::make('medical_licence_file')
                ->label(trans('medical_licence_file')),
                Forms\Components\FileUpload::make('cv_file')
                ->label(trans('cv_file')),
                Forms\Components\FileUpload::make('certification_file')
                ->label(trans('certification_file')),
                //opt
                Forms\Components\TextInput::make('birth_date')
                ->label(trans('birth_date')),
                Forms\Components\TextInput::make('gender')
                ->label(trans('gender')),

                Forms\Components\Select::make('account_status')
                ->options([
                    'pending' => 'Pending',
                    'accepted' => 'Accepted',
                    'blocked' => 'Blocked',
                ])
                ->label(trans('Account Status')),
           
              
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->sortable(),
                Tables\Columns\TextColumn::make('full_name')->sortable()->searchable(),
                Tables\Columns\ImageColumn::make('thumbnail')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('id_number')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('gender')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('job_title')->sortable()->searchable(),
                Tables\Columns\IconColumn::make('is_phone_verified')->sortable()->searchable(),
                Tables\Columns\SelectColumn::make('account_status') 
                ->options([
                    'pending' => 'Pending',
                    'accepted' => 'Accepted',
                    'blocked' => 'Blocked',
                ]),

            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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
            'index' => Pages\ListDoctors::route('/'),
            // 'create' => Pages\CreateDoctor::route('/create'),
            // 'edit' => Pages\EditDoctor::route('/{record}/edit'),
            'view' => Pages\ViewDoctor::route('/{record}'),
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
            DoctorOverview::class,
        ];
    }
}
