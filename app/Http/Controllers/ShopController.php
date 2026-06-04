<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductBookmark;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function index(Request $request)
    {
        $query = Product::active();
        if ($request->filled('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $products   = $query->latest()->paginate(12)->withQueryString();
        $categories = Product::active()->distinct()->pluck('category');
        return view('shop.index', compact('products', 'categories'));
    }

    public function show(Product $product)
    {
        $related = Product::active()
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)->get();
        $isBookmarked = Auth::check()
            ? ProductBookmark::where('user_id', Auth::id())->where('product_id', $product->id)->exists()
            : false;
        return view('shop.show', compact('product', 'related', 'isBookmarked'));
    }

    public function toggleBookmark(Product $product)
    {
        $userId = Auth::id();
        $bookmark = ProductBookmark::where('user_id', $userId)->where('product_id', $product->id)->first();

        if ($bookmark) {
            $bookmark->delete();
            $bookmarked = false;
            $message = '已取消收藏';
        } else {
            ProductBookmark::create(['user_id' => $userId, 'product_id' => $product->id]);
            $bookmarked = true;
            $message = '已加入收藏';
        }

        return response()->json(['success' => true, 'bookmarked' => $bookmarked, 'message' => $message]);
    }

    public function addToCart(Request $request, Product $product)
    {
        $qty = max(1, (int) $request->input('quantity', 1));
        $this->cartService->add($product, $qty);
        $cartCount = collect($this->cartService->all())->sum('qty');

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success'   => true,
                'cartCount' => $cartCount,
                'message'   => "「{$product->name}」已加入購物車",
            ]);
        }

        return back()->with('success', "「{$product->name}」已加入購物車");
    }

    public function removeFromCart(Request $request)
    {
        $this->cartService->remove((int) $request->product_id);
        return back();
    }

    public function updateCart(Request $request)
    {
        $this->cartService->updateQuantities($request->qty ?? []);
        return back();
    }

    public function cart()
    {
        $cart  = $this->cartService->all();
        $total = $this->cartService->total();
        return view('shop.cart', compact('cart', 'total'));
    }

    public function checkout()
    {
        if ($this->cartService->isEmpty()) return redirect()->route('shop.cart');
        $cart  = $this->cartService->all();
        $total = $this->cartService->total();
        return view('shop.checkout', compact('cart', 'total'));
    }

    public function processCheckout(Request $request)
    {
        $request->validate([
            'name'    => 'required',
            'email'   => 'required|email',
            'phone'   => 'required',
            'address' => 'required',
        ], [
            'name.required'    => '請輸入姓名',
            'email.required'   => '請輸入 Email',
            'phone.required'   => '請輸入電話',
            'address.required' => '請輸入地址',
        ]);

        $orderId = 'RP' . date('Ymd') . rand(1000, 9999);
        session(['last_order_id' => $orderId]);
        $this->cartService->clear();
        return redirect()->route('shop.success');
    }

    public function success()
    {
        $orderId = session('last_order_no') ?? session('last_order_id') ?? 'RP' . date('Ymd') . '0000';
        return view('shop.success', compact('orderId'));
    }
}
