<?php

namespace LaravelRedisCache\Http\Controllers;

use LaravelRedisCache\Product;
use LaravelRedisCache\Repositories\ProductRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->productRepository->getAll();

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = new Product;

        $product->name = $request->get('name');

        $product = $this->productRepository->save($product);

        return response()->json($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->productRepository->getById($id);

        if (is_null($product)) {
            throw (new ModelNotFoundException)
                ->setModel(get_class(Product::class), $id);
        }

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $currentProduct = $this->productRepository->getById($id);

        if (is_null($currentProduct)) {
            throw (new ModelNotFoundException)
                ->setModel(get_class(Product::class), $id);
        }

        $product = new Product;

        $product->name = $request->get('name');

        $product = $this->productRepository->save($product);

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $numProductsDeleted = $this->productRepository->delete($id);

        return response()->json($numProductsDeleted);
    }
}
