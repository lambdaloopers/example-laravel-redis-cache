<?php

namespace LaravelRedisCache\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;
use LaravelRedisCache\Product;

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
        $rawProducts = json_decode(Redis::get('product.all'));

        if (!is_null($rawProducts)) {
            $products = collect(
                array_map(function ($rawProduct) {
                    $product = new Product;
                    $product->id = $rawProduct->id;
                    $product->name = $rawProduct->name;

                    return $product;
                }, $rawProducts)
            );

            return $products;
        }

        $products = $this->updateProductList();

        return $products;
    }

    public function getById(int $id): ?Product
    {
        $rawProduct = json_decode(Redis::get('product.' . $id));

        if (!is_null($rawProduct)) {
            $product = new Product;
            $product->id = $rawProduct->id;
            $product->name = $rawProduct->name;

            return $product;
        }

        $product = $this->nestedRepository->getById($id);

        if (!is_null($product)) {
            $this->save($product);
        }

        return $product;
    }

    public function save(Product $product): Product
    {
        $this->nestedRepository->save($product);

        Redis::del('product.' . $product->id);
        Redis::set('product.' . $product->id, json_encode($product));
        Redis::expire('product.' . $product->id, self::CACHE_TTL);

        $this->updateProductList();

        return $product;
    }

    public function delete(int $id): int
    {
        $numDeletes = Redis::del('product.' . $id);

        $this->nestedRepository->delete($id);

        $this->updateProductList();

        return $numDeletes;
    }
    
    private function updateProductList(): Collection
    {
        $products = $this->nestedRepository->getAll();

        Redis::del('product.all');
        Redis::set('product.all', json_encode($products));
        Redis::expire('product.all', self::CACHE_TTL);

        return $products;
    }
}