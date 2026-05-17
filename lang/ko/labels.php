<?php

return [
    'appointment_status' => [
        'pending' => '대기',
        'confirmed' => '확정',
        'completed' => '완료',
        'cancelled' => '취소',
    ],
    'payment_status' => [
        'pending' => '미결제',
        'paid' => '결제완료',
        'failed' => '실패',
    ],
    'payment_method' => [
        'cash' => '현금',
        'credit_card' => '신용카드',
        'debit_card' => '체크카드',
        'bank_transfer' => '계좌이체',
    ],
    'gender' => [
        'male' => '남성',
        'female' => '여성',
        'other' => '기타',
    ],
    'role' => [
        'admin' => '관리자',
        'doctor' => '의사',
        'patient' => '환자',
    ],
    'weekday' => [
        'monday' => '월요일',
        'tuesday' => '화요일',
        'wednesday' => '수요일',
        'thursday' => '목요일',
        'friday' => '금요일',
        'saturday' => '토요일',
        'sunday' => '일요일',
    ],
    'pain_area' => [
        'head' => '머리',
        'neck' => '목',
        'chest' => '가슴',
        'upper_back' => '등 상부',
        'lower_back' => '허리',
        'stomach' => '복부',
        'left_shoulder' => '왼쪽 어깨',
        'right_shoulder' => '오른쪽 어깨',
        'left_knee' => '왼쪽 무릎',
        'right_knee' => '오른쪽 무릎',
        'left_wrist' => '왼쪽 손목',
        'right_wrist' => '오른쪽 손목',
        'left_ankle' => '왼쪽 발목',
        'right_ankle' => '오른쪽 발목',
    ],
    'pain_severity' => [
        'low' => '경미',
        'medium' => '보통',
        'high' => '심함',
    ],
];
