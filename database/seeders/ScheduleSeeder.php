<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $doctors = User::where('role', 'doctor')->get();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

        $schedules = [
            // Doctor 1 - Mon-Fri 9am-5pm
            ['start' => '09:00', 'end' => '17:00', 'max' => 12],
            // Doctor 2 - Mon-Fri 10am-6pm
            ['start' => '10:00', 'end' => '18:00', 'max' => 10],
            // Doctor 3 - Mon-Fri 8am-4pm
            ['start' => '08:00', 'end' => '16:00', 'max' => 15],
        ];

        foreach ($doctors as $index => $doctor) {
            $config = $schedules[$index] ?? $schedules[0];
            foreach ($days as $day) {
                Schedule::create([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => $config['start'],
                    'end_time' => $config['end'],
                    'max_patients' => $config['max'],
                    'status' => true,
                ]);
            }
        }
    }
}
