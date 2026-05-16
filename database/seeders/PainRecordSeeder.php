<?php

namespace Database\Seeders;

use App\Models\PainRecord;
use App\Models\User;
use Illuminate\Database\Seeder;

class PainRecordSeeder extends Seeder
{
    public function run(): void
    {
        $patients = User::where('role', 'patient')->get();
        $areas = ['head', 'neck', 'chest', 'upper_back', 'lower_back', 'stomach', 'left_shoulder', 'right_shoulder', 'left_knee', 'right_knee', 'left_wrist', 'right_wrist', 'left_ankle', 'right_ankle'];
        $severities = ['low', 'medium', 'high'];
        $descriptions = [
            'head' => ['지속적인 편두통', '긴장성 두통', '욱신거리는 통증'],
            'neck' => ['잠 후 목 뻣뻣함', '경부 긴장', '돌릴 때 통증'],
            'chest' => ['날카로운 가슴 통증', '가슴 답답함', '호흡 시 통증'],
            'upper_back' => ['근육 경련', '자세성 통증', '어깨 사이 통증'],
            'lower_back' => ['만성 허리통', '디스크 불편감', '들어올린 후 통증'],
            'stomach' => ['복부 경련', '소화 불편', '날카로운 복통'],
            'left_shoulder' => ['오십견 의심', '회전근개 통증', '묵직한 통증'],
            'right_shoulder' => ['운동 손상', '충돌 증후군', '팔 올릴 때 통증'],
            'left_knee' => ['무릎 부종', '계단 오를 때 통증', '관절 뻣뻣함'],
            'right_knee' => ['인대 긴장', '러너스 니', '소리와 함께 통증'],
            'left_wrist' => ['손목터널증후군 증상', '손목 염좌', '타자 후 통증'],
            'right_wrist' => ['힘줄염', '골절 후 회복기 통증', '악력 시 통증'],
            'left_ankle' => ['발목 염좌', '걸은 후 부종', '아킬레스 통증'],
            'right_ankle' => ['삐끗한 발목', '달리기 시 통증', '아침 뻣뻣함'],
        ];

        foreach ($patients as $patient) {
            $count = rand(2, 5);
            $selectedAreas = array_rand(array_flip($areas), $count);
            if (!is_array($selectedAreas)) {
                $selectedAreas = [$selectedAreas];
            }

            foreach ($selectedAreas as $area) {
                $descs = $descriptions[$area];
                PainRecord::create([
                    'patient_id' => $patient->id,
                    'area' => $area,
                    'severity' => $severities[array_rand($severities)],
                    'description' => $descs[array_rand($descs)],
                ]);
            }
        }
    }
}
