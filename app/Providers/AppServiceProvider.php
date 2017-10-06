<?php

namespace LaravelRedisCache\Providers;

use LaravelRedisCache\Http\Controllers\ProductController;
use LaravelRedisCache\Repositories\MysqlProductRepository;
use LaravelRedisCache\Repositories\ProductRepository;
use LaravelRedisCache\Repositories\RedisProductRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->when(ProductController::class)
            ->needs(ProductRepository::class)
            ->give(function () {
                return new RedisProductRepository(
                    new MysqlProductRepository()
                );
            });
    }
}
