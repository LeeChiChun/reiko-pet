<?php

namespace App\Http\Controllers;

use App\Models\Promotion;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::with('coupon')->active()->orderByDesc('sort_order')->get();
        return view('promotions.index', compact('promotions'));
    }

    public function show(Promotion $promotion)
    {
        abort_unless($promotion->is_active, 404);
        $promotion->load('coupon');
        return view('promotions.show', compact('promotion'));
    }
}
