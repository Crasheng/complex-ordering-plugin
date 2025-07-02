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

        // Publish migrations
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'complex-collection-ordering-migrations');
    }

    protected function registerNavigation()
    {
        \Statamic\Facades\CP\Nav::extend(function ($nav) {
            $nav->create('Complex Collection Ordering')
                ->section('Tools')
                ->route('complex-collection-ordering.index')
                ->icon('entries');
        });
    }
}
