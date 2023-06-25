<?php

namespace App\Filament\Resources\PatientResource\Widgets;

use App\Models\Patient;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class PatientOverview extends BaseWidget
{
    protected function getCards(): array
    {
    
        return [
            Card::make('Total Doctors', Patient::count()),
            Card::make('Active Accounts', Patient::whereAccountStatus(true)->count()),
            Card::make('Blocked Accounts',Patient::whereAccountStatus(false)->count())->color('danger'),
        ];
    }
}
