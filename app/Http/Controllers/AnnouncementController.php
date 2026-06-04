<?php

namespace App\Http\Controllers;

use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::active()
            ->orderByDesc('sort_order')
            ->orderByDesc('published_at')
            ->paginate(10);
        return view('announcements.index', compact('announcements'));
    }

    public function show(Announcement $announcement)
    {
        abort_if(!$announcement->is_active, 404);
        return view('announcements.show', compact('announcement'));
    }
}
