<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use App\Filament\Pages\Chat;
use Illuminate\Support\Facades\URL;
use App\Filament\Forms\AvatarUploadForm;
use App\Filament\Pages\Profile;

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
        Livewire::component('filament.pages.profile', Profile::class);
        Livewire::component('filament.pages.chat', Chat::class);

        if (app()->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
