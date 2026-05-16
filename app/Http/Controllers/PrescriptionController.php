<?php

namespace App\Http\Controllers;

use App\Models\Prescription;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrescriptionController extends Controller
{
    public function doctorIndex()
    {
        $prescriptions = auth()->user()->doctorPrescriptions()
            ->with(['patient', 'items'])
            ->latest()
            ->paginate(10);

        return view('prescriptions.index', compact('prescriptions'));
    }

    public function patientIndex()
    {
        $prescriptions = auth()->user()->patientPrescriptions()
            ->with(['doctor', 'items'])
            ->latest()
            ->paginate(10);

        return view('prescriptions.index', compact('prescriptions'));
    }

    public function create($patient = null)
    {
        $patients = User::where('role', 'patient')->get();
        $selected_patient = $patient ? User::findOrFail($patient) : null;

        return view('prescriptions.create', compact('patients', 'selected_patient'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:users,id',
            'diagnosis' => 'required|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.medicine_name' => 'required|string',
            'items.*.dosage' => 'required|string',
            'items.*.frequency' => 'required|string',
            'items.*.duration' => 'required|string',
            'items.*.instructions' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $prescription = Prescription::create([
                'doctor_id' => auth()->id(),
                'patient_id' => $request->patient_id,
                'appointment_id' => $request->appointment_id,
                'diagnosis' => $request->diagnosis,
                'notes' => $request->notes,
            ]);

            foreach ($request->items as $item) {
                $prescription->items()->create($item);
            }
        });

        return redirect()->route('doctor.prescriptions')->with('success', '처방전이 등록되었습니다.');
    }

    public function exportPdf(Prescription $prescription)
    {
        $prescription->load(['doctor', 'patient', 'items']);

        $pdf = Pdf::loadView('pdf.prescription', compact('prescription'));

        return $pdf->download('prescription-' . $prescription->id . '.pdf');
    }
}
