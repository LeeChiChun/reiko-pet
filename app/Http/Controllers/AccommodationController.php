<?php

namespace App\Http\Controllers;

use App\Models\{AccommodationReservation, AccommodationRoom, SiteSetting};
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccommodationController extends Controller
{
    public function index()
    {
        $rooms     = AccommodationRoom::active()->ordered()->get();
        $userPets  = Auth::check() ? Auth::user()->pets()->latest()->get() : collect();

        $settings = [
            'hero_title'    => SiteSetting::get('accom_hero_title',    '寵物住宿'),
            'hero_subtitle' => SiteSetting::get('accom_hero_subtitle', '當你需要出差或旅行，把毛孩安心託付給禮寵。我們提供舒適、安全、充滿溫度的住宿環境。'),
            'benefits'      => $this->buildBenefits(),
            'rules'         => $this->buildRules(),
        ];

        return view('accommodation', compact('rooms', 'userPets', 'settings'));
    }

    public function book(Request $request)
    {
        $validSlugs = AccommodationRoom::active()->pluck('slug')->implode(',');

        $data = $request->validate([
            'room_type'   => ['required', 'in:' . $validSlugs],
            'check_in'    => 'required|date|after_or_equal:today',
            'check_out'   => 'required|date|after:check_in',
            'pet_id'      => 'nullable|integer',
            'pet_name'    => 'required_without:pet_id|nullable|string|max:50',
            'pet_type'    => 'nullable|string|max:20',
            'guest_name'  => 'required|string|max:50',
            'guest_phone' => 'required|string|max:20',
            'guest_email' => 'required|email|max:100',
        ], [
            'room_type.required'      => '請選擇房型',
            'check_in.required'       => '請選擇入住日期',
            'check_in.after_or_equal' => '入住日期不能早於今天',
            'check_out.required'      => '請選擇退房日期',
            'check_out.after'         => '退房日期必須晚於入住日期',
            'pet_name.required_without' => '請填寫寵物名稱',
            'guest_name.required'     => '請填寫聯絡人姓名',
            'guest_phone.required'    => '請填寫聯絡電話',
            'guest_email.required'    => '請填寫電子信箱',
        ]);

        // 若有選擇已建立的寵物
        if (!empty($data['pet_id']) && Auth::check()) {
            $pet = Auth::user()->pets()->find($data['pet_id']);
            if ($pet) {
                $data['pet_name'] = $pet->name;
                $data['pet_type'] = match($pet->type) {
                    'dog' => '狗', 'cat' => '貓', default => $pet->type
                };
            }
        }

        $room          = AccommodationRoom::where('slug', $data['room_type'])->firstOrFail();
        $nights        = Carbon::parse($data['check_in'])->diffInDays(Carbon::parse($data['check_out']));
        $pricePerNight = $room->price_per_night;
        $total         = $pricePerNight * $nights;

        session(['checkout_order' => [
            'source'   => 'accommodation',
            'title'    => '寵物住宿預約',
            'items'    => [[
                'name'   => $room->name . ' × ' . $nights . '晚（' . $data['check_in'] . ' ~ ' . $data['check_out'] . '）',
                'qty'    => 1,
                'amount' => $total,
            ]],
            'total'    => $total,
            'redirect' => route('accommodation.success'),
            'meta'     => array_merge($data, [
                'room_label'      => $room->name,
                'nights'          => $nights,
                'price_per_night' => $pricePerNight,
            ]),
        ]]);

        return redirect()->route('checkout.show');
    }

    public function success()
    {
        $orderNo = session('last_order_no');
        if (!$orderNo) return redirect()->route('accommodation.index');

        return view('accommodation_success', compact('orderNo'));
    }

    private function buildBenefits(): array
    {
        $benefits = [];
        for ($i = 1; $i <= 8; $i++) {
            $benefits[] = [
                'icon'  => SiteSetting::get("accom_b{$i}_icon",  ''),
                'title' => SiteSetting::get("accom_b{$i}_title", ''),
                'desc'  => SiteSetting::get("accom_b{$i}_desc",  ''),
            ];
        }
        return array_filter($benefits, fn($b) => $b['title'] !== '');
    }

    private function buildRules(): array
    {
        $rules = [];
        for ($i = 1; $i <= 4; $i++) {
            $title = SiteSetting::get("accom_r{$i}_title", '');
            $raw   = SiteSetting::get("accom_r{$i}_items", '');
            if (!$title) continue;
            $rules[] = [
                'title' => $title,
                'items' => array_filter(array_map('trim', explode("\n", $raw))),
            ];
        }
        return $rules;
    }
}
