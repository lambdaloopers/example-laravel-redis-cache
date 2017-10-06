<?php

namespace LaravelRedisCache\Repositories;

use LaravelRedisCache\Product;
use Illuminate\Support\Collection;

interface ProductRepository
{
    function getAll(): Collection;

    function getById(int $id): Product;

    function save(Product $product): Product;

    function delete(int $id): int;
}
