<?php

namespace App\Http\Controllers;

use App\Models\{Pet, Appointment, Promotion, Coupon, AccommodationReservation, ShopOrder, ArticleBookmark, ProductBookmark};
use App\Contracts\BookingServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function __construct(private BookingServiceInterface $bookingService) {}

    public function index()
    {
        $user = Auth::user();
        $upcomingAppointments = Appointment::with(['pet', 'service', 'store'])
            ->where('customer_id', $user->id)
            ->upcoming()
            ->orderBy('appointment_at')
            ->take(3)->get();
        $recentAppointments = Appointment::with(['pet', 'service', 'store'])
            ->where('customer_id', $user->id)
            ->orderByDesc('appointment_at')
            ->take(5)->get();
        $totalAppointments = Appointment::where('customer_id', $user->id)->count();
        $petsCount         = $user->pets()->count();
        $promotions        = Promotion::active()->orderByDesc('sort_order')->take(3)->get();
        $homeCoupons       = Coupon::forHome($user->id);
        return view('member.index', compact('user', 'upcomingAppointments', 'recentAppointments', 'totalAppointments', 'petsCount', 'promotions', 'homeCoupons'));
    }

    public function profile()
    {
        $user = Auth::user();
        $pets = $user->pets()->latest()->get();
        return view('member.profile', compact('user', 'pets'));
    }

    public function updateProfile(Request $request)
    {
        $data = $request->validate([
            'name'  => 'required|string|max:50',
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required' => '請輸入姓名',
        ]);
        Auth::user()->update($data);
        return back()->with('success', '個人資料已更新');
    }

    public function pets()
    {
        $pets = Auth::user()->pets()->latest()->get();
        return view('member.pets', compact('pets'));
    }

    public function storePet(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required|string|max:50',
            'type'        => 'required|in:dog,cat',
            'breed'       => 'nullable|string|max:50',
            'gender'      => 'nullable|in:male,female',
            'age'         => 'nullable|integer|min:0|max:30',
            'weight'      => 'nullable|numeric|min:0|max:100',
            'health_note' => 'nullable|string|max:500',
            'notes'       => 'nullable|string|max:500',
        ], [
            'name.required' => '請輸入寵物名稱',
            'type.required' => '請選擇寵物種類',
        ]);
        Auth::user()->pets()->create($data);
        return back()->with('success', '寵物資料已新增');
    }

    public function updatePet(Request $request, Pet $pet)
    {
        abort_if($pet->user_id !== Auth::id(), 403);
        $data = $request->validate([
            'name'        => 'required|string|max:50',
            'type'        => 'required|in:dog,cat',
            'breed'       => 'nullable|string|max:50',
            'gender'      => 'nullable|in:male,female',
            'age'         => 'nullable|integer|min:0|max:30',
            'weight'      => 'nullable|numeric|min:0|max:100',
            'health_note' => 'nullable|string|max:500',
            'notes'       => 'nullable|string|max:500',
        ]);
        $pet->update($data);
        return back()->with('success', '寵物資料已更新');
    }

    public function destroyPet(Pet $pet)
    {
        abort_if($pet->user_id !== Auth::id(), 403);
        $pet->delete();
        return back()->with('success', '寵物資料已刪除');
    }

    public function appointments()
    {
        $appointments = Appointment::with(['pet', 'service', 'store', 'addons.addonService'])
            ->where('customer_id', Auth::id())
            ->orderByDesc('appointment_at')
            ->paginate(10);
        return view('member.appointments', compact('appointments'));
    }

    public function cancelAppointment(Appointment $appointment)
    {
        abort_if(!$appointment->ownedBy(Auth::id()), 403);
        abort_if(!$appointment->canBeCancelled(), 422);
        $appointment->cancel();
        return back()->with('success', '預約已取消');
    }

    // ── 住宿預約紀錄 ──────────────────────────────────────
    public function accommodation()
    {
        $reservations = AccommodationReservation::where('user_id', Auth::id())
            ->with('room')
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('member.accommodation', compact('reservations'));
    }

    public function cancelAccommodation(AccommodationReservation $reservation)
    {
        abort_if(!$reservation->ownedBy(Auth::id()), 403);
        abort_if(!$reservation->canBeCancelled(), 422);
        $reservation->cancel();
        return back()->with('success', '住宿預約已取消');
    }

    // ── 商城訂單 ─────────────────────────────────────────
    public function orders()
    {
        $orders = ShopOrder::where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(10);
        return view('member.orders', compact('orders'));
    }

    // ── 收藏文章 ─────────────────────────────────────────
    public function bookmarks()
    {
        $bookmarks = ArticleBookmark::with('article')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(12);
        return view('member.bookmarks', compact('bookmarks'));
    }

    public function removeBookmark(ArticleBookmark $bookmark)
    {
        abort_if($bookmark->user_id !== Auth::id(), 403);
        $bookmark->delete();
        return back()->with('success', '已取消收藏');
    }

    // ── 收藏商品 ─────────────────────────────────────────
    public function productBookmarks()
    {
        $bookmarks = ProductBookmark::with('product')
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->paginate(12);
        return view('member.product_bookmarks', compact('bookmarks'));
    }

    public function removeProductBookmark(ProductBookmark $bookmark)
    {
        abort_if($bookmark->user_id !== Auth::id(), 403);
        $bookmark->delete();
        return back()->with('success', '已取消收藏');
    }
}
