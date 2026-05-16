@extends('layouts.app')
@section('page-title', '의사 대시보드')

@section('content')
@include('partials.weather')

<div class="row g-4 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="label">오늘 예약</div><div class="value">{{ $stats['today_appointments'] }}</div></div>
                <div class="icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-calendar-day"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="label">총 환자</div><div class="value">{{ $stats['total_patients'] }}</div></div>
                <div class="icon bg-success bg-opacity-10 text-success"><i class="bi bi-people"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="label">대기</div><div class="value">{{ $stats['pending'] }}</div></div>
                <div class="icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-hourglass-split"></i></div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="label">오늘 완료</div><div class="value">{{ $stats['completed_today'] }}</div></div>
                <div class="icon bg-info bg-opacity-10 text-info"><i class="bi bi-check-circle"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header py-3">오늘의 예약</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>시간</th><th>환자</th><th>사유</th><th>상태</th><th>작업</th></tr>
            </thead>
            <tbody>
                @forelse($today_appointments as $apt)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($apt->appointment_time)->format('H:i') }}</td>
                    <td class="fw-semibold">{{ $apt->patient->name }}</td>
                    <td>{{ Str::limit($apt->reason, 40) }}</td>
                    <td><span class="badge badge-{{ $apt->status }}">{{ $t['appointment_status'][$apt->status] ?? $apt->status }}</span></td>
                    <td>
                        @if($apt->status === 'pending')
                            <form action="{{ route('doctor.appointments.updateStatus', $apt) }}" method="POST" class="d-inline">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="confirmed">
                                <button class="btn btn-sm btn-outline-primary">확정</button>
                            </form>
                            <form action="{{ route('doctor.appointments.updateStatus', $apt) }}" method="POST" class="d-inline">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="cancelled">
                                <button class="btn btn-sm btn-outline-danger">취소</button>
                            </form>
                        @elseif($apt->status === 'confirmed')
                            <form action="{{ route('doctor.appointments.updateStatus', $apt) }}" method="POST" class="d-inline">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="completed">
                                <button class="btn btn-sm btn-outline-success">완료</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">오늘 예약이 없습니다</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
