<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Consultation;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DashboardOverView extends BaseWidget
{
    // protected static string $view = 'filament.widgets.dashboard-over-view';
    protected static ?string $pollingInterval = '360s';

    protected function getCards(): array
    {
    
        $patient_today = Patient::whereDate('created_at',Carbon::today())->count();
        return [
            Card::make('Total Patients',Patient::count() )
            ->icon('heroicon-s-users')
            ->description($patient_today.' Joined Today')
            ->descriptionColor('white')
            ->url(route('filament.resources.doctors.index'))
            ->extraAttributes(['new-card-design'=>true,'blue-card'=>true])
            ->descriptionIcon('icons.arrow-right'),
            Card::make('Total Doctors',Doctor::count() )
            ->icon('icons.doctor')
            ->description('View Details')
            ->descriptionColor('white')
            ->url(route('filament.resources.doctors.index'))
            ->extraAttributes(['new-card-design'=>true,'blue-fonc-card'=>true])
            ->descriptionIcon('icons.arrow-right'),
            Card::make('Online Doctors',Doctor::where('last_online_at', '>=', Carbon::now()->subMinutes(5))->get()->count())
            ->icon('icons.doctor')
            ->description('View Details')
            ->descriptionColor('white')
            ->url(route('filament.pages.online-doctors'))
            ->extraAttributes(['new-card-design'=>true,'open-green-card'=>true])
            ->descriptionIcon('icons.arrow-right'),
            Card::make('Total Consultations',Consultation::count() )
            ->icon('icons.consult')
            ->description('View Details')
            ->descriptionColor('white')
            ->url(route('filament.resources.doctors.index'))
            ->extraAttributes(['new-card-design'=>true,'open-red-card'=>true])
            ->descriptionIcon('icons.arrow-right'),
        ];
    }
}
