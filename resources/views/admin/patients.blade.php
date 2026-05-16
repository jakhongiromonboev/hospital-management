@extends('layouts.app')
@section('page-title', '환자 목록')

@section('content')
<div class="card">
    <div class="card-header py-2">전체 환자</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr><th>이름</th><th>이메일</th><th>전화</th><th>혈액형</th><th>성별</th><th>통증 부위</th><th>작업</th></tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr>
                        <td class="fw-semibold">{{ $patient->name }}</td>
                        <td>{{ $patient->email }}</td>
                        <td>{{ $patient->phone ?? '-' }}</td>
                        <td>{{ $patient->blood_group ?? '-' }}</td>
                        <td>{{ $patient->gender ? ($t['gender'][$patient->gender] ?? $patient->gender) : '-' }}</td>
                        <td>
                            @if($patient->pain_records_count > 0)
                                <span class="badge bg-danger bg-opacity-10 text-danger">{{ $patient->pain_records_count }}곳</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.patients.detail', $patient) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-body-text"></i> 신체 지도
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">환자가 없습니다</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="mt-3">{{ $patients->links() }}</div>
@endsection
