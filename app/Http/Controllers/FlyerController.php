<?php

namespace App\Http\Controllers;

use App\Models\Flyer;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FlyerController extends Controller
{
    public function index()
    {
        $flyers = Flyer::active()->orderByDesc('sort_order')->get();
        return view('flyers.index', compact('flyers'));
    }

    public function download(Flyer $flyer): StreamedResponse
    {
        abort_if(!$flyer->image_path, 404);
        abort_if(!Storage::disk('public')->exists($flyer->image_path), 404);

        $filename = $flyer->title . '.' . pathinfo($flyer->image_path, PATHINFO_EXTENSION);

        return Storage::disk('public')->download($flyer->image_path, $filename);
    }
}
