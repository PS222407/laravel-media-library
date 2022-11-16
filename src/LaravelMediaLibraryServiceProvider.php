<?php

namespace Jensramakers\LaravelMediaLibrary;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Jensramakers\LaravelMediaLibrary\app\Http\Livewire\MediaLibrary;
use Jensramakers\LaravelMediaLibrary\app\View\Components\InputFileSelector;
use Jensramakers\LaravelMediaLibrary\app\View\Components\InputImageContainer;
use Livewire\Livewire;

class LaravelMediaLibraryServiceProvider extends ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views/livewire', 'laravel-media-library');
        $this->loadViewsFrom(__DIR__ . '/resources/views/components', 'laravel-media-library');

        Blade::component(InputFileSelector::class, 'input-file-selector');
        Blade::component(InputImageContainer::class, 'input-image-container');

        Livewire::component('media-library', MediaLibrary::class);

        $this->publishes([
            __DIR__ . '/database/migrations/' => database_path('migrations'),
        ], 'laravel-media-library');

        $this->publishes([
            __DIR__ . '/config/medialibrary.php' => config_path('medialibrary.php'),
        ], 'laravel-media-library');
    }
}
