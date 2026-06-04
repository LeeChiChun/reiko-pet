<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    ServiceController,
    ShopController,
    ArticleController,
    AboutController,
    BookingController,
    AuthController,
    MemberController,
    AdminController,
    GroomerController,
    TwoFactorController,
    PasswordConfirmController,
    EmailVerifyController,
    AnnouncementController,
    PromotionController,
    FlyerController,
    AccommodationController,
    CheckoutController,
    SurveyController,
    CouponController,
};

// ── 公開頁面 ──────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/services', [ServiceController::class, 'index'])->name('services');
Route::get('/about',         [AboutController::class, 'index'])->name('about');
Route::get('/accommodation',         [AccommodationController::class, 'index'])->name('accommodation.index');
Route::post('/accommodation/book',   [AccommodationController::class, 'book'])->name('accommodation.book');
Route::get('/accommodation/success', [AccommodationController::class, 'success'])->name('accommodation.success');

// ── 線上商城 ──────────────────────────────────────────────
Route::prefix('shop')->name('shop.')->group(function () {
    Route::get('/',                         [ShopController::class, 'index'])->name('index');
    Route::get('/cart',                     [ShopController::class, 'cart'])->name('cart');
    Route::post('/cart/add/{product}',      [ShopController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/remove',             [ShopController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/update',             [ShopController::class, 'updateCart'])->name('cart.update');
    Route::get('/checkout',                 [ShopController::class, 'checkout'])->name('checkout');
    Route::post('/checkout',                [ShopController::class, 'processCheckout'])->name('checkout.process');
    Route::get('/success',                  [ShopController::class, 'success'])->name('success');
    Route::post('/{product}/bookmark',      [ShopController::class, 'toggleBookmark'])->name('bookmark')->middleware('auth');
    Route::get('/{product}',               [ShopController::class, 'show'])->name('show');
});

// ── 寵物專欄 ──────────────────────────────────────────────
Route::prefix('articles')->name('articles.')->group(function () {
    Route::get('/',                         [ArticleController::class, 'index'])->name('index');
    Route::get('/{article}',               [ArticleController::class, 'show'])->name('show');
    Route::post('/{article}/bookmark',     [ArticleController::class, 'toggleBookmark'])->name('bookmark')->middleware('auth');
});

// ── 最新公告 ──────────────────────────────────────────────
Route::prefix('announcements')->name('announcements.')->group(function () {
    Route::get('/',                [AnnouncementController::class, 'index'])->name('index');
    Route::get('/{announcement}',  [AnnouncementController::class, 'show'])->name('show');
});

// ── 活動優惠 ──────────────────────────────────────────────
Route::get('/promotions',        [PromotionController::class, 'index'])->name('promotions.index');
Route::get('/promotions/{promotion}', [PromotionController::class, 'show'])->name('promotions.show');

// ── 共用結帳 ──────────────────────────────────────────────
Route::get('/checkout',                    [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout/pay',               [CheckoutController::class, 'pay'])->name('checkout.pay');
Route::post('/checkout/from-cart',         [CheckoutController::class, 'fromCart'])->name('checkout.from-cart');
Route::post('/checkout/validate-coupon',   [CouponController::class, 'validate'])->name('checkout.validate-coupon');

// ── 滿意度問卷 ─────────────────────────────────────────────
Route::post('/survey/submit', [SurveyController::class, 'submit'])->name('survey.submit');

// ── DM 傳單 ───────────────────────────────────────────────
Route::prefix('flyers')->name('flyers.')->group(function () {
    Route::get('/',                     [FlyerController::class, 'index'])->name('index');
    Route::get('/{flyer}/download',     [FlyerController::class, 'download'])->name('download');
});

// ── 認證 ──────────────────────────────────────────────────
Route::get('/login',    [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',   [AuthController::class, 'login'])->middleware('throttle:login');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register',[AuthController::class, 'register']);
Route::post('/logout',  [AuthController::class, 'logout'])->name('logout');

// ── 密碼再確認（敏感操作前） ──────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/password/confirm',  [PasswordConfirmController::class, 'show'])->name('password.confirm');
    Route::post('/password/confirm', [PasswordConfirmController::class, 'confirm'])->name('password.confirm.submit');
});

// ── 2FA 驗證（Admin/Groomer 登入後 Email OTP）────────────
Route::middleware('auth')->prefix('2fa')->name('2fa.')->group(function () {
    Route::get('/verify',  [TwoFactorController::class, 'showVerify'])->name('verify');
    Route::post('/verify', [TwoFactorController::class, 'verify'])->name('verify.submit');
});

// ── 顧客信箱驗證（註冊後）────────────────────────────────
Route::middleware('auth')->prefix('email')->name('email.')->group(function () {
    Route::get('/verify',  [EmailVerifyController::class, 'show'])->name('verify');
    Route::post('/verify', [EmailVerifyController::class, 'verify'])->name('verify.submit');
    Route::post('/resend', [EmailVerifyController::class, 'resend'])->name('verify.resend');
});

// ── 預約（需登入）─────────────────────────────────────────
Route::middleware('auth')->prefix('booking')->name('booking.')->group(function () {
    Route::get('/',        [BookingController::class, 'step1'])->name('step1');
    Route::post('/step1',  [BookingController::class, 'saveStep1'])->name('save1');
    Route::get('/step2',   [BookingController::class, 'step2'])->name('step2');
    Route::post('/step2',  [BookingController::class, 'saveStep2'])->name('save2');
    Route::get('/step3',   [BookingController::class, 'step3'])->name('step3');
    Route::post('/step3',  [BookingController::class, 'saveStep3'])->name('save3');
    Route::get('/step4',   [BookingController::class, 'step4'])->name('step4');
    Route::post('/step4',  [BookingController::class, 'saveStep4'])->name('save4');
    Route::get('/step5',   [BookingController::class, 'step5'])->name('step5');
    Route::post('/step5',  [BookingController::class, 'saveStep5'])->name('save5');
    Route::get('/confirm', [BookingController::class, 'confirm'])->name('confirm');
});

// ── 會員中心（需登入）────────────────────────────────────
Route::middleware('auth')->prefix('member')->name('member.')->group(function () {
    Route::get('/',                                    [MemberController::class, 'index'])->name('index');
    Route::get('/profile',                             [MemberController::class, 'profile'])->name('profile');
    Route::put('/profile',                             [MemberController::class, 'updateProfile'])->name('profile.update');
    Route::get('/pets',                                [MemberController::class, 'pets'])->name('pets');
    Route::post('/pets',                               [MemberController::class, 'storePet'])->name('pets.store');
    Route::put('/pets/{pet}',                          [MemberController::class, 'updatePet'])->name('pets.update');
    Route::delete('/pets/{pet}',                       [MemberController::class, 'destroyPet'])->name('pets.destroy');
    Route::get('/appointments',                        [MemberController::class, 'appointments'])->name('appointments');
    Route::post('/appointments/{appointment}/cancel',  [MemberController::class, 'cancelAppointment'])->name('appointments.cancel');
    // 住宿預約紀錄
    Route::get('/accommodation',                       [MemberController::class, 'accommodation'])->name('accommodation');
    Route::post('/accommodation/{reservation}/cancel', [MemberController::class, 'cancelAccommodation'])->name('accommodation.cancel');
    // 商城訂單
    Route::get('/orders',                              [MemberController::class, 'orders'])->name('orders');
    // 收藏文章
    Route::get('/bookmarks',                                [MemberController::class, 'bookmarks'])->name('bookmarks');
    Route::delete('/bookmarks/{bookmark}',                  [MemberController::class, 'removeBookmark'])->name('bookmarks.remove');
    // 收藏商品
    Route::get('/product-bookmarks',                        [MemberController::class, 'productBookmarks'])->name('product-bookmarks');
    Route::delete('/product-bookmarks/{bookmark}',          [MemberController::class, 'removeProductBookmark'])->name('product-bookmarks.remove');
});

// ── 美容師面板（需登入 + groomer/admin role + 2FA）────────
Route::middleware(['auth', 'groomer', '2fa'])->prefix('groomer')->name('groomer.')->group(function () {
    Route::get('/schedule',                              [GroomerController::class, 'schedule'])->name('schedule');
    Route::put('/appointments/{appointment}/status',     [GroomerController::class, 'updateStatus'])->name('status');
});

// ── 管理員後台（需登入 + admin role + 2FA）─────────────────
Route::middleware(['auth', 'admin', '2fa'])->prefix('manage-panel')->name('admin.')->group(function () {
    Route::get('/',                                          [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/services',                                  [AdminController::class, 'servicesIndex'])->name('services');
    Route::post('/services',                                 [AdminController::class, 'servicesStore'])->name('services.store');
    Route::put('/services/{service}',                        [AdminController::class, 'servicesUpdate'])->name('services.update');
    Route::patch('/services/{service}/toggle',               [AdminController::class, 'servicesToggle'])->name('services.toggle');
    Route::delete('/services/{service}',                     [AdminController::class, 'servicesDestroy'])->name('services.destroy');
    Route::get('/addons',                                    [AdminController::class, 'addonsIndex'])->name('addons');
    Route::post('/addons',                                   [AdminController::class, 'addonsStore'])->name('addons.store');
    Route::put('/addons/{addon}',                            [AdminController::class, 'addonsUpdate'])->name('addons.update');
    Route::patch('/addons/{addon}/toggle',                   [AdminController::class, 'addonsToggle'])->name('addons.toggle');
    Route::delete('/addons/{addon}',                         [AdminController::class, 'addonsDestroy'])->name('addons.destroy');
    Route::get('/stores',                                    [AdminController::class, 'storesIndex'])->name('stores');
    Route::post('/stores',                                   [AdminController::class, 'storesStore'])->name('stores.store');
    Route::put('/stores/{store}',                            [AdminController::class, 'storesUpdate'])->name('stores.update');
    Route::patch('/stores/{store}/toggle',                   [AdminController::class, 'storesToggle'])->name('stores.toggle');
    Route::delete('/stores/{store}',                         [AdminController::class, 'storesDestroy'])->name('stores.destroy');
    Route::get('/members',                                   [AdminController::class, 'membersIndex'])->name('members');
    Route::delete('/members/{member}',                       [AdminController::class, 'membersDestroy'])->name('members.destroy');
    Route::get('/appointments',                              [AdminController::class, 'appointmentsIndex'])->name('appointments');
    Route::put('/appointments/{appointment}/status',         [AdminController::class, 'appointmentsUpdateStatus'])->name('appointments.status');
    Route::get('/articles',                                  [AdminController::class, 'articlesIndex'])->name('articles');
    Route::post('/articles',                                 [AdminController::class, 'articlesStore'])->name('articles.store');
    Route::put('/articles/{article}',                        [AdminController::class, 'articlesUpdate'])->name('articles.update');
    Route::delete('/articles/{article}',                     [AdminController::class, 'articlesDestroy'])->name('articles.destroy');
    Route::get('/products',                                  [AdminController::class, 'productsIndex'])->name('products');
    Route::post('/products',                                 [AdminController::class, 'productsStore'])->name('products.store');
    Route::put('/products/{product}',                        [AdminController::class, 'productsUpdate'])->name('products.update');
    Route::patch('/products/{product}/toggle',               [AdminController::class, 'productsToggle'])->name('products.toggle');
    Route::delete('/products/{product}',                     [AdminController::class, 'productsDestroy'])->name('products.destroy');
    Route::get('/groomers',                                  [AdminController::class, 'groomersIndex'])->name('groomers');
    Route::post('/groomers',                                 [AdminController::class, 'groomersStore'])->name('groomers.store');
    Route::put('/groomers/{groomer}',                        [AdminController::class, 'groomersUpdate'])->name('groomers.update');
    Route::delete('/groomers/{groomer}',                     [AdminController::class, 'groomersDestroy'])->name('groomers.destroy');
    // 最新公告管理
    Route::get('/announcements',                             [AdminController::class, 'announcementsIndex'])->name('announcements');
    Route::post('/announcements',                            [AdminController::class, 'announcementsStore'])->name('announcements.store');
    Route::put('/announcements/{announcement}',              [AdminController::class, 'announcementsUpdate'])->name('announcements.update');
    Route::patch('/announcements/{announcement}/toggle',     [AdminController::class, 'announcementsToggle'])->name('announcements.toggle');
    Route::delete('/announcements/{announcement}',           [AdminController::class, 'announcementsDestroy'])->name('announcements.destroy');
    // 活動優惠管理
    Route::get('/promotions',                                [AdminController::class, 'promotionsIndex'])->name('promotions');
    Route::post('/promotions',                               [AdminController::class, 'promotionsStore'])->name('promotions.store');
    Route::put('/promotions/{promotion}',                    [AdminController::class, 'promotionsUpdate'])->name('promotions.update');
    Route::patch('/promotions/{promotion}/toggle',           [AdminController::class, 'promotionsToggle'])->name('promotions.toggle');
    Route::delete('/promotions/{promotion}',                 [AdminController::class, 'promotionsDestroy'])->name('promotions.destroy');
    // DM 傳單管理
    Route::get('/flyers',                                    [AdminController::class, 'flyersIndex'])->name('flyers');
    Route::post('/flyers',                                   [AdminController::class, 'flyersStore'])->name('flyers.store');
    Route::put('/flyers/{flyer}',                            [AdminController::class, 'flyersUpdate'])->name('flyers.update');
    Route::delete('/flyers/{flyer}',                         [AdminController::class, 'flyersDestroy'])->name('flyers.destroy');
    // 內容管理（CMS）
    Route::get('/cms',                                       [AdminController::class, 'cmsIndex'])->name('cms');
    Route::post('/cms',                                      [AdminController::class, 'cmsUpdate'])->name('cms.update');
    // 關於我們管理
    Route::get('/about',                                     [AdminController::class, 'aboutIndex'])->name('about');
    Route::post('/about',                                    [AdminController::class, 'aboutUpdate'])->name('about.update');
    // 滿意度問卷管理
    Route::get('/survey',                                    [SurveyController::class, 'adminIndex'])->name('survey');
    Route::post('/survey',                                   [SurveyController::class, 'adminStore'])->name('survey.store');
    Route::put('/survey/{question}',                         [SurveyController::class, 'adminUpdate'])->name('survey.update');
    Route::delete('/survey/{question}',                      [SurveyController::class, 'adminDestroy'])->name('survey.destroy');
    Route::get('/survey/responses',                          [SurveyController::class, 'adminResponses'])->name('survey.responses');
    // 優惠券管理
    Route::get('/coupons',                                   [CouponController::class, 'adminIndex'])->name('coupons');
    Route::post('/coupons',                                  [CouponController::class, 'adminStore'])->name('coupons.store');
    Route::put('/coupons/{coupon}',                          [CouponController::class, 'adminUpdate'])->name('coupons.update');
    Route::delete('/coupons/{coupon}',                       [CouponController::class, 'adminDestroy'])->name('coupons.destroy');
    // 住宿內容管理（CMS）
    Route::get('/accommodation-cms',                         [AdminController::class, 'accommodationCmsIndex'])->name('accommodation.cms');
    Route::post('/accommodation-cms',                        [AdminController::class, 'accommodationCmsUpdate'])->name('accommodation.cms.update');
    // 住宿房型管理
    Route::get('/accommodation-rooms',                       [AdminController::class, 'accommodationRooms'])->name('accommodation.rooms');
    Route::post('/accommodation-rooms',                      [AdminController::class, 'accommodationRoomsStore'])->name('accommodation.rooms.store');
    Route::put('/accommodation-rooms/{room}',                [AdminController::class, 'accommodationRoomsUpdate'])->name('accommodation.rooms.update');
    Route::patch('/accommodation-rooms/{room}/toggle',       [AdminController::class, 'accommodationRoomsToggle'])->name('accommodation.rooms.toggle');
    Route::delete('/accommodation-rooms/{room}',             [AdminController::class, 'accommodationRoomsDestroy'])->name('accommodation.rooms.destroy');
    // 住宿預約管理
    Route::get('/accommodation-bookings',                    [AdminController::class, 'accommodationBookings'])->name('accommodation.bookings');
    Route::put('/accommodation-bookings/{reservation}/status',[AdminController::class, 'accommodationBookingsUpdateStatus'])->name('accommodation.bookings.status');
    // 訂單管理
    Route::get('/orders',                                    [AdminController::class, 'ordersIndex'])->name('orders');
    Route::put('/orders/{order}/status',                     [AdminController::class, 'ordersUpdateStatus'])->name('orders.status');
});
