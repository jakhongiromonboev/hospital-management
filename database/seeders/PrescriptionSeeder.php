<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Database\Seeder;

class PrescriptionSeeder extends Seeder
{
    public function run(): void
    {
        $completed = Appointment::where('status', 'completed')->take(5)->get();

        $prescriptionData = [
            [
                'diagnosis' => '경미한 고혈압, 혈압 수치 상승',
                'notes' => '염분 섭취 줄이기와 규칙적인 운동 권고.',
                'items' => [
                    ['medicine_name' => '암로디핀', 'dosage' => '5mg', 'frequency' => '1일 1회', 'duration' => '30일', 'instructions' => '아침 식후'],
                    ['medicine_name' => '히드로클로로티아지드', 'dosage' => '12.5mg', 'frequency' => '1일 1회', 'duration' => '30일', 'instructions' => '아침 식사와 함께'],
                ],
            ],
            [
                'diagnosis' => '긴장성 두통, 경부 긴장',
                'notes' => '휴식 및 장시간 화면 노출 자제.',
                'items' => [
                    ['medicine_name' => '이부프로펜', 'dosage' => '400mg', 'frequency' => '1일 3회', 'duration' => '7일', 'instructions' => '식후 복용'],
                    ['medicine_name' => '시클로벤자프린', 'dosage' => '10mg', 'frequency' => '취침 전', 'duration' => '5일', 'instructions' => '졸음 유발 가능'],
                    ['medicine_name' => '비타민 B 복합제', 'dosage' => '1정', 'frequency' => '1일 1회', 'duration' => '30일', 'instructions' => '물과 함께'],
                ],
            ],
            [
                'diagnosis' => '급성 무릎 염좌 1도',
                'notes' => 'RICE 처치. 2주 후 재진.',
                'items' => [
                    ['medicine_name' => '나프록센', 'dosage' => '500mg', 'frequency' => '1일 2회', 'duration' => '10일', 'instructions' => '식사와 함께'],
                    ['medicine_name' => '글루코사민', 'dosage' => '1500mg', 'frequency' => '1일 1회', 'duration' => '60일', 'instructions' => '식후'],
                ],
            ],
            [
                'diagnosis' => '상기도 감염',
                'notes' => '수분 섭취와 충분한 휴식.',
                'items' => [
                    ['medicine_name' => '아목시실린', 'dosage' => '500mg', 'frequency' => '1일 3회', 'duration' => '7일', 'instructions' => '처방 기간 끝까지 복용'],
                    ['medicine_name' => '세티리진', 'dosage' => '10mg', 'frequency' => '1일 1회', 'duration' => '5일', 'instructions' => '취침 전'],
                    ['medicine_name' => '아세트아미노펜', 'dosage' => '500mg', 'frequency' => '필요 시', 'duration' => '5일', 'instructions' => '1일 최대 4정'],
                ],
            ],
            [
                'diagnosis' => '철결핍성 빈혈',
                'notes' => '6주 후 혈액 검사 재검.',
                'items' => [
                    ['medicine_name' => '황산철', 'dosage' => '325mg', 'frequency' => '1일 2회', 'duration' => '90일', 'instructions' => '공복, 비타민C와 함께'],
                    ['medicine_name' => '엽산', 'dosage' => '1mg', 'frequency' => '1일 1회', 'duration' => '90일', 'instructions' => '철제와 함께'],
                ],
            ],
        ];

        foreach ($completed as $i => $appointment) {
            if (!isset($prescriptionData[$i])) {
                break;
            }

            $data = $prescriptionData[$i];
            $prescription = Prescription::create([
                'appointment_id' => $appointment->id,
                'doctor_id' => $appointment->doctor_id,
                'patient_id' => $appointment->patient_id,
                'diagnosis' => $data['diagnosis'],
                'notes' => $data['notes'],
            ]);

            foreach ($data['items'] as $item) {
                $prescription->items()->create($item);
            }
        }
    }
}
