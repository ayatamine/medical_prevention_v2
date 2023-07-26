<?php

namespace App\Filament\Pages;

use Filament\Forms;
use App\Models\Doctor;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Pages\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;

class OnlineDoctors extends Page
{
    use  InteractsWithForms; 
    public $notification_title,$notification_content;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.online-doctors';
    protected function getViewData(): array
    {
       $doctors = Doctor::with('specialities:id,name')->with('sub_specialities:id,name')->whereNotificationStatus(true)->paginate(10);
       return [
        'doctors'=>$doctors
       ];
    }
    protected function getActions(): array
    {
        return [
            Action::make('Send Notification')->color('primary')
            ->action(function (array $data): void {
                Notification::make()
                            ->title('Notifications send successfully to doctors')
                            ->success() 
                            ->duration(5000) 
                            ->send();
            })
            ->form([
                Forms\Components\TextInput::make('notification_title')
                ->required()
                ->label(trans('notification_title'))
                ->maxLength(150),
                Forms\Components\Textarea::make('notification_content')
                    ->required()
                    ->columnSpan('full')
                    ->maxLength(16777),
                    
            ])
        ];
    }
   protected function getFormSchema(): array
    {  
        return [
            Section::make('Send Notification')
                ->schema([
                    Forms\Components\TextInput::make('notification_title')
                    ->required()
                    ->label(trans('notification_title'))
                    ->maxLength(150),
                    Forms\Components\Textarea::make('notification_content')
                        ->required()
                        ->columnSpan('full')
                        ->maxLength(16777),
                        
                ])
                ->collapsible()
                ->collapsed()
                
            ];
    }
    public function submit(): void
    {
       dd($this->form->getState());
    }
}
