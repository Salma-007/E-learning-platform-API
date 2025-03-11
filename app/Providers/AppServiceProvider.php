<?php

namespace App\Providers;

use App\Services\TagService;
use App\Interfaces\TagInterface;
use App\Services\CategoryService;
use App\Repositories\TagRepository;
use App\Interfaces\CategoryInterface;
use Illuminate\Support\ServiceProvider;
use App\Repositories\CategoryRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CategoryInterface::class, CategoryRepository::class);
        $this->app->bind(CategoryService::class);
        $this->app->bind(TagInterface::class, TagRepository::class);
        $this->app->bind(TagService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
