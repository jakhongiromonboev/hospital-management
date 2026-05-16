@extends('layouts.app')
@section('page-title', '전체 예약')

@section('content')
<div class="card">
    <div class="card-header py-3">예약 목록</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>환자</th><th>의사</th><th>날짜</th><th>시간</th><th>상태</th><th>사유</th></tr>
            </thead>
            <tbody>
                @forelse($appointments as $apt)
                <tr>
                    <td>{{ $apt->patient->name }}</td>
                    <td>{{ $apt->doctor->name }} 의사</td>
                    <td>{{ $apt->appointment_date->year }}년 {{ $apt->appointment_date->month }}월 {{ $apt->appointment_date->day }}일</td>
                    <td>{{ \Carbon\Carbon::parse($apt->appointment_time)->format('H:i') }}</td>
                    <td><span class="badge badge-{{ $apt->status }}">{{ $t['appointment_status'][$apt->status] ?? $apt->status }}</span></td>
                    <td>{{ Str::limit($apt->reason, 40) }}</td>
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
