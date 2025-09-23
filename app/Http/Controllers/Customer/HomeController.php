<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PDO;

class HomeController extends Controller
{
    public function index()
    {
        return view('customer.home.index');
    }
}
