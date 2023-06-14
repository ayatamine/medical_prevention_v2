<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MedicalInstructionResource\Pages;
use App\Filament\Resources\MedicalInstructionResource\RelationManagers;
use App\Models\MedicalInstruction;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MedicalInstructionResource extends Resource
{
    protected static ?string $model = MedicalInstruction::class;
    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $navigationIcon = 'icons.medical_instructions';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                ->required()
                ->label(trans('title'))
                ->unique(table: MedicalInstruction::class,ignoreRecord: true)
                ->maxLength(150),
                Forms\Components\TextInput::make('title_ar')
                    ->required()
                    ->unique(table: MedicalInstruction::class,ignoreRecord: true)
                    ->maxLength(150),
                Forms\Components\FileUpload::make('image')
                    ->required(),
                Forms\Components\Textarea::make('content')
                    ->required()
                    ->columnSpan('full')
                    ->maxLength(16777),
                Forms\Components\Textarea::make('content_ar')
                    ->required()
                    ->columnSpan('full')
                    ->maxLength(16777),
                Forms\Components\DatePicker::make('publish_date')
                    ->required(),
                Forms\Components\TextInput::make('duration')
                    ->label('Duration in days')
                    ->numeric()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('id'),
                Tables\Columns\ImageColumn::make('image'),
                Tables\Columns\TextColumn::make('title')->label('Title')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('title_ar')->label('Title in Arabic')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('publish_date')->dateTime()->label('Publish Date')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('duration')->label('Duration(days)')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('status')->label('status')->sortable()->searchable()
                ->color('success'),

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
            'index' => Pages\ListMedicalInstructions::route('/'),
            'create' => Pages\CreateMedicalInstruction::route('/create'),
            'edit' => Pages\EditMedicalInstruction::route('/{record}/edit'),
        ];
    }    
}
