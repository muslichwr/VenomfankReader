<?php

namespace App\Providers;

use App\Models\Transaction;
use App\Observers\TransactionObserver;
use Illuminate\Support\ServiceProvider;
use App\Repository\SeriesRepositoryInterface;
use App\Repository\SeriesRepository;
use App\Repository\ChapterRepositoryInterface;
use App\Repository\ChapterRepository;
use App\Repository\CoinPackageRepositoryInterface;
use App\Repository\CoinPackageRepository;
use App\Repository\TransactionRepositoryInterface;
use App\Repository\TransactionRepository;

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
        $this->app->bind(CoinPackageRepositoryInterface::class, CoinPackageRepository::class);
        $this->app->bind(TransactionRepositoryInterface::class, TransactionRepository::class);
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
