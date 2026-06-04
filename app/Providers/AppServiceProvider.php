<?php

namespace App\Providers;

use App\Contracts\AppointmentServiceInterface;
use App\Contracts\BookingMailNotifiable;
use App\Contracts\BookingServiceInterface;
use App\Contracts\BookingSmsNotifiable;
use App\Contracts\CloudStorageInterface;
use App\Contracts\CouponServiceInterface;
use App\Contracts\OtpSmsNotifiable;
use App\Contracts\PaymentInterface;
use App\Contracts\RefundInterface;
use App\Contracts\Repositories\AppointmentRepositoryInterface;
use App\Contracts\Repositories\CouponRepositoryInterface;
use App\Contracts\StatusMailNotifiable;
use App\Repositories\EloquentAppointmentRepository;
use App\Repositories\EloquentCouponRepository;
use App\Services\AppointmentService;
use App\Services\BookingService;
use App\Services\CartService;
use App\Services\CouponService;
use App\Services\Mock\MockCloudStorage;
use App\Services\Mock\MockMailNotification;
use App\Services\Mock\MockPayment;
use App\Services\Mock\MockSmsNotification;
use App\Helpers\MaskHelper;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Session\Store as Session;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(CartService::class, fn($app) => new CartService($app->make(Session::class)));

        // Repositories（Eloquent 實作）
        $this->app->bind(AppointmentRepositoryInterface::class, EloquentAppointmentRepository::class);
        $this->app->bind(CouponRepositoryInterface::class,      EloquentCouponRepository::class);

        // Services（綁定介面到具體實作）
        $this->app->bind(BookingServiceInterface::class,     BookingService::class);
        $this->app->bind(CouponServiceInterface::class,      CouponService::class);
        $this->app->bind(AppointmentServiceInterface::class, AppointmentService::class);

        // Payment
        $this->app->bind(PaymentInterface::class, MockPayment::class);
        $this->app->bind(RefundInterface::class,  MockPayment::class);

        // Mail notifications（顧客端 vs 管理端分開注入）
        $this->app->bind(BookingMailNotifiable::class, MockMailNotification::class);
        $this->app->bind(StatusMailNotifiable::class,  MockMailNotification::class);

        // SMS notifications（預約提醒 vs OTP 分開注入）
        $this->app->bind(BookingSmsNotifiable::class, MockSmsNotification::class);
        $this->app->bind(OtpSmsNotifiable::class,     MockSmsNotification::class);

        $this->app->bind(CloudStorageInterface::class, MockCloudStorage::class);
    }

    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinutes(15, 5)->by($request->input('email') . '|' . $request->ip());
        });

        Blade::directive('maskPhone', fn($phone) => "<?php echo \App\Helpers\MaskHelper::phone($phone); ?>");
        Blade::directive('maskEmail', fn($email) => "<?php echo \App\Helpers\MaskHelper::email($email); ?>");
    }
}
