<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Contracts\AppointmentServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GroomerController extends Controller
{
    public function __construct(private AppointmentServiceInterface $appointmentService) {}

    public function schedule()
    {
        $groomer = Auth::user();
        $date    = request('date') ? Carbon::parse(request('date')) : Carbon::today();

        $query = Appointment::with(['customer', 'pet', 'service', 'store', 'addons.addonService'])
            ->whereDate('appointment_at', $date)
            ->orderBy('appointment_at');

        // Admin sees all stores; groomer only sees their own store
        if ($groomer->isGroomer() && $groomer->store_id) {
            $query->where('store_id', $groomer->store_id);
        }

        $appointments = $query->get();

        return view('groomer.schedule', compact('appointments', 'date', 'groomer'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $groomer = Auth::user();

        // Groomer can only update appointments in their store
        if ($groomer->isGroomer() && $groomer->store_id) {
            abort_if($appointment->store_id !== $groomer->store_id, 403, '無權操作此預約');
        }

        $data = $request->validate([
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'note'   => 'nullable|string|max:500',
        ]);

        $this->appointmentService->updateStatus(
            $appointment,
            $data['status'],
            $groomer->id,
            $request->filled('note') ? $data['note'] : null
        );

        return back()->with('success', '狀態已更新');
    }
}
