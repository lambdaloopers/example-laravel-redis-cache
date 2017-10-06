<?php

namespace LaravelRedisCache\Repositories;

use LaravelRedisCache\Product;
use Illuminate\Support\Collection;

class MysqlProductRepository implements ProductRepository
{
    public function getAll(): Collection
    {
        return Product::all();
    }

    public function getById(int $id): Product
    {
        return Product::find($id);
    }

    public function save(Product $product): Product
    {
        $product->save();

        return $product;
    }

    public function delete(int $id): int
    {
        return Product::destroy($id);
    }
}
