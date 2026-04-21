<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ProductorDashboardController extends Controller
{
    public function index(): View
    {
        return view('productor.dashboard');
    }
}
