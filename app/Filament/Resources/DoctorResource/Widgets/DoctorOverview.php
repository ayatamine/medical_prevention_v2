<?php

namespace App\Filament\Resources\DoctorResource\Widgets;

use App\Models\Doctor;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DoctorOverview extends BaseWidget
{
    protected function getCards(): array
    {
    
        return [
            Card::make('Total Doctors', Doctor::count()),
            Card::make('Pending Account Requests', Doctor::whereAccountStatus('pending')->count()),
            Card::make('Blocked Account',Doctor::whereAccountStatus('blocked')->count())->color('danger'),
        ];
    }
}
