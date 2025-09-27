<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller; // ← Agregar esta línea


class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        return view('dashboard.index', compact('user'));
    }

    public function tests()
    {
        return view('dashboard.tests');
    }

    public function careers()
    {
        return view('dashboard.careers');
    }

    public function recommendations()
    {
        return view('dashboard.recommendations');
    }

    public function profile()
    {
        $user = Auth::user();
        return view('dashboard.profile', compact('user'));
    }
}
