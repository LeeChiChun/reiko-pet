<?php

namespace App\Http\Controllers;

use App\Enums\{AppointmentStatus, AccommodationReservationStatus, ShopOrderStatus};
use App\Enums\UserRole;
use App\Models\{User, Service, AddonService, Store, Appointment, AccommodationReservation, Article, Product, ShopOrder, Announcement, Promotion, Flyer, SiteSetting, AccommodationRoom};
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct(private AuditLogService $audit) {}

    public function dashboard()
    {
        return view('admin.dashboard', [
            'totalUsers'         => User::where('role', UserRole::Customer)->count(),
            'totalAppointments'  => Appointment::count(),
            'pendingCount'       => Appointment::pending()->count(),
            'totalProducts'      => Product::count(),
            'recentAppointments' => Appointment::with(['customer', 'pet', 'service', 'store', 'groomer'])
                                        ->latest()->take(5)->get(),
        ]);
    }

    // ── 服務 ──
    public function servicesIndex()
    {
        return view('admin.services', ['services' => Service::latest()->get()]);
    }

    public function servicesStore(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required',
            'category'    => 'required',
            'price'       => 'required|numeric',
            'description' => 'nullable',
        ]);
        $service = Service::create($data);
        $this->audit->log('service.create', Service::class, $service->id, ['name' => $service->name]);
        return back()->with('success', '服務已新增');
    }

    public function servicesUpdate(Request $request, Service $service)
    {
        $data = $request->validate([
            'name'        => 'required',
            'category'    => 'required',
            'price'       => 'required|numeric',
            'description' => 'nullable',
            'is_active'   => 'boolean',
        ]);
        $service->update(array_merge($data, ['is_active' => $request->boolean('is_active')]));
        $this->audit->log('service.update', Service::class, $service->id, ['name' => $service->name]);
        return back()->with('success', '服務已更新');
    }

    public function servicesDestroy(Service $service)
    {
        $this->audit->log('service.delete', Service::class, $service->id, ['name' => $service->name]);
        $service->delete();
        return back()->with('success', '服務已刪除');
    }

    // ── 加值服務 ──
    public function addonsIndex()
    {
        return view('admin.addons', ['addons' => AddonService::latest()->get()]);
    }

    public function addonsStore(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required',
            'price'         => 'required|numeric',
            'applicable_to' => 'required|in:dog,cat,both',
            'description'   => 'nullable',
        ]);
        $addon = AddonService::create($data);
        $this->audit->log('addon.create', AddonService::class, $addon->id);
        return back()->with('success', '加值服務已新增');
    }

    public function addonsUpdate(Request $request, AddonService $addon)
    {
        $data = $request->validate([
            'name'          => 'required',
            'price'         => 'required|numeric',
            'applicable_to' => 'required|in:dog,cat,both',
            'description'   => 'nullable',
        ]);
        $addon->update(array_merge($data, ['is_active' => $request->boolean('is_active')]));
        $this->audit->log('addon.update', AddonService::class, $addon->id);
        return back()->with('success', '加值服務已更新');
    }

    public function addonsDestroy(AddonService $addon)
    {
        $this->audit->log('addon.delete', AddonService::class, $addon->id, ['name' => $addon->name]);
        $addon->delete();
        return back()->with('success', '已刪除');
    }

    // ── 門市 ──
    public function storesIndex()
    {
        return view('admin.stores', ['stores' => Store::latest()->get()]);
    }

    public function storesStore(Request $request)
    {
        $data = $request->validate([
            'name'           => 'required',
            'address'        => 'required',
            'phone'          => 'nullable',
            'business_hours' => 'nullable',
            'description'    => 'nullable',
        ]);
        $store = Store::create($data);
        $this->audit->log('store.create', Store::class, $store->id);
        return back()->with('success', '門市已新增');
    }

    public function storesUpdate(Request $request, Store $store)
    {
        $data = $request->validate([
            'name'           => 'required',
            'address'        => 'required',
            'phone'          => 'nullable',
            'business_hours' => 'nullable',
            'description'    => 'nullable',
        ]);
        $store->update(array_merge($data, ['is_active' => $request->boolean('is_active')]));
        $this->audit->log('store.update', Store::class, $store->id);
        return back()->with('success', '門市已更新');
    }

    public function storesDestroy(Store $store)
    {
        $this->audit->log('store.delete', Store::class, $store->id, ['name' => $store->name]);
        $store->delete();
        return back()->with('success', '已刪除');
    }

    // ── 會員 ──
    public function membersIndex()
    {
        $members = User::where('role', UserRole::Customer)
            ->withCount(['pets', 'appointments'])
            ->latest()
            ->paginate(20);
        return view('admin.members', compact('members'));
    }

    public function membersDestroy(Request $request, User $member)
    {
        abort_if($member->role !== UserRole::Customer, 422);
        $this->audit->log('member.delete', User::class, $member->id, ['email' => $member->email]);
        $member->delete();
        return back()->with('success', '會員已刪除');
    }

    // ── 預約 ──
    public function appointmentsIndex(Request $request)
    {
        $query = Appointment::with(['customer', 'pet', 'service', 'store', 'groomer'])->latest();

        $status = $request->query('status', 'all');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $appointments = $query->paginate(20)->withQueryString();
        $groomers = User::where('role', UserRole::Groomer)->get();
        return view('admin.appointments', compact('appointments', 'groomers', 'status'));
    }

    public function appointmentsUpdateStatus(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'status'     => 'required|in:pending,in_progress,completed,cancelled',
            'groomer_id' => 'nullable|exists:users,id',
        ]);
        $appointment->update(['status' => $data['status']]);
        if ($request->filled('groomer_id')) {
            $appointment->update(['groomer_id' => $data['groomer_id']]);
        }
        $this->audit->log('appointment.status', Appointment::class, $appointment->id, ['status' => $data['status']]);
        return back()->with('success', '預約狀態已更新');
    }

    // ── 文章 ──
    public function articlesIndex()
    {
        return view('admin.articles', ['articles' => Article::latest()->paginate(20)]);
    }

    public function articlesStore(Request $request)
    {
        $data = $request->validate([
            'title'    => 'required',
            'category' => 'required',
            'content'  => 'required',
        ]);
        $article = Article::create(array_merge($data, ['published_at' => now()]));
        $this->audit->log('article.create', Article::class, $article->id, ['title' => $article->title]);
        return back()->with('success', '文章已新增');
    }

    public function articlesUpdate(Request $request, Article $article)
    {
        $data = $request->validate([
            'title'    => 'required',
            'category' => 'required',
            'content'  => 'required',
        ]);
        $article->update($data);
        $this->audit->log('article.update', Article::class, $article->id, ['title' => $article->title]);
        return back()->with('success', '文章已更新');
    }

    public function articlesDestroy(Article $article)
    {
        $this->audit->log('article.delete', Article::class, $article->id, ['title' => $article->title]);
        $article->delete();
        return back()->with('success', '已刪除');
    }

    // ── 商品 ──
    public function productsIndex()
    {
        return view('admin.products', ['products' => Product::latest()->get()]);
    }

    public function productsStore(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required',
            'category'    => 'required',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'description' => 'nullable',
        ]);
        $product = Product::create($data);
        $this->audit->log('product.create', Product::class, $product->id);
        return back()->with('success', '商品已新增');
    }

    public function productsUpdate(Request $request, Product $product)
    {
        $data = $request->validate([
            'name'        => 'required',
            'category'    => 'required',
            'price'       => 'required|numeric',
            'stock'       => 'required|integer',
            'description' => 'nullable',
        ]);
        $product->update(array_merge($data, ['is_active' => $request->boolean('is_active')]));
        $this->audit->log('product.update', Product::class, $product->id);
        return back()->with('success', '商品已更新');
    }

    public function productsDestroy(Product $product)
    {
        $this->audit->log('product.delete', Product::class, $product->id, ['name' => $product->name]);
        $product->delete();
        return back()->with('success', '已刪除');
    }

    // ── 美容師帳號管理 ──
    public function groomersIndex()
    {
        $groomers = User::where('role', UserRole::Groomer)->with('store')->latest()->get();
        $stores   = Store::active()->get();
        return view('admin.groomers', compact('groomers', 'stores'));
    }

    public function groomersStore(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users',
            'phone'    => 'nullable',
            'store_id' => 'required|exists:stores,id',
            'password' => 'required|min:8',
        ]);
        $groomer = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'store_id' => $data['store_id'],
            'password' => Hash::make($data['password']),
            'role'     => UserRole::Groomer->value,
        ]);
        $this->audit->log('groomer.create', User::class, $groomer->id, ['email' => $groomer->email]);
        return back()->with('success', '美容師帳號已新增');
    }

    public function groomersUpdate(Request $request, User $groomer)
    {
        abort_if($groomer->role !== UserRole::Groomer, 422);
        $data = $request->validate([
            'name'     => 'required',
            'phone'    => 'nullable',
            'store_id' => 'nullable|exists:stores,id',
        ]);
        $groomer->update($data);
        $this->audit->log('groomer.update', User::class, $groomer->id);
        return back()->with('success', '美容師資料已更新');
    }

    public function groomersDestroy(User $groomer)
    {
        abort_if($groomer->role !== UserRole::Groomer, 422);
        $this->audit->log('groomer.delete', User::class, $groomer->id, ['email' => $groomer->email]);
        $groomer->delete();
        return back()->with('success', '已刪除');
    }

    // ── 公告管理 ──
    public function announcementsIndex()
    {
        return view('admin.announcements', ['announcements' => Announcement::orderByDesc('sort_order')->orderByDesc('published_at')->paginate(20)]);
    }

    public function announcementsStore(Request $request)
    {
        $data = $request->validate([
            'title'        => 'required',
            'tag'          => 'required',
            'content'      => 'nullable',
            'sort_order'   => 'integer',
            'published_at' => 'required|date',
        ]);
        $ann = Announcement::create(array_merge($data, ['is_active' => true]));
        $this->audit->log('announcement.create', Announcement::class, $ann->id);
        return back()->with('success', '公告已新增');
    }

    public function announcementsUpdate(Request $request, Announcement $announcement)
    {
        $data = $request->validate([
            'title'        => 'required',
            'tag'          => 'required',
            'content'      => 'nullable',
            'sort_order'   => 'integer',
            'published_at' => 'required|date',
        ]);
        $announcement->update(array_merge($data, ['is_active' => $request->boolean('is_active')]));
        $this->audit->log('announcement.update', Announcement::class, $announcement->id);
        return back()->with('success', '公告已更新');
    }

    public function announcementsDestroy(Announcement $announcement)
    {
        $this->audit->log('announcement.delete', Announcement::class, $announcement->id);
        $announcement->delete();
        return back()->with('success', '已刪除');
    }

    // ── 活動優惠管理 ──
    public function promotionsIndex()
    {
        $promotions = Promotion::with('coupon')->orderByDesc('sort_order')->paginate(20);
        $coupons    = \App\Models\Coupon::where('is_active', true)->orderBy('name')->get();
        return view('admin.promotions', compact('promotions', 'coupons'));
    }

    public function promotionsStore(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required',
            'badge'       => 'required',
            'period'      => 'nullable',
            'description' => 'nullable',
            'tag'         => 'nullable',
            'color'       => 'nullable',
            'link_url'    => 'nullable|url',
            'coupon_id'   => 'nullable|exists:coupons,id',
            'sort_order'  => 'integer',
        ]);
        $p = Promotion::create(array_merge($data, ['is_active' => true]));
        $this->audit->log('promotion.create', Promotion::class, $p->id);
        return back()->with('success', '活動已新增');
    }

    public function promotionsUpdate(Request $request, Promotion $promotion)
    {
        $data = $request->validate([
            'title'       => 'required',
            'badge'       => 'required',
            'period'      => 'nullable',
            'description' => 'nullable',
            'tag'         => 'nullable',
            'color'       => 'nullable',
            'link_url'    => 'nullable|url',
            'coupon_id'   => 'nullable|exists:coupons,id',
            'sort_order'  => 'integer',
        ]);
        $promotion->update(array_merge($data, ['is_active' => $request->boolean('is_active')]));
        $this->audit->log('promotion.update', Promotion::class, $promotion->id);
        return back()->with('success', '活動已更新');
    }

    public function promotionsDestroy(Promotion $promotion)
    {
        $this->audit->log('promotion.delete', Promotion::class, $promotion->id);
        $promotion->delete();
        return back()->with('success', '已刪除');
    }

    // ── DM 管理 ──
    public function flyersIndex()
    {
        return view('admin.flyers', ['flyers' => Flyer::orderByDesc('sort_order')->paginate(20)]);
    }

    public function flyersStore(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required',
            'period'      => 'nullable',
            'description' => 'nullable',
            'image'       => 'nullable|image|max:5120',
            'sort_order'  => 'integer',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('flyers', 'public');
        }

        $f = Flyer::create([
            'title'       => $data['title'],
            'period'      => $data['period'] ?? null,
            'description' => $data['description'] ?? null,
            'image_path'  => $imagePath,
            'sort_order'  => $data['sort_order'] ?? 0,
            'is_active'   => true,
        ]);
        $this->audit->log('flyer.create', Flyer::class, $f->id);
        return back()->with('success', 'DM 已新增');
    }

    public function flyersUpdate(Request $request, Flyer $flyer)
    {
        $data = $request->validate([
            'title'       => 'required',
            'period'      => 'nullable',
            'description' => 'nullable',
            'image'       => 'nullable|image|max:5120',
            'sort_order'  => 'integer',
        ]);

        if ($request->hasFile('image')) {
            if ($flyer->image_path) Storage::disk('public')->delete($flyer->image_path);
            $data['image_path'] = $request->file('image')->store('flyers', 'public');
        }

        $flyer->update([
            'title'       => $data['title'],
            'period'      => $data['period'] ?? null,
            'description' => $data['description'] ?? null,
            'sort_order'  => $data['sort_order'] ?? 0,
            'image_path'  => isset($data['image_path']) ? $data['image_path'] : $flyer->image_path,
            'is_active'   => $request->boolean('is_active'),
        ]);
        $this->audit->log('flyer.update', Flyer::class, $flyer->id);
        return back()->with('success', 'DM 已更新');
    }

    public function flyersDestroy(Flyer $flyer)
    {
        if ($flyer->image_path) Storage::disk('public')->delete($flyer->image_path);
        $this->audit->log('flyer.delete', Flyer::class, $flyer->id);
        $flyer->delete();
        return back()->with('success', '已刪除');
    }

    // ── 內容管理（CMS）──
    public function cmsIndex()
    {
        $settings = SiteSetting::all()->pluck('value', 'key')->toArray();
        return view('admin.cms', compact('settings'));
    }

    public function accommodationCmsIndex()
    {
        $settings = SiteSetting::all()->pluck('value', 'key')->toArray();
        return view('admin.accommodation_cms', compact('settings'));
    }

    public function accommodationCmsUpdate(Request $request)
    {
        $definitions = SiteSetting::all();

        foreach ($definitions as $setting) {
            if ($setting->type === 'image') {
                if ($request->hasFile('setting_' . $setting->key)) {
                    $path = $request->file('setting_' . $setting->key)->store('cms', 'public');
                    SiteSetting::set($setting->key, $path);
                }
            } else {
                if ($request->has('setting_' . $setting->key)) {
                    SiteSetting::set($setting->key, $request->input('setting_' . $setting->key));
                }
            }
        }

        $this->audit->log('accommodation.cms.update', SiteSetting::class, null);
        return back()->with('success', '住宿頁面內容已更新');
    }

    public function aboutIndex()
    {
        $settings = SiteSetting::all()->pluck('value', 'key')->toArray();
        return view('admin.about', compact('settings'));
    }

    public function aboutUpdate(Request $request)
    {
        $definitions = SiteSetting::all();

        foreach ($definitions as $setting) {
            if ($setting->type === 'image') {
                if ($request->hasFile('setting_' . $setting->key)) {
                    $path = $request->file('setting_' . $setting->key)->store('cms', 'public');
                    SiteSetting::set($setting->key, $path);
                }
            } else {
                if ($request->has('setting_' . $setting->key)) {
                    SiteSetting::set($setting->key, $request->input('setting_' . $setting->key));
                }
            }
        }

        $this->audit->log('about.update', SiteSetting::class, null);
        return back()->with('success', '關於我們內容已更新');
    }

    public function cmsUpdate(Request $request)
    {
        $definitions = SiteSetting::all();

        foreach ($definitions as $setting) {
            if ($setting->type === 'image') {
                if ($request->hasFile('setting_' . $setting->key)) {
                    $path = $request->file('setting_' . $setting->key)->store('cms', 'public');
                    SiteSetting::set($setting->key, $path);
                }
            } else {
                if ($request->has('setting_' . $setting->key)) {
                    SiteSetting::set($setting->key, $request->input('setting_' . $setting->key));
                }
            }
        }

        $this->audit->log('cms.update', SiteSetting::class, null, ['keys' => $definitions->pluck('key')]);
        return back()->with('success', '內容已更新');
    }

    // ── Toggle 啟用狀態 ──
    public function servicesToggle(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);
        return back()->with('success', $service->is_active ? '服務已啟用' : '服務已停用');
    }

    public function addonsToggle(AddonService $addon)
    {
        $addon->update(['is_active' => !$addon->is_active]);
        return back()->with('success', $addon->is_active ? '加值服務已啟用' : '加值服務已停用');
    }

    public function storesToggle(Store $store)
    {
        $store->update(['is_active' => !$store->is_active]);
        return back()->with('success', $store->is_active ? '門市已啟用' : '門市已停用');
    }

    public function productsToggle(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return back()->with('success', $product->is_active ? '商品已上架' : '商品已下架');
    }

    public function announcementsToggle(Announcement $announcement)
    {
        $announcement->update(['is_active' => !$announcement->is_active]);
        return back()->with('success', $announcement->is_active ? '公告已上架' : '公告已下架');
    }

    public function promotionsToggle(Promotion $promotion)
    {
        $promotion->update(['is_active' => !$promotion->is_active]);
        return back()->with('success', $promotion->is_active ? '優惠已上架' : '優惠已下架');
    }

    // ── 住宿房型管理 ──
    public function accommodationRooms()
    {
        $rooms = AccommodationRoom::ordered()->get();
        return view('admin.accommodation_rooms', compact('rooms'));
    }

    public function accommodationRoomsStore(Request $request)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:50',
            'slug'            => 'required|string|max:50|unique:accommodation_rooms,slug',
            'price_per_night' => 'required|integer|min:0',
            'max_weight'      => 'nullable|integer|min:1',
            'description'     => 'nullable|string',
            'features'        => 'nullable|string',
            'image'           => 'nullable|image|max:4096',
            'sort_order'      => 'nullable|integer|min:0',
            'is_active'       => 'nullable',
        ]);

        $data['features']  = $this->parseFeatures($request->input('features', ''));
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $request->input('sort_order', 0);

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('accommodation-rooms', 'public');
        }
        unset($data['image']);

        AccommodationRoom::create($data);
        return back()->with('success', '房型已新增');
    }

    public function accommodationRoomsUpdate(Request $request, AccommodationRoom $room)
    {
        $data = $request->validate([
            'name'            => 'required|string|max:50',
            'price_per_night' => 'required|integer|min:0',
            'max_weight'      => 'nullable|integer|min:1',
            'description'     => 'nullable|string',
            'features'        => 'nullable|string',
            'image'           => 'nullable|image|max:4096',
            'sort_order'      => 'nullable|integer|min:0',
            'is_active'       => 'nullable',
        ]);

        $data['features']  = $this->parseFeatures($request->input('features', ''));
        $data['is_active'] = $request->boolean('is_active');
        $data['sort_order'] = $request->input('sort_order', $room->sort_order);

        if ($request->hasFile('image')) {
            if ($room->image_path) Storage::disk('public')->delete($room->image_path);
            $data['image_path'] = $request->file('image')->store('accommodation-rooms', 'public');
        }
        unset($data['image']);

        $room->update($data);
        return back()->with('success', '房型已更新');
    }

    public function accommodationRoomsToggle(AccommodationRoom $room)
    {
        $room->update(['is_active' => !$room->is_active]);
        return back()->with('success', $room->is_active ? '房型已啟用' : '房型已停用');
    }

    public function accommodationRoomsDestroy(AccommodationRoom $room)
    {
        if ($room->image_path) Storage::disk('public')->delete($room->image_path);
        $room->delete();
        return back()->with('success', '房型已刪除');
    }

    private function parseFeatures(string $raw): array
    {
        return array_values(array_filter(array_map('trim', explode("\n", $raw))));
    }

    // ── 住宿預約管理 ──
    public function accommodationBookings()
    {
        $status = request('status', 'all');
        $query  = AccommodationReservation::with(['room'])->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $reservations = $query->paginate(20)->withQueryString();
        $statusMap    = collect(AccommodationReservationStatus::cases())
            ->mapWithKeys(fn($s) => [$s->value => $s->label()]);

        return view('admin.accommodation_bookings', compact('reservations', 'statusMap', 'status'));
    }

    public function accommodationBookingsUpdateStatus(Request $request, AccommodationReservation $reservation)
    {
        $request->validate([
            'status' => ['required', \Illuminate\Validation\Rules\Enum::class(AccommodationReservationStatus::class)],
        ]);
        $reservation->update(['status' => $request->status]);
        return back()->with('success', '狀態已更新');
    }

    // ── 訂單管理 ──
    public function ordersIndex()
    {
        $status = request('status', 'all');
        $query  = ShopOrder::with('user')->latest();

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $orders    = $query->paginate(20)->withQueryString();
        $statusMap = collect(ShopOrderStatus::cases())
            ->mapWithKeys(fn($s) => [$s->value => $s->label()]);

        return view('admin.orders', compact('orders', 'statusMap', 'status'));
    }

    public function ordersUpdateStatus(Request $request, ShopOrder $order)
    {
        $request->validate([
            'status' => ['required', \Illuminate\Validation\Rules\Enum::class(ShopOrderStatus::class)],
        ]);
        $order->update(['status' => $request->status]);
        return back()->with('success', '訂單狀態已更新');
    }
}
