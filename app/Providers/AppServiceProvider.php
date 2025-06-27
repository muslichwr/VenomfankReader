<?php

namespace App\Providers;

use App\Models\Transaction;
use App\Observers\TransactionObserver;
use Illuminate\Support\ServiceProvider;
use App\Repository\SeriesRepositoryInterface;
use App\Repository\SeriesRepository;
use App\Repository\ChapterRepositoryInterface;
use App\Repository\ChapterRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->bind(SeriesRepositoryInterface::class, SeriesRepository::class);
        $this->app->bind(ChapterRepositoryInterface::class, ChapterRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observers
        Transaction::observe(TransactionObserver::class);
    }
}
