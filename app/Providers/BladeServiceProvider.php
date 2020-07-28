<?php

declare (strict_types = 1);

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadComponents()->loadViews();
    }

    /**
     * Load components
     * 
     * @return self
     */
    protected function loadComponents(): self
    {
        Blade::component("backend.layouts.app", "app-layout");

        return $this;
    }

    /**
     * Load views hint path
     * 
     * @return self
     */
    protected function loadViews(): self
    {
        $this->loadViewsFrom(resource_path("views/backend"), "backend");

        return $this;
    }
}
