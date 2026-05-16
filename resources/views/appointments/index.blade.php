@extends('layouts.app')
@section('page-title', '내 예약')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">예약 목록</h5>
    <a href="{{ route('patient.appointments.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> 예약하기</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>의사</th><th>전문과목</th><th>날짜</th><th>시간</th><th>상태</th><th>작업</th></tr>
            </thead>
            <tbody>
                @forelse($appointments as $apt)
                <tr>
                    <td class="fw-semibold">{{ $apt->doctor->name }} 의사</td>
                    <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $apt->doctor->specialization }}</span></td>
                    <td>{{ $apt->appointment_date->year }}년 {{ $apt->appointment_date->month }}월 {{ $apt->appointment_date->day }}일</td>
                    <td>{{ \Carbon\Carbon::parse($apt->appointment_time)->format('H:i') }}</td>
                    <td><span class="badge badge-{{ $apt->status }}">{{ $t['appointment_status'][$apt->status] ?? $apt->status }}</span></td>
                    <td>
                        @if($apt->status === 'pending')
                            <form action="{{ route('patient.appointments.cancel', $apt) }}" method="POST" class="d-inline" onsubmit="return confirm('이 예약을 취소할까요?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">취소</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">예약이 없습니다</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $appointments->links() }}</div>
@endsection
