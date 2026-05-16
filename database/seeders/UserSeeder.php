<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => '시스템 관리자',
            'email' => 'admin@hospital.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '02-1000-0001',
        ]);

        $doctors = [
            ['name' => '김민수', 'email' => 'doctor1@hospital.com', 'specialization' => '심장내과', 'phone' => '02-2000-0001', 'bio' => '심장 전문의 경력 15년.'],
            ['name' => '이지은', 'email' => 'doctor2@hospital.com', 'specialization' => '신경과', 'phone' => '02-2000-0002', 'bio' => '뇌·신경 질환 및 수술 전문.'],
            ['name' => '박준호', 'email' => 'doctor3@hospital.com', 'specialization' => '정형외과', 'phone' => '02-2000-0003', 'bio' => '스포츠 의학 및 인공관절 전문.'],
        ];

        foreach ($doctors as $doctor) {
            User::create(array_merge($doctor, [
                'password' => Hash::make('password'),
                'role' => 'doctor',
            ]));
        }

        $patients = [
            ['name' => '최서연', 'email' => 'patient1@hospital.com', 'phone' => '010-3000-0001', 'gender' => 'female', 'blood_group' => 'A+', 'date_of_birth' => '1990-05-15', 'address' => '서울특별시 강남구 테헤란로 123'],
            ['name' => '정우진', 'email' => 'patient2@hospital.com', 'phone' => '010-3000-0002', 'gender' => 'male', 'blood_group' => 'B+', 'date_of_birth' => '1985-08-22', 'address' => '서울특별시 마포구 월드컵로 456'],
            ['name' => '한소희', 'email' => 'patient3@hospital.com', 'phone' => '010-3000-0003', 'gender' => 'female', 'blood_group' => 'O-', 'date_of_birth' => '1978-12-03', 'address' => '경기도 성남시 분당구 정자로 789'],
            ['name' => '윤도현', 'email' => 'patient4@hospital.com', 'phone' => '010-3000-0004', 'gender' => 'male', 'blood_group' => 'AB+', 'date_of_birth' => '1995-03-28', 'address' => '인천광역시 연수구 컨벤시아대로 321'],
            ['name' => '강민지', 'email' => 'patient5@hospital.com', 'phone' => '010-3000-0005', 'gender' => 'female', 'blood_group' => 'O+', 'date_of_birth' => '1988-07-10', 'address' => '부산광역시 해운대구 센텀중앙로 654'],
        ];

        foreach ($patients as $patient) {
            User::create(array_merge($patient, [
                'password' => Hash::make('password'),
                'role' => 'patient',
            ]));
        }
    }
}
