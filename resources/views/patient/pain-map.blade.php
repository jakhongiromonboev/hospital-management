@extends('layouts.app')
@section('page-title', '나의 통증 지도')

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-body py-3">
                <div class="d-flex align-items-center gap-3 flex-wrap">
                    <div class="patient-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <div>
                        <h5 class="mb-0">{{ auth()->user()->name }}</h5>
                        <div class="text-muted small">{{ auth()->user()->email }}</div>
                    </div>
                    <div class="ms-auto">
                        <span class="badge bg-warning bg-opacity-10 text-warning p-2">통증 {{ $painRecords->count() }}곳</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header py-2">통증 기록</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead><tr><th>부위</th><th>정도</th><th>설명</th><th>날짜</th><th>질환 이해</th></tr></thead>
                        <tbody>
                            @forelse($painRecords as $record)
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
                                    <a href="https://www.youtube.com/results?search_query={{ urlencode(($t['pain_area'][$record->area] ?? $record->area) . ' 통증 원인 치료') }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-danger" title="YouTube에서 이 질환에 대해 알아보기">
                                        <i class="bi bi-youtube"></i><span class="btn-youtube-text"> 영상 보기</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center text-muted py-5">
                                <i class="bi bi-check-circle text-success fs-2 d-block mb-2"></i>
                                통증 기록이 없습니다. 건강하세요!
                            </td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($painRecords->isNotEmpty())
        <div class="alert alert-info mt-3 mb-0">
            <i class="bi bi-info-circle"></i>
            통증이 지속되거나 심해지면 의사와 상담하세요. <a href="{{ route('patient.appointments.create') }}" class="alert-link">예약하기</a>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        @include('partials.pain-map', ['painRecords' => $painRecords])
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
