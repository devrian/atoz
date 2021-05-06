<?php

namespace App\Providers;

use App\Repositories\OrderRepository;
use App\Repositories\PrepaidBalanceRepository;
use App\Repositories\ProductRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\OrderInterface;
use App\Interfaces\PrepaidBalanceInterface;
use App\Interfaces\ProductInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ProductInterface::class, ProductRepository::class);
        $this->app->bind(PrepaidBalanceInterface::class, PrepaidBalanceRepository::class);
        $this->app->bind(OrderInterface::class, OrderRepository::class);
    }
}
