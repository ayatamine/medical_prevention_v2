<?php
 
namespace App\Filament\Pages;
 
use Filament\Pages\Dashboard as BasePage;
 
class Dashboard extends BasePage
{
    protected function getColumns(): int | array
    {
        return [
            'md' => 4,
            'xl' => 5,
        ];
    }
}