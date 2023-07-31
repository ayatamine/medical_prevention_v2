<?php

namespace App\Filament\Pages;

use Filament\Forms;
use App\Models\Doctor;
use App\Notifications\OnlineDoctorNotification;
use Filament\Pages\Page;
use Filament\Resources\Form;
use Filament\Pages\Actions\Action;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Notification;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification as viewNotification;

class OnlineDoctors extends Page
{
    use  InteractsWithForms; 
    public $notification_title,$notification_content;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = false;
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
               
                $doctors = Doctor::with('specialities:id,name')->with('sub_specialities:id,name')->whereNotificationStatus(true)->get();
                $delay = now()->addMinutes(2);
                Notification::send($doctors, (new OnlineDoctorNotification($data))->delay($delay));

                viewNotification::make()
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
 
}
