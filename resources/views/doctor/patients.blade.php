@extends('layouts.app')
@section('page-title', '내 환자')

@push('styles')
<style>
    /* Mobile: kam muhim ustunlarni yashirish */
    @media (max-width: 768px) {
        .doc-patients-table thead th.col-email,
        .doc-patients-table tbody td.col-email,
        .doc-patients-table thead th.col-blood,
        .doc-patients-table tbody td.col-blood { display: none; }

        .doc-patients-table .btn-action-text { display: none; }
    }
</style>
@endpush

@section('content')
<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0 doc-patients-table">
            <thead>
                <tr><th>이름</th><th class="col-email">이메일</th><th>전화</th><th class="col-blood">혈액형</th><th>통증</th><th>작업</th></tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                <tr>
                    <td class="fw-semibold">{{ $patient->name }}</td>
                    <td class="col-email">{{ $patient->email }}</td>
                    <td>{{ $patient->phone ?? '-' }}</td>
                    <td class="col-blood">{{ $patient->blood_group ?? '-' }}</td>
                    <td>
                        @if($patient->pain_records_count > 0)
                            <span class="badge bg-warning bg-opacity-10 text-warning">{{ $patient->pain_records_count }}곳</span>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            <a href="{{ route('doctor.patients.detail', $patient->id) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-person-vcard"></i><span class="btn-action-text"> 상세</span>
                            </a>
                            <a href="{{ route('doctor.prescriptions.create', $patient->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-file-earmark-medical"></i><span class="btn-action-text"> 처방 작성</span>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted py-4">환자가 없습니다</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $patients->links() }}</div>
@endsection
