<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Payment;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    public function run(): void
    {
        $completed = Appointment::where('status', 'completed')->get();

        $methods = ['cash', 'credit_card', 'debit_card', 'bank_transfer'];

        foreach ($completed as $i => $appointment) {
            $isPaid = $i < 4;

            Payment::create([
                'patient_id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
                'amount' => collect([100, 150, 200, 250, 175])->random(),
                'status' => $isPaid ? 'paid' : 'pending',
                'payment_method' => $isPaid ? $methods[array_rand($methods)] : null,
                'transaction_id' => $isPaid ? 'TXN-' . strtoupper(uniqid()) : null,
                'description' => '진찰료 — ' . $appointment->doctor->name . ' 의사',
                'invoice_number' => 'INV-' . strtoupper(uniqid()),
                'paid_at' => $isPaid ? Carbon::now()->subDays(rand(1, 25)) : null,
            ]);
        }
    }
}
