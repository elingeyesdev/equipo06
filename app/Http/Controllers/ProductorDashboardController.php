<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ProductorDashboardController extends Controller
{
    public function index(): View
    {
        $notificaciones = auth()->user()->notifications()
            ->where('type', 'App\Notifications\ComenzarCultivoNotification')
            ->latest()
            ->take(10)
            ->get();

        return view('productor.dashboard', compact('notificaciones'));
    }
}
