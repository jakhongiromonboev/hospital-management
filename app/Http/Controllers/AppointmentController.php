<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        $appointments = auth()->user()->patientAppointments()
            ->with('doctor')
            ->latest('appointment_date')
            ->paginate(10);

        return view('appointments.index', compact('appointments'));
    }

    public function create()
    {
        $doctors = User::where('role', 'doctor')
            ->with('schedules')
            ->get();

        return view('appointments.create', compact('doctors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'reason' => 'required|string|max:500',
        ]);

        $doctor = User::findOrFail($request->doctor_id);
        $dayOfWeek = strtolower(date('l', strtotime($request->appointment_date)));

        $schedule = $doctor->schedules()
            ->where('day_of_week', $dayOfWeek)
            ->where('status', true)
            ->first();

        if (!$schedule) {
            return back()->with('error', '해당 요일에는 의사 진료가 없습니다.')->withInput();
        }

        $appointmentCount = Appointment::where('doctor_id', $request->doctor_id)
            ->whereDate('appointment_date', $request->appointment_date)
            ->whereNotIn('status', ['cancelled'])
            ->count();

        if ($appointmentCount >= $schedule->max_patients) {
            return back()->with('error', '해당 날짜의 예약 가능 인원이 찼습니다.')->withInput();
        }

        Appointment::create([
            'patient_id' => auth()->id(),
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('patient.appointments')->with('success', '예약이 접수되었습니다.');
    }

    public function destroy(Appointment $appointment)
    {
        if ($appointment->patient_id !== auth()->id()) {
            abort(403);
        }

        if ($appointment->status !== 'pending') {
            return back()->with('error', '대기 중인 예약만 취소할 수 있습니다.');
        }

        $appointment->update(['status' => 'cancelled']);

        return redirect()->route('patient.appointments')->with('success', '예약이 취소되었습니다.');
    }
}
