@extends('layouts.app')
@section('page-title', '처방전')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0">처방전 목록</h5>
    @if(auth()->user()->role === 'doctor')
        <a href="{{ route('doctor.prescriptions.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> 새 처방전</a>
    @endif
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>날짜</th>
                    <th>{{ auth()->user()->role === 'doctor' ? '환자' : '의사' }}</th>
                    <th>진단</th>
                    <th>약품</th>
                    <th>작업</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prescriptions as $rx)
                <tr>
                    <td>{{ $rx->created_at->year }}년 {{ $rx->created_at->month }}월 {{ $rx->created_at->day }}일</td>
                    <td class="fw-semibold">
                        @if(auth()->user()->role === 'doctor')
                            {{ $rx->patient->name }}
                        @else
                            {{ $rx->doctor->name }} 의사
                        @endif
                    </td>
                    <td>{{ Str::limit($rx->diagnosis, 40) }}</td>
                    <td><span class="badge bg-primary">{{ $rx->items->count() }}종</span></td>
                    <td>
                        <a href="{{ route('prescriptions.pdf', $rx) }}" class="btn btn-sm btn-outline-danger" target="_blank">
                            <i class="bi bi-file-earmark-pdf"></i> PDF
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">처방전이 없습니다</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $prescriptions->links() }}</div>
@endsection
