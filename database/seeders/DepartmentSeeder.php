<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = [
            ['name' => '심장내과', 'description' => '심장 및 혈관 질환'],
            ['name' => '신경과', 'description' => '뇌 및 신경계 질환'],
            ['name' => '정형외과', 'description' => '뼈·관절·근골격계'],
            ['name' => '소아과', 'description' => '영유아·아동·청소년 진료'],
            ['name' => '피부과', 'description' => '피부·모발·손톱 질환'],
            ['name' => '일반내과', 'description' => '일차 진료 및 건강 상담'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }
    }
}
