<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Contracts\CouponServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function __construct(private CouponServiceInterface $couponService) {}

    // ── 前台：AJAX 驗證優惠碼 ─────────────────────────────────

    public function validate(Request $request)
    {
        $request->validate(['code' => 'required|string']);

        $result = $this->couponService->validateCode(
            $request->input('code'),
            (int) $request->input('total', 0),
            Auth::id()
        );

        return response()->json($result);
    }

    // ── 後台 CRUD ─────────────────────────────────────────────

    public function adminIndex()
    {
        $coupons = Coupon::with('assignedUser')->latest()->paginate(20);
        return view('admin.coupons', compact('coupons'));
    }

    public function adminStore(Request $request)
    {
        $data = $request->validate([
            'code'             => 'required|string|max:50|unique:coupons,code',
            'name'             => 'required|string|max:100',
            'type'             => 'required|in:percentage,fixed,buy_one_get_one',
            'discount_value'   => 'required|integer|min:0',
            'minimum_amount'   => 'nullable|integer|min:0',
            'scope'            => 'required|in:all,grooming,accommodation,shop',
            'visibility'       => 'required|in:public,member,personal',
            'assigned_user_id' => 'nullable|exists:users,id',
            'max_uses'         => 'nullable|integer|min:1',
            'starts_at'        => 'nullable|date',
            'expires_at'       => 'nullable|date|after_or_equal:starts_at',
            'is_active'        => 'nullable|boolean',
            'show_on_home'     => 'nullable|boolean',
        ]);

        $data['code']           = strtoupper(trim($data['code']));
        $data['minimum_amount'] = $data['minimum_amount'] ?? 0;
        $data['is_active']      = $request->boolean('is_active', true);
        $data['show_on_home']   = $request->boolean('show_on_home', false);

        // personal coupon requires assigned_user_id
        if ($data['visibility'] === 'personal') {
            $data['assigned_user_id'] = $data['assigned_user_id'] ?? null;
        } else {
            $data['assigned_user_id'] = null;
        }

        Coupon::create($data);
        return back()->with('success', '優惠券已建立');
    }

    public function adminUpdate(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'code'             => "required|string|max:50|unique:coupons,code,{$coupon->id}",
            'name'             => 'required|string|max:100',
            'type'             => 'required|in:percentage,fixed,buy_one_get_one',
            'discount_value'   => 'required|integer|min:0',
            'minimum_amount'   => 'nullable|integer|min:0',
            'scope'            => 'required|in:all,grooming,accommodation,shop',
            'visibility'       => 'required|in:public,member,personal',
            'assigned_user_id' => 'nullable|exists:users,id',
            'max_uses'         => 'nullable|integer|min:1',
            'starts_at'        => 'nullable|date',
            'expires_at'       => 'nullable|date|after_or_equal:starts_at',
            'is_active'        => 'nullable|boolean',
            'show_on_home'     => 'nullable|boolean',
        ]);

        $data['code']           = strtoupper(trim($data['code']));
        $data['minimum_amount'] = $data['minimum_amount'] ?? 0;
        $data['is_active']      = $request->boolean('is_active', true);
        $data['show_on_home']   = $request->boolean('show_on_home', false);

        if ($data['visibility'] !== 'personal') {
            $data['assigned_user_id'] = null;
        }

        $coupon->update($data);
        return back()->with('success', '優惠券已更新');
    }

    public function adminDestroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', '優惠券已刪除');
    }
}
