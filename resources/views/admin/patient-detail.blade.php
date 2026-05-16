@extends('layouts.app')
@section('page-title', $patient->name . ' — 통증 신체 지도')

@section('content')
<div class="mb-3">
    <a href="{{ route('admin.patients') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-arrow-left"></i> 환자 목록</a>
</div>

<div class="row g-4">
    <div class="col-lg-9">
        <div class="card mb-3">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="patient-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ $patient->name }}</h5>
                        <div class="text-muted small">{{ $patient->email }} · {{ $patient->phone ?? '전화 없음' }}</div>
                    </div>
                    <div class="ms-auto d-flex gap-2 flex-wrap">
                        <span class="badge bg-primary bg-opacity-10 text-primary p-2">{{ $patient->gender ? ($t['gender'][$patient->gender] ?? $patient->gender) : '없음' }}</span>
                        <span class="badge bg-danger bg-opacity-10 text-danger p-2">{{ $patient->blood_group ?? '없음' }}</span>
                        <span class="badge bg-warning bg-opacity-10 text-warning p-2">통증 {{ $patient->painRecords->count() }}곳</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header py-2">통증 기록</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>부위</th><th>정도</th><th>설명</th><th>날짜</th><th>영상</th></tr></thead>
                        <tbody>
                            @forelse($patient->painRecords as $record)
                            <tr class="pain-row" data-area="{{ $record->area }}">
                                <td class="fw-semibold">{{ $t['pain_area'][$record->area] ?? str_replace('_', ' ', $record->area) }}</td>
                                <td>
                                    @if($record->severity === 'high')
                                        <span class="badge" style="background:#fee2e2;color:#dc2626;">{{ $t['pain_severity']['high'] }}</span>
                                    @elseif($record->severity === 'medium')
                                        <span class="badge" style="background:#fef3c7;color:#d97706;">{{ $t['pain_severity']['medium'] }}</span>
                                    @else
                                        <span class="badge" style="background:#d1fae5;color:#059669;">{{ $t['pain_severity']['low'] }}</span>
                                    @endif
                                </td>
                                <td>{{ $record->description ?? '-' }}</td>
                                <td>{{ $record->created_at->year }}년 {{ $record->created_at->month }}월 {{ $record->created_at->day }}일</td>
                                <td>
                                    <a href="https://www.youtube.com/results?search_query={{ urlencode(($t['pain_area'][$record->area] ?? $record->area) . ' 통증 원인 치료') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-danger" title="YouTube에서 이 질환 정보 보기">
                                        <i class="bi bi-youtube"></i><span class="btn-youtube-text"> 영상</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">통증 기록이 없습니다</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-3">
        @include('partials.pain-map', ['painRecords' => $patient->painRecords])
    </div>
</div>
@endsection

@push('styles')
<style>
    .patient-avatar {
        width: 50px; height: 50px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex; align-items: center; justify-content: center; color: #fff; font-size: 1.4rem;
    }
</style>
@endpush

