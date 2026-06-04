<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\AddonService;

class ServiceController extends Controller
{
    public function index()
    {
        return view('services', [
            'singles'   => Service::active()->where('category', 'single')->get(),
            'dogs'      => Service::active()->where('category', 'dog')->get(),
            'cats'      => Service::active()->where('category', 'cat')->get(),
            'smallPkgs' => Service::active()->where('category', 'small_pkg')->get(),
            'largePkgs' => Service::active()->where('category', 'large_pkg')->get(),
            'addons'    => AddonService::where('is_active', true)->get(),
        ]);
    }
}
