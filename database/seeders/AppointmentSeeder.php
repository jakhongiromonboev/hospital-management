<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = User::where('role', 'doctor')->pluck('id')->toArray();
        $patients = User::where('role', 'patient')->pluck('id')->toArray();

        $reasons = [
            '가슴 통증과 호흡곤란',
            '2주간 지속되는 두통',
            '운동 후 무릎 통증',
            '정기 건강검진',
            '재진 상담',
            '어지럼증과 피로',
            '허리 통증과 근육 뻣뻣함',
            '피부 발진과 가려움',
            '고혈압 경과 관찰',
            '관절 통증과 붓기',
        ];

        $appointments = [
            ['days' => -30, 'time' => '09:30', 'status' => 'completed'],
            ['days' => -25, 'time' => '10:00', 'status' => 'completed'],
            ['days' => -20, 'time' => '11:00', 'status' => 'completed'],
            ['days' => -15, 'time' => '14:00', 'status' => 'completed'],
            ['days' => -10, 'time' => '09:00', 'status' => 'completed'],
            ['days' => -7, 'time' => '15:30', 'status' => 'completed'],
            ['days' => -5, 'time' => '10:30', 'status' => 'cancelled'],
            ['days' => -2, 'time' => '11:30', 'status' => 'confirmed'],
            ['days' => -1, 'time' => '13:00', 'status' => 'confirmed'],
            ['days' => 0, 'time' => '09:00', 'status' => 'pending'],
            ['days' => 0, 'time' => '10:30', 'status' => 'pending'],
            ['days' => 1, 'time' => '14:00', 'status' => 'pending'],
            ['days' => 3, 'time' => '09:30', 'status' => 'pending'],
            ['days' => 5, 'time' => '11:00', 'status' => 'pending'],
            ['days' => 7, 'time' => '16:00', 'status' => 'confirmed'],
        ];

        foreach ($appointments as $i => $apt) {
            Appointment::create([
                'patient_id' => $patients[$i % count($patients)],
                'doctor_id' => $doctors[$i % count($doctors)],
                'appointment_date' => Carbon::today()->addDays($apt['days']),
                'appointment_time' => $apt['time'],
                'status' => $apt['status'],
                'reason' => $reasons[$i % count($reasons)],
            ]);
        }
    }
}
