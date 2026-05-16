@extends('layouts.app')
@section('page-title', '관리자 대시보드')

@section('content')
<div class="row g-2 mb-3">
    <div class="col-4 col-md-2">
        <div class="stat-mini">
            <div class="sm-icon bg-primary bg-opacity-10 text-primary"><i class="bi bi-people"></i></div>
            <div class="sm-val">{{ $stats['total_doctors'] }}</div>
            <div class="sm-lbl">의사</div>
        </div>
    </div>
    <div class="col-4 col-md-2">
        <div class="stat-mini">
            <div class="sm-icon bg-success bg-opacity-10 text-success"><i class="bi bi-person-lines-fill"></i></div>
            <div class="sm-val">{{ $stats['total_patients'] }}</div>
            <div class="sm-lbl">환자</div>
        </div>
    </div>
    <div class="col-4 col-md-2">
        <div class="stat-mini">
            <div class="sm-icon bg-info bg-opacity-10 text-info"><i class="bi bi-calendar-check"></i></div>
            <div class="sm-val">{{ $stats['total_appointments'] }}</div>
            <div class="sm-lbl">예약</div>
        </div>
    </div>
    <div class="col-4 col-md-2">
        <div class="stat-mini">
            <div class="sm-icon bg-warning bg-opacity-10 text-warning"><i class="bi bi-calendar-day"></i></div>
            <div class="sm-val">{{ $stats['today_appointments'] }}</div>
            <div class="sm-lbl">오늘</div>
        </div>
    </div>
    <div class="col-4 col-md-2">
        <div class="stat-mini">
            <div class="sm-icon bg-danger bg-opacity-10 text-danger"><i class="bi bi-hourglass-split"></i></div>
            <div class="sm-val">{{ $stats['pending_appointments'] }}</div>
            <div class="sm-lbl">대기</div>
        </div>
    </div>
    <div class="col-4 col-md-2">
        <div class="stat-mini">
            <div class="sm-icon bg-success bg-opacity-10 text-success"><i class="bi bi-currency-dollar"></i></div>
            <div class="sm-val">${{ number_format($stats['total_revenue'], 0) }}</div>
            <div class="sm-lbl">매출</div>
        </div>
    </div>
</div>

@include('partials.weather')

@php
    $apptChartLabels = array_map(fn ($k) => $t['appointment_status'][$k] ?? $k, array_keys($appointment_stats));
@endphp

<div class="row g-4 mb-4" style="--chart-h: 220px;">
    <div class="col-lg-8">
        <div class="card h-100">
            <div class="card-header py-2">월별 매출 ({{ date('Y') }}년)</div>
            <div class="card-body py-2 d-flex align-items-center" style="height: var(--chart-h);">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card h-100">
            <div class="card-header py-2">예약 상태</div>
            <div class="card-body py-2 d-flex align-items-center justify-content-center" style="height: var(--chart-h);">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header py-3">최근 예약</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>환자</th><th>의사</th><th>날짜</th><th>시간</th><th>상태</th></tr>
            </thead>
            <tbody>
                @forelse($recent_appointments as $apt)
                <tr>
                    <td>{{ $apt->patient->name }}</td>
                    <td>{{ $apt->doctor->name }} 의사</td>
                    <td>{{ $apt->appointment_date->year }}년 {{ $apt->appointment_date->month }}월 {{ $apt->appointment_date->day }}일</td>
                    <td>{{ \Carbon\Carbon::parse($apt->appointment_time)->format('H:i') }}</td>
                    <td><span class="badge badge-{{ $apt->status }}">{{ $t['appointment_status'][$apt->status] ?? $apt->status }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-muted py-4">예약이 없습니다</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const months = ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'];
const revenueData = new Array(12).fill(0);
@foreach($monthly_revenue as $month => $total)
    revenueData[{{ $month }} - 1] = {{ $total }};
@endforeach
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: '매출 ($)',
            data: revenueData,
            backgroundColor: 'rgba(59, 130, 246, 0.5)',
            borderColor: 'rgb(59, 130, 246)',
            borderWidth: 2,
            borderRadius: 8,
        }]
    },
    options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

const statusCtx = document.getElementById('statusChart').getContext('2d');
new Chart(statusCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode($apptChartLabels) !!},
        datasets: [{
            data: {!! json_encode(array_values($appointment_stats)) !!},
            backgroundColor: ['#fbbf24', '#3b82f6', '#10b981', '#ef4444'],
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
            legend: { position: 'right', labels: { boxWidth: 12, padding: 10, font: { size: 11 } } }
        }
    }
});
</script>
@endpush
