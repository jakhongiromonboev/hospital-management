<?php

namespace App\Http\Controllers;

use App\Models\Payment;

class PatientController extends Controller
{
    public function dashboard()
    {
        $patient = auth()->user();

        $stats = [
            'upcoming_appointments' => $patient->patientAppointments()
                ->whereDate('appointment_date', '>=', today())
                ->whereIn('status', ['pending', 'confirmed'])
                ->count(),
            'total_prescriptions' => $patient->patientPrescriptions()->count(),
            'pending_payments' => $patient->payments()->where('status', 'pending')->sum('amount'),
            'completed_appointments' => $patient->patientAppointments()->where('status', 'completed')->count(),
        ];

        $upcoming_appointments = $patient->patientAppointments()
            ->with('doctor')
            ->whereDate('appointment_date', '>=', today())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(5)
            ->get();

        return view('patient.dashboard', compact('stats', 'upcoming_appointments'));
    }

    public function painMap()
    {
        $painRecords = auth()->user()->painRecords()->latest()->get();
        return view('patient.pain-map', compact('painRecords'));
    }
}
