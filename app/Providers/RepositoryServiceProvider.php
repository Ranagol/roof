<?php

namespace App\Providers;

use App\Http\Controllers\AdControllers\AdController;
use App\Http\Controllers\CrawlerControllers\HaloOglasiController;
use App\Http\Controllers\CrawlerControllers\OglasiController;
use App\Repository\Crawler\HaloOglasiCrawlerRepository;
use App\Repository\Crawler\OglasiCrawlerRepository;
use App\Repository\Interfaces\CrawlerRepositoryInterface;
use App\Repository\MyTest\MyTestRepository;
use App\Repository\Interfaces\MyTestRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
	 * @url  https://itnext.io/repository-design-pattern-done-right-in-laravel-d177b5fa75d4
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->when(HaloOglasiController::class)
            ->needs(CrawlerRepositoryInterface::class)
            ->give(HaloOglasiCrawlerRepository::class);

        $this->app->when(OglasiController::class)
            ->needs(CrawlerRepositoryInterface::class)
            ->give(OglasiCrawlerRepository::class);

        $this->app->bind(
            MyTestRepositoryInterface::class,
            MyTestRepository::class
        );
    }
}
