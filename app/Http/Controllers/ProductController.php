<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'categories' => 'nullable|exists:categories,id',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric',
        ]);

        $search = request('search');
        $categories = request('categories');
        $min = request('min_price');
        $max = request('max_price');
        $size = request('size');
        $sort = request('sort');
        $ratings = request('ratings');
        $perPage = request('perPage') ?? 10;

        if (!in_array($perPage, [10, 20, 40, 60])  ) {
            $perPage = 10;
        }

        return ProductResource::collection(
            Product::search($search)
                ->withCategories($categories)
                ->withMinPrice($min)
                ->withMaxPrice($max)
                ->withSize($size)
                ->withSort($sort)
                ->withRatings($ratings)
                ->with(['categories:name,slug', 'reviews'])
                ->withCount('reviews') // utile per ordinare gli articoli con più recensioni
                ->paginate($perPage)
        );
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load('categories', 'reviews'));
    }

    public function similar(Product $product)
    {
        // Prendo gli ID delle categorie dell'articolo
        $categories = $product->categories->pluck('id');

        /*
        *   Prendo tutti gli articoli che appartengono alle categorie della request,
        *   tutti tranne l'articolo stesso
        *   con distinct prendo solo i risultati unici, infine ritorno una collection
        */
        $products = Product::when($categories, function($query) use($categories) {
            $query
                ->join('category_product', 'products.id', '=', 'category_product.product_id')
                ->join('categories', 'categories.id', '=', 'category_product.category_id')
                ->select('products.*')
                ->whereIn('categories.id', $categories);
            })
                ->where('products.id', '!=', $product->id)
                ->distinct()
                ->limit(12)
                ->get();

        return ProductResource::collection($products);
    }
}
