@extends('layouts.app')
@section('page-title', '환자 대시보드')

@section('content')
@include('partials.weather')

<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="label">예정 예약</div><div class="value">{{ $stats['upcoming_appointments'] }}</div></div>
                <div class="icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-calendar-check"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="label">처방전</div><div class="value">{{ $stats['total_prescriptions'] }}</div></div>
                <div class="icon bg-success bg-opacity-10 text-success"><i class="bi bi-file-earmark-medical"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="label">미결제</div><div class="value">${{ number_format($stats['pending_payments'], 2) }}</div></div>
                <div class="icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-credit-card"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="label">완료된 방문</div><div class="value">{{ $stats['completed_appointments'] }}</div></div>
                <div class="icon bg-info bg-opacity-10 text-info"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0">예정된 예약</h5>
    <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> 예약하기</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>의사</th><th>전문과목</th><th>날짜</th><th>시간</th><th>상태</th></tr>
            </thead>
            <tbody>
                @forelse($upcoming_appointments as $apt)
                <tr>
                    <td class="fw-semibold">{{ $apt->doctor->name }} 의사</td>
                    <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $apt->doctor->specialization }}</span></td>
                    <td>{{ $apt->appointment_date->year }}년 {{ $apt->appointment_date->month }}월 {{ $apt->appointment_date->day }}일</td>
                    <td>{{ \Carbon\Carbon::parse($apt->appointment_time)->format('H:i') }}</td>
                    <td><span class="badge badge-{{ $apt->status }}">{{ $t['appointment_status'][$apt->status] ?? $apt->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">예정된 예약이 없습니다. <a href="{{ route('patient.appointments.create') }}">지금 예약하기</a></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
