<?php

namespace LaravelRedisCache\Repositories;

use Illuminate\Support\Collection;
use LaravelRedisCache\Product;

interface ProductRepository
{
    function getAll(): Collection;

    function getById(int $id): ?Product;

    function save(Product $product): Product;

    function delete(int $id): int;
}
