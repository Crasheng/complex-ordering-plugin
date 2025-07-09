<?php

namespace Osmanco\ComplexCollection;

use Statamic\Providers\AddonServiceProvider;
use Osmanco\ComplexCollection\Http\Controllers\ItemController;

class ServiceProvider extends AddonServiceProvider
{
    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    protected $namespace = 'Osmanco\\ComplexCollection\\Http\\Controllers';

    public function boot()
    {
        parent::boot();

        // Register the navigation item
        $this->registerNavigation();

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'complex-collection-ordering');

        // Publish config
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/complex-collection.php' => config_path('complex-collection.php'),
            ], 'config');
        }

        // Only try to merge config if the file exists
        $configPath = config_path('complex-collection.php');
        if (file_exists($configPath)) {
            $this->mergeConfigFrom($configPath, 'complex-collection');
        } else {
            // Fallback to default config if published config doesn't exist
            $this->mergeConfigFrom(__DIR__.'/../../config/complex-collection.php', 'complex-collection');
        }

    }

    protected function registerNavigation()
    {
        \Statamic\Facades\CP\Nav::extend(function ($nav) {
            $nav->create(config('complex-collection.navigation_title', 'Complex Collection Ordering'))
                ->section('Tools')
                ->route('complex-collection-ordering.index')
                ->icon('entries');
        });
    }
}
