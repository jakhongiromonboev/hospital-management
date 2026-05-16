<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_doctors' => User::where('role', 'doctor')->count(),
            'total_patients' => User::where('role', 'patient')->count(),
            'total_appointments' => Appointment::count(),
            'total_revenue' => Payment::where('status', 'paid')->sum('amount'),
            'pending_appointments' => Appointment::where('status', 'pending')->count(),
            'today_appointments' => Appointment::whereDate('appointment_date', today())->count(),
        ];

        $monthly_revenue = Payment::where('status', 'paid')
            ->selectRaw("CAST(strftime('%m', paid_at) AS INTEGER) as month, SUM(amount) as total")
            ->whereRaw("strftime('%Y', paid_at) = ?", [date('Y')])
            ->groupByRaw("strftime('%m', paid_at)")
            ->pluck('total', 'month')
            ->toArray();

        $appointment_stats = Appointment::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $recent_appointments = Appointment::with(['patient', 'doctor'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'monthly_revenue', 'appointment_stats', 'recent_appointments'));
    }

    public function doctors()
    {
        $doctors = User::where('role', 'doctor')->latest()->paginate(10);
        return view('admin.doctors', compact('doctors'));
    }

    public function createDoctor()
    {
        return view('admin.doctor-form');
    }

    public function storeDoctor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'phone' => 'nullable|string',
            'specialization' => 'required|string',
            'bio' => 'nullable|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'doctor',
            'phone' => $request->phone,
            'specialization' => $request->specialization,
            'bio' => $request->bio,
        ]);

        return redirect()->route('admin.doctors')->with('success', '의사가 등록되었습니다.');
    }

    public function editDoctor(User $doctor)
    {
        return view('admin.doctor-form', compact('doctor'));
    }

    public function updateDoctor(Request $request, User $doctor)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $doctor->id,
            'phone' => 'nullable|string',
            'specialization' => 'required|string',
            'bio' => 'nullable|string',
        ]);

        $data = $request->only('name', 'email', 'phone', 'specialization', 'bio');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $doctor->update($data);

        return redirect()->route('admin.doctors')->with('success', '의사 정보가 수정되었습니다.');
    }

    public function destroyDoctor(User $doctor)
    {
        $doctor->delete();
        return redirect()->route('admin.doctors')->with('success', '의사가 삭제되었습니다.');
    }

    public function patients()
    {
        $patients = User::where('role', 'patient')->withCount('painRecords')->latest()->paginate(10);
        return view('admin.patients', compact('patients'));
    }

    public function patientDetail(User $patient)
    {
        $patient->load('painRecords');
        return view('admin.patient-detail', compact('patient'));
    }

    public function appointments()
    {
        $appointments = Appointment::with(['patient', 'doctor'])->latest()->paginate(10);
        return view('admin.appointments', compact('appointments'));
    }

    public function payments()
    {
        $payments = Payment::with(['patient', 'appointment'])->latest()->paginate(10);
        return view('admin.payments', compact('payments'));
    }

    public function reports()
    {
        $monthly_patients = User::where('role', 'patient')
            ->selectRaw("CAST(strftime('%m', created_at) AS INTEGER) as month, COUNT(*) as count")
            ->whereRaw("strftime('%Y', created_at) = ?", [date('Y')])
            ->groupByRaw("strftime('%m', created_at)")
            ->pluck('count', 'month')
            ->toArray();

        $monthly_revenue = Payment::where('status', 'paid')
            ->selectRaw("CAST(strftime('%m', paid_at) AS INTEGER) as month, SUM(amount) as total")
            ->whereRaw("strftime('%Y', paid_at) = ?", [date('Y')])
            ->groupByRaw("strftime('%m', paid_at)")
            ->pluck('total', 'month')
            ->toArray();

        $doctor_stats = User::where('role', 'doctor')
            ->withCount('doctorAppointments')
            ->orderByDesc('doctor_appointments_count')
            ->take(10)
            ->get();

        return view('admin.reports', compact('monthly_patients', 'monthly_revenue', 'doctor_stats'));
    }
}
