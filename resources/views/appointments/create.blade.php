@extends('layouts.app')
@section('page-title', '예약하기')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header py-3">새 예약</div>
            <div class="card-body">
                <form method="POST" action="{{ route('patient.appointments.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">의사 선택 *</label>
                        <select name="doctor_id" class="form-select" id="doctorSelect" required>
                            <option value="">의사를 선택하세요</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}
                                    data-schedules="{{ json_encode($doctor->schedules) }}">
                                    {{ $doctor->name }} 의사 — {{ $doctor->specialization }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div id="scheduleInfo" class="alert alert-info d-none mb-3">
                        <strong>진료 가능 시간:</strong>
                        <div id="scheduleDetails"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">예약 날짜 *</label>
                            <input type="date" name="appointment_date" class="form-control" value="{{ old('appointment_date') }}" min="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">희망 시간 *</label>
                            <input type="time" name="appointment_time" class="form-control" value="{{ old('appointment_time') }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">방문 사유 *</label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="증상이나 방문 목적을 적어 주세요.">{{ old('reason') }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">예약 신청</button>
                        <a href="{{ route('patient.appointments') }}" class="btn btn-light">취소</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const dayKo = @json($t['weekday']);
document.getElementById('doctorSelect').addEventListener('change', function() {
    const option = this.options[this.selectedIndex];
    const info = document.getElementById('scheduleInfo');
    const details = document.getElementById('scheduleDetails');

    if (this.value) {
        const schedules = JSON.parse(option.dataset.schedules || '[]');
        if (schedules.length > 0) {
            let html = '<ul class="mb-0 mt-2">';
            schedules.forEach(s => {
                if (s.status) {
                    const dayLabel = dayKo[s.day_of_week] || s.day_of_week;
                    html += `<li><strong>${dayLabel}</strong>: ${s.start_time} - ${s.end_time} (최대 ${s.max_patients}명)</li>`;
                }
            });
            html += '</ul>';
            details.innerHTML = html;
            info.classList.remove('d-none');
        } else {
            details.innerHTML = '<p class="mt-2 mb-0">이 의사는 등록된 진료 시간이 없습니다.</p>';
            info.classList.remove('d-none');
        }
    } else {
        info.classList.add('d-none');
    }
});
</script>
@endpush
