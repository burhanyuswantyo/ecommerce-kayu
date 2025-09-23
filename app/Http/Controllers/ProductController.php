<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('pages.product.index');
    }

    public function show(Product $product)
    {
        return view('pages.product.show', compact('product'));
    }
}
