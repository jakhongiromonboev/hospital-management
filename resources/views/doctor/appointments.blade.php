@extends('layouts.app')
@section('page-title', '내 예약')

@section('content')
<div class="d-flex gap-2 mb-4 flex-wrap">
    <a href="{{ route('doctor.appointments') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-outline-secondary' }}">전체</a>
    <a href="{{ route('doctor.appointments', ['status' => 'pending']) }}" class="btn btn-sm {{ request('status') === 'pending' ? 'btn-warning' : 'btn-outline-secondary' }}">{{ $t['appointment_status']['pending'] }}</a>
    <a href="{{ route('doctor.appointments', ['status' => 'confirmed']) }}" class="btn btn-sm {{ request('status') === 'confirmed' ? 'btn-info' : 'btn-outline-secondary' }}">{{ $t['appointment_status']['confirmed'] }}</a>
    <a href="{{ route('doctor.appointments', ['status' => 'completed']) }}" class="btn btn-sm {{ request('status') === 'completed' ? 'btn-success' : 'btn-outline-secondary' }}">{{ $t['appointment_status']['completed'] }}</a>
    <a href="{{ route('doctor.appointments', ['status' => 'cancelled']) }}" class="btn btn-sm {{ request('status') === 'cancelled' ? 'btn-danger' : 'btn-outline-secondary' }}">{{ $t['appointment_status']['cancelled'] }}</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>환자</th><th>날짜</th><th>시간</th><th>상태</th><th>사유</th><th>작업</th></tr>
            </thead>
            <tbody>
                @forelse($appointments as $apt)
                <tr>
                    <td class="fw-semibold">{{ $apt->patient->name }}</td>
                    <td>{{ $apt->appointment_date->year }}년 {{ $apt->appointment_date->month }}월 {{ $apt->appointment_date->day }}일</td>
                    <td>{{ \Carbon\Carbon::parse($apt->appointment_time)->format('H:i') }}</td>
                    <td><span class="badge badge-{{ $apt->status }}">{{ $t['appointment_status'][$apt->status] ?? $apt->status }}</span></td>
                    <td>{{ Str::limit($apt->reason, 30) }}</td>
                    <td>
                        @if($apt->status === 'pending')
                            <form action="{{ route('doctor.appointments.updateStatus', $apt) }}" method="POST" class="d-inline">@csrf @method('PUT')<input type="hidden" name="status" value="confirmed"><button class="btn btn-sm btn-outline-primary">확정</button></form>
                            <form action="{{ route('doctor.appointments.updateStatus', $apt) }}" method="POST" class="d-inline">@csrf @method('PUT')<input type="hidden" name="status" value="cancelled"><button class="btn btn-sm btn-outline-danger">취소</button></form>
                        @elseif($apt->status === 'confirmed')
                            <form action="{{ route('doctor.appointments.updateStatus', $apt) }}" method="POST" class="d-inline">@csrf @method('PUT')<input type="hidden" name="status" value="completed"><button class="btn btn-sm btn-outline-success">완료</button></form>
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
<div class="mt-3">{{ $appointments->appends(request()->query())->links() }}</div>
@endsection
