<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $search = request('search');

        return ProductResource::collection(
            Product::search($search)->get()
        );
    }

    public function show(Product $product)
    {
        return new ProductResource($product);
    }
}
