<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'icons.cogs';
    public static function  canCreate():bool
    {
        return false;
    }
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('app_name')
                    ->maxLength(255),
                Forms\Components\FileUpload::make('app_logo'),
                Forms\Components\TextInput::make('app_slogon')
                    ->maxLength(255),
                Forms\Components\Textarea::make('app_description')
                    ->maxLength(16777215),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('signature_image'),
                Forms\Components\TextInput::make('customer_service_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('whatsapp_number')
                    ->maxLength(255),
                Forms\Components\TextInput::make('facebook_link')
                    ->maxLength(255),
                Forms\Components\TextInput::make('twitter_link')
                    ->maxLength(255),
                Forms\Components\TextInput::make('instagram_link')
                    ->maxLength(255),
                Forms\Components\TextInput::make('linkedin_link')
                    ->maxLength(255),
                Forms\Components\TextInput::make('website_link')
                    ->maxLength(255),
                Forms\Components\TextInput::make('commercial_register')
                    ->maxLength(255),
                Forms\Components\TextInput::make('ministery_licence')
                    ->maxLength(255),
                Forms\Components\TextInput::make('post_address')
                    ->maxLength(255),
                Forms\Components\TimePicker::make('work_time_from'),
                Forms\Components\TimePicker::make('work_time_to'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('app_name'),
                Tables\Columns\ImageColumn::make('app_logo'),
                Tables\Columns\TextColumn::make('app_slogon'),
                Tables\Columns\TextColumn::make('app_description'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('signature_image'),
                Tables\Columns\TextColumn::make('customer_service_number'),
                Tables\Columns\TextColumn::make('whatsapp_number'),
                Tables\Columns\TextColumn::make('commercial_register'),
                Tables\Columns\TextColumn::make('ministery_licence'),
                Tables\Columns\TextColumn::make('post_address'),
              
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListSettings::route('/'),
            // 'create' => Pages\CreateSetting::route('/create'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }    
}
