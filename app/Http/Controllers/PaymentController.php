<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments = auth()->user()->payments()
            ->with('appointment')
            ->latest()
            ->paginate(10);

        return view('payments.index', compact('payments'));
    }

    public function pay(Payment $payment)
    {
        if ($payment->patient_id !== auth()->id()) {
            abort(403);
        }

        if ($payment->status === 'paid') {
            return back()->with('error', '이미 처리된 결제입니다.');
        }

        return view('payments.pay', compact('payment'));
    }

    public function process(Request $request, Payment $payment)
    {
        if ($payment->patient_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'payment_method' => 'required|in:cash,credit_card,debit_card,bank_transfer',
        ]);

        $payment->update([
            'status' => 'paid',
            'payment_method' => $request->payment_method,
            'transaction_id' => 'TXN-' . strtoupper(uniqid()),
            'paid_at' => now(),
        ]);

        return redirect()->route('patient.payments')->with('success', '결제가 완료되었습니다.');
    }

    public function invoice(Payment $payment)
    {
        $payment->load(['patient', 'appointment']);

        $pdf = Pdf::loadView('pdf.invoice', compact('payment'));

        return $pdf->download('invoice-' . $payment->invoice_number . '.pdf');
    }
}
