<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()
            ->active()
            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Product list retrieved successfully',
            'data' => $products,
        ]);
    }

    public function show(Product $product)
    {
        if (!$product->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Product is not available',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Product details retrieved successfully',
            'data' => $product,
        ]);
    }
}
