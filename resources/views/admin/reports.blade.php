@extends('layouts.app')
@section('page-title', '보고서 및 분석')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header py-3">월별 신규 환자 ({{ date('Y') }}년)</div>
            <div class="card-body">
                <canvas id="patientChart" height="120"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header py-3">매출 추이 ({{ date('Y') }}년)</div>
            <div class="card-body">
                <canvas id="revenueTrendChart" height="120"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header py-3">예약 수 기준 상위 의사</div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead>
                <tr><th>#</th><th>의사</th><th>전문과목</th><th>총 예약</th></tr>
            </thead>
            <tbody>
                @foreach($doctor_stats as $i => $doc)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="fw-semibold">{{ $doc->name }} 의사</td>
                    <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $doc->specialization }}</span></td>
                    <td>{{ $doc->doctor_appointments_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
const months = ['1월','2월','3월','4월','5월','6월','7월','8월','9월','10월','11월','12월'];

const patientData = new Array(12).fill(0);
@foreach($monthly_patients as $month => $count)
    patientData[{{ $month }} - 1] = {{ $count }};
@endforeach
new Chart(document.getElementById('patientChart').getContext('2d'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: '신규 환자',
            data: patientData,
            backgroundColor: 'rgba(16, 185, 129, 0.5)',
            borderColor: 'rgb(16, 185, 129)',
            borderWidth: 2, borderRadius: 8,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});

const revData = new Array(12).fill(0);
@foreach($monthly_revenue as $month => $total)
    revData[{{ $month }} - 1] = {{ $total }};
@endforeach
new Chart(document.getElementById('revenueTrendChart').getContext('2d'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: '매출 ($)',
            data: revData,
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            fill: true, tension: 0.4, pointRadius: 4,
        }]
    },
    options: { responsive: true, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
});
</script>
@endpush
