<?php

namespace App\Http\Controllers;

use App\Models\{Service, Product, Article, Store, Announcement, Promotion, Flyer, SiteSetting, Appointment};
use App\Enums\AppointmentStatus;
use App\Http\Controllers\SurveyController;

class HomeController extends Controller
{
    public function index()
    {
        $featuredServices  = Service::active()->limit(4)->get();
        $featuredProducts  = Product::where('is_active', true)->limit(4)->get();
        $recentArticles    = Article::latest('published_at')->limit(3)->get();
        $stores            = Store::active()->get();
        $announcements     = Announcement::active()->orderByDesc('sort_order')->limit(4)->get();
        $promotions        = Promotion::with('coupon')->active()->orderByDesc('sort_order')->limit(3)->get();
        $flyers            = Flyer::active()->orderByDesc('sort_order')->get();

        $heroSettings = [
            'badge'    => SiteSetting::get('hero_badge',    'Premium Pet Grooming'),
            'title'    => SiteSetting::get('hero_title',    null),
            'subtitle' => SiteSetting::get('hero_subtitle', null),
            'image'    => SiteSetting::get('hero_image',    null),
        ];

        $brandSettings = [
            'story_title'      => SiteSetting::get('brand_story_title',  '以「禮」為心，以溫柔為手'),
            'story_text_1'     => SiteSetting::get('brand_story_text_1', null),
            'story_text_2'     => SiteSetting::get('brand_story_text_2', null),
            'philosophy_image' => SiteSetting::get('philosophy_image',   null),
            'stat_stores'      => SiteSetting::get('stat_stores',        '3+'),
            'stat_pets'        => number_format(Appointment::where('status', AppointmentStatus::Completed)->count()),
            'stat_satisfaction'=> SurveyController::computeSatisfaction(),
        ];

        return view('home', compact(
            'featuredServices',
            'featuredProducts',
            'recentArticles',
            'stores',
            'announcements',
            'promotions',
            'flyers',
            'heroSettings',
            'brandSettings'
        ));
    }
}
