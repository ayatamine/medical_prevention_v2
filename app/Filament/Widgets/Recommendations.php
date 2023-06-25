<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class Recommendations extends BaseWidget
{
    protected static string $view = 'filament.widgets.recommendations-widget';
    protected int | string | array $columnSpan = [
        'md' => 3,
        'xl' => 2,
    ];
    protected function getCards(): array
    {
        return [
            //
        ];
    }
}
