<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function dashboard()
    {
        $doctor = auth()->user();

        $stats = [
            'today_appointments' => $doctor->doctorAppointments()->whereDate('appointment_date', today())->count(),
            'total_patients' => $doctor->doctorAppointments()->distinct('patient_id')->count('patient_id'),
            'pending' => $doctor->doctorAppointments()->where('status', 'pending')->count(),
            'completed_today' => $doctor->doctorAppointments()
                ->whereDate('appointment_date', today())
                ->where('status', 'completed')
                ->count(),
        ];

        $today_appointments = $doctor->doctorAppointments()
            ->with('patient')
            ->whereDate('appointment_date', today())
            ->orderBy('appointment_time')
            ->get();

        return view('doctor.dashboard', compact('stats', 'today_appointments'));
    }

    public function appointments(Request $request)
    {
        $doctor = auth()->user();

        $query = $doctor->doctorAppointments()->with('patient');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $appointments = $query->latest('appointment_date')->paginate(10);

        return view('doctor.appointments', compact('appointments'));
    }

    public function updateAppointmentStatus(Request $request, Appointment $appointment)
    {
        if ($appointment->doctor_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:confirmed,completed,cancelled',
        ]);

        $appointment->update([
            'status' => $request->status,
            'notes' => $request->notes ?? $appointment->notes,
        ]);

        if ($request->status === 'completed') {
            Payment::create([
                'patient_id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
                'amount' => 150.00,
                'status' => 'pending',
                'description' => '진찰료 - ' . auth()->user()->name . ' 의사',
                'invoice_number' => 'INV-' . strtoupper(uniqid()),
            ]);
        }

        $statusLabel = trans('labels.appointment_status.' . $request->status);

        return redirect()->back()->with('success', __('messages.flash.appointment_status_updated', ['status' => $statusLabel]));
    }

    public function patients()
    {
        $doctor = auth()->user();

        $patientIds = $doctor->doctorAppointments()->pluck('patient_id')->unique();
        $patients = User::whereIn('id', $patientIds)->withCount('painRecords')->paginate(10);

        return view('doctor.patients', compact('patients'));
    }

    public function patientDetail(User $patient)
    {
        $doctor = auth()->user();

        $hasRelationship = $doctor->doctorAppointments()
            ->where('patient_id', $patient->id)
            ->exists();

        if (!$hasRelationship || $patient->role !== 'patient') {
            abort(403, '본인 환자만 조회할 수 있습니다.');
        }

        $patient->load('painRecords');
        return view('doctor.patient-detail', compact('patient'));
    }
}
