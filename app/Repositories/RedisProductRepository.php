<?php

namespace LaravelRedisCache\Repositories;

use LaravelRedisCache\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;

class RedisProductRepository implements ProductRepository
{
    const CACHE_TTL = 30 * 60;

    private $nestedRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->nestedRepository = $productRepository;
    }

    public function getAll(): Collection
    {
        $products = json_decode(Redis::get('product.all'));

        if (is_null($products)) {
            $products = $this->nestedRepository->getAll();

            Redis::set('product.all', json_encode($products));
            Redis::ttl('product.all', self::CACHE_TTL);
        }

        return $products;
    }

    public function getById(int $id): Product
    {
        $product = json_decode(Redis::get('product.' . $id));

        if (is_null($product)) {
            $product = $this->nestedRepository->getById($id);

            $this->save($product);
        }

        return $product;
    }

    public function save(Product $product): Product
    {
        $this->nestedRepository->save($product);

        Redis::del('product.' . $product->id);
        Redis::set('product.' . $product->id, json_encode($product));
        Redis::ttl('product.' . $product->id, self::CACHE_TTL);

        $products = $this->nestedRepository->getAll();

        Redis::del('product.all');
        Redis::set('product.all', json_encode($products));
        Redis::ttl('product.all', self::CACHE_TTL);

        return $product;
    }

    public function delete(int $id): int
    {
        return Redis::del('product.' . $id);
    }
}