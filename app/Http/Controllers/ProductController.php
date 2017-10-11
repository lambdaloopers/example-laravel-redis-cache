<?php

namespace LaravelRedisCache\Http\Controllers;

use Illuminate\Http\Request;
use LaravelRedisCache\Product;
use LaravelRedisCache\Repositories\ProductRepository;

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
     * @param  \Illuminate\Http\Request $request
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->productRepository->getById($id);

        if (is_null($product)) {
            return response()->json(['status' => 'KO'], 404);
        }

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = $this->productRepository->getById($id);

        if (is_null($product)) {
            return response()->json(['status' => 'KO'], 404);
        }

        $product->name = $request->get('name');

        $product = $this->productRepository->save($product);

        return response()->json($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $numProductsDeleted = $this->productRepository->delete($id);

        return response()->json($numProductsDeleted);
    }
}
