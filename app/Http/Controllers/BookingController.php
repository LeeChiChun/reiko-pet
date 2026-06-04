<?php

namespace App\Http\Controllers;

use App\Models\{Pet, Service, AddonService, Store, Appointment};
use App\Contracts\BookingServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function __construct(private BookingServiceInterface $bookingService) {}

    public function step1()
    {
        $pets = Auth::user()->pets()->get();
        return view('booking.step1', compact('pets'));
    }

    public function saveStep1(Request $request)
    {
        $request->validate(['pet_id' => 'required|exists:pets,id']);
        $pet = Pet::where('id', $request->pet_id)->where('user_id', Auth::id())->firstOrFail();
        session([config('booking.session_key') => [
            'pet_id'   => $pet->id,
            'pet_name' => $pet->name,
            'pet_type' => $pet->type,
        ]]);
        return redirect()->route('booking.step2');
    }

    public function step2()
    {
        if (!session(config('booking.session_key') . '.pet_id')) {
            return redirect()->route('booking.step1');
        }
        $singles   = Service::active()->where('category', 'single')->get();
        $smallPkgs = Service::active()->where('category', 'small_pkg')->get();
        $largePkgs = Service::active()->where('category', 'large_pkg')->get();
        $dogSvcs   = Service::active()->where('category', 'dog')->get();
        $catSvcs   = Service::active()->where('category', 'cat')->get();
        $booking   = session(config('booking.session_key'));
        return view('booking.step2', compact('singles', 'smallPkgs', 'largePkgs', 'dogSvcs', 'catSvcs', 'booking'));
    }

    public function saveStep2(Request $request)
    {
        $request->validate(['service_id' => 'required|exists:services,id']);
        $service = Service::findOrFail($request->service_id);
        $booking = session(config('booking.session_key'), []);
        $booking['service_id']    = $service->id;
        $booking['service_name']  = $service->name;
        $booking['service_price'] = $service->price;
        session([config('booking.session_key') => $booking]);
        return redirect()->route('booking.step3');
    }

    public function step3()
    {
        $booking = session(config('booking.session_key'));
        if (!isset($booking['service_id'])) return redirect()->route('booking.step2');
        $petType = $booking['pet_type'];
        $addons = AddonService::where('is_active', true)
            ->where(fn($q) => $q->where('applicable_to', 'both')->orWhere('applicable_to', $petType))
            ->get();
        return view('booking.step3', compact('addons', 'booking'));
    }

    public function saveStep3(Request $request)
    {
        $booking              = session(config('booking.session_key'), []);
        $addonIds             = $request->addon_ids ?? [];
        $addonTotal           = AddonService::whereIn('id', $addonIds)->sum('price');
        $booking['addon_ids']   = $addonIds;
        $booking['addon_total'] = $addonTotal;
        session([config('booking.session_key') => $booking]);
        return redirect()->route('booking.step4');
    }

    public function step4()
    {
        $booking = session(config('booking.session_key'));
        if (!isset($booking['pet_id'])) return redirect()->route('booking.step1');
        $stores  = Store::active()->get();
        $minDate = Carbon::tomorrow()->format('Y-m-d');
        $maxDate = Carbon::now()->addMonths(config('booking.advance_months_max'))->format('Y-m-d');
        return view('booking.step4', compact('stores', 'booking', 'minDate', 'maxDate'));
    }

    public function saveStep4(Request $request)
    {
        $request->validate([
            'store_id' => 'required|exists:stores,id',
            'date'     => 'required|date|after:today',
            'time'     => 'required',
        ], [
            'store_id.required' => '請選擇門市',
            'date.required'     => '請選擇日期',
            'date.after'        => '日期必須是明天以後',
            'time.required'     => '請選擇時段',
        ]);

        $store   = Store::findOrFail($request->store_id);
        $booking = session(config('booking.session_key'), []);
        $booking['store_id']       = $store->id;
        $booking['store_name']     = $store->name;
        $booking['appointment_at'] = $request->date . ' ' . $request->time . ':00';
        $booking['total_price']    = $this->bookingService->calculateTotal($booking);
        session([config('booking.session_key') => $booking]);
        return redirect()->route('booking.step5');
    }

    public function step5()
    {
        $booking = session(config('booking.session_key'));
        if (!isset($booking['store_id'])) return redirect()->route('booking.step4');
        return view('booking.step5', compact('booking'));
    }

    public function saveStep5(Request $request)
    {
        $booking = session(config('booking.session_key'));
        if (!isset($booking['pet_id'])) return redirect()->route('booking.step1');

        if ($this->bookingService->isSlotFull($booking['store_id'], $booking['appointment_at'])) {
            return redirect()->route('booking.step4')
                ->withErrors(['time' => '此時段已額滿，請選擇其他時間']);
        }

        $items = [['name' => $booking['service_name'], 'qty' => 1, 'amount' => $booking['service_price']]];
        if (!empty($booking['addon_ids'])) {
            \App\Models\AddonService::whereIn('id', $booking['addon_ids'])->each(
                fn($a) => $items[] = ['name' => $a->name, 'qty' => 1, 'amount' => $a->price]
            );
        }

        session(['checkout_order' => [
            'source'   => 'grooming',
            'title'    => '美容預約',
            'items'    => $items,
            'total'    => $booking['total_price'],
            'meta'     => $booking,
            'redirect' => route('booking.confirm'),
        ]]);

        return redirect()->route('checkout.show');
    }

    public function confirm()
    {
        $appointmentId = session(config('booking.last_appointment_key'));
        if (!$appointmentId) return redirect()->route('home');
        $appointment = Appointment::with(['pet', 'service', 'store', 'addons.addonService'])
            ->findOrFail($appointmentId);
        return view('booking.confirm', compact('appointment'));
    }
}
