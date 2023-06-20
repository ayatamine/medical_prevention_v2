<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Filament::serving(function () {
            Filament::registerViteTheme('resources/css/filament.css');
        });

        //settings
        $json = file_get_contents(config('filament-settings.path'));
        $form_inputs=[];
        foreach (json_decode($json,true) as $key=>$value) {
            array_push($form_inputs, \Filament\Forms\Components\TextInput::make($key));
        }
        \Reworck\FilamentSettings\FilamentSettings::setFormFields($form_inputs);
    }
}
