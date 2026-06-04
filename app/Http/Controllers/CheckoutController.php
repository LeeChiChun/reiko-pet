<?php

namespace App\Http\Controllers;

use App\Models\{AccommodationReservation, ShopOrder, Product};
use App\Contracts\{BookingServiceInterface, CouponServiceInterface};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(
        private BookingServiceInterface $bookingService,
        private CouponServiceInterface  $couponService,
    ) {}

    public function show()
    {
        $order = session('checkout_order');
        if (!$order) return redirect('/');

        $coupons = $this->couponService->getAvailableCoupons($order['source'], Auth::id());

        return view('checkout', compact('order', 'coupons'));
    }

    public function pay(Request $request)
    {
        $order = session('checkout_order');
        if (!$order) return redirect('/');

        $paymentMethod = $request->input('payment_method', 'credit');

        // COD only allowed for shop
        if ($paymentMethod === 'cod' && $order['source'] !== 'shop') {
            abort(422);
        }

        if ($paymentMethod !== 'cod') {
            $request->validate([
                'card_name'   => 'required|string|max:100',
                'card_number' => 'required|digits:16',
                'card_expiry' => ['required', 'regex:/^\d{2}\/\d{2}$/'],
                'card_cvv'    => 'required|digits_between:3,4',
            ], [
                'card_name.required'   => '請填寫持卡人姓名',
                'card_number.required' => '請填寫卡號',
                'card_number.digits'   => '卡號應為 16 位數字',
                'card_expiry.required' => '請填寫有效期限',
                'card_expiry.regex'    => '格式應為 MM/YY',
                'card_cvv.required'    => '請填寫 CVV',
            ]);
        }

        $orderNo    = 'RP' . date('Ymd') . rand(1000, 9999);
        $couponId   = (int) $request->input('coupon_id', 0);
        $discount   = (int) $request->input('discount', 0);
        $finalTotal = max(0, $order['total'] - $discount);

        DB::transaction(function () use ($request, $order, $orderNo, $couponId, $discount, $finalTotal, $paymentMethod) {

            if ($order['source'] === 'shop') {
                ShopOrder::create([
                    'user_id'      => Auth::id(),
                    'order_no'     => $orderNo,
                    'guest_name'   => $order['meta']['name']    ?? $request->card_name,
                    'guest_email'  => $order['meta']['email']   ?? '',
                    'guest_phone'  => $order['meta']['phone']   ?? '',
                    'guest_address'=> $order['meta']['address'] ?? '',
                    'items'        => $order['items'],
                    'total'        => $finalTotal,
                    'status'       => $paymentMethod === 'cod' ? 'pending' : 'paid',
                ]);
                foreach ($order['items'] as $item) {
                    if (!empty($item['product_id'])) {
                        Product::where('id', $item['product_id'])
                            ->where('stock', '>=', $item['qty'])
                            ->decrement('stock', $item['qty']);
                    }
                }
                session()->forget('cart');
            }

            if ($order['source'] === 'accommodation') {
                $meta = $order['meta'] ?? [];
                AccommodationReservation::create([
                    'user_id'        => Auth::id(),
                    'order_no'       => $orderNo,
                    'guest_name'     => $meta['guest_name']      ?? '',
                    'guest_phone'    => $meta['guest_phone']      ?? '',
                    'guest_email'    => $meta['guest_email']      ?? '',
                    'pet_name'       => $meta['pet_name']         ?? '',
                    'room_type'      => $meta['room_type']        ?? '',
                    'price_per_night'=> $meta['price_per_night']  ?? 0,
                    'check_in'       => $meta['check_in']         ?? now()->toDateString(),
                    'check_out'      => $meta['check_out']        ?? now()->addDay()->toDateString(),
                    'nights'         => $meta['nights']           ?? 1,
                    'total_price'    => $finalTotal,
                    'status'         => 'paid',
                ]);
            }

            if ($order['source'] === 'grooming') {
                $booking     = $order['meta'] ?? [];
                $appointment = $this->bookingService->createAppointment($booking);
                session([config('booking.last_appointment_key') => $appointment->id]);
                session()->forget(config('booking.session_key'));
            }

            if ($couponId && $discount > 0) {
                $this->couponService->recordUsage($couponId, Auth::id(), $orderNo, $discount);
            }

        }); // end DB::transaction

        session()->forget('checkout_order');
        session(['last_order_no' => $orderNo, 'last_order_source' => $order['source']]);

        return redirect($order['redirect']);
    }

    public function fromCart()
    {
        $cart = session('cart', []);
        if (empty($cart)) return redirect()->route('shop.cart');

        // 結帳前驗證庫存
        foreach ($cart as $productId => $item) {
            $product = Product::find($productId);
            if (!$product) {
                return redirect()->route('shop.cart')
                    ->withErrors(['stock' => "「{$item['name']}」已下架，請從購物車移除"]);
            }
            if ($product->stock < $item['qty']) {
                return redirect()->route('shop.cart')
                    ->withErrors(['stock' => "「{$item['name']}」庫存不足（剩餘 {$product->stock} 件），請調整數量"]);
            }
        }

        $items    = collect($cart)->map(fn($i) => [
            'product_id' => $i['id'],
            'name'       => $i['name'],
            'qty'        => $i['qty'],
            'amount'     => $i['price'] * $i['qty'],
        ])->values()->all();
        $subtotal = collect($cart)->sum(fn($i) => $i['price'] * $i['qty']);
        $shipping = $subtotal >= 999 ? 0 : 60;
        $total    = $subtotal + $shipping;

        if ($shipping > 0) {
            $items[] = ['name' => '運費', 'qty' => 1, 'amount' => $shipping];
        }

        session(['checkout_order' => [
            'source'   => 'shop',
            'title'    => '商城訂單',
            'items'    => $items,
            'total'    => $total,
            'redirect' => route('shop.success'),
        ]]);

        return redirect()->route('checkout.show');
    }
}
