@extends('layouts.app')
@section('page-title', '진료 시간 관리')

@section('content')
<div class="row g-4">
    <div class="col-lg-5">
        <div class="card">
            <div class="card-header py-3">진료 시간 추가</div>
            <div class="card-body">
                <form method="POST" action="{{ route('doctor.schedule.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-semibold">요일</label>
                        <select name="day_of_week" class="form-select" required>
                            <option value="">요일 선택</option>
                            @foreach(['monday','tuesday','wednesday','thursday','friday','saturday','sunday'] as $day)
                                <option value="{{ $day }}">{{ $t['weekday'][$day] ?? $day }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">시작</label>
                            <input type="time" name="start_time" class="form-control" required>
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label fw-semibold">종료</label>
                            <input type="time" name="end_time" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">최대 환자 수</label>
                        <input type="number" name="max_patients" class="form-control" value="10" min="1" max="50" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">추가</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card">
            <div class="card-header py-3">현재 진료 시간</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr><th>요일</th><th>시간</th><th>최대</th><th>상태</th><th>작업</th></tr>
                    </thead>
                    <tbody>
                        @forelse($schedules as $schedule)
                        <tr>
                            <td class="fw-semibold">{{ $t['weekday'][$schedule->day_of_week] ?? $schedule->day_of_week }}</td>
                            <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                            <td>{{ $schedule->max_patients }}</td>
                            <td>
                                @if($schedule->status)
                                    <span class="badge bg-success">사용</span>
                                @else
                                    <span class="badge bg-secondary">중지</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $schedule->id }}"><i class="bi bi-pencil"></i></button>
                                <form action="{{ route('doctor.schedule.destroy', $schedule) }}" method="POST" class="d-inline" onsubmit="return confirm('이 진료 시간을 삭제할까요?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            </td>
                        </tr>

                        <div class="modal fade" id="editModal{{ $schedule->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('doctor.schedule.update', $schedule) }}">
                                        @csrf @method('PUT')
                                        <div class="modal-header">
                                            <h5 class="modal-title">{{ $t['weekday'][$schedule->day_of_week] ?? $schedule->day_of_week }} 진료 시간 수정</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-6 mb-3">
                                                    <label class="form-label">시작</label>
                                                    <input type="time" name="start_time" class="form-control" value="{{ $schedule->start_time }}" required>
                                                </div>
                                                <div class="col-6 mb-3">
                                                    <label class="form-label">종료</label>
                                                    <input type="time" name="end_time" class="form-control" value="{{ $schedule->end_time }}" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">최대 환자 수</label>
                                                <input type="number" name="max_patients" class="form-control" value="{{ $schedule->max_patients }}" min="1" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">상태</label>
                                                <select name="status" class="form-select">
                                                    <option value="1" {{ $schedule->status ? 'selected' : '' }}>사용</option>
                                                    <option value="0" {{ !$schedule->status ? 'selected' : '' }}>중지</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">취소</button>
                                            <button type="submit" class="btn btn-primary">저장</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">등록된 진료 시간이 없습니다. 위에서 추가하세요.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
