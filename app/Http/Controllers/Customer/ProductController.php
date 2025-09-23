<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $search = request()->query('search');
        $category = Category::whereSlug(request()->query('category'))->first();

        return view('customer.product.index', compact('search', 'category'));
    }
}
