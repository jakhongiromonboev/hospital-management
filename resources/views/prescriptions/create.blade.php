@extends('layouts.app')
@section('page-title', '처방전 작성')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header py-3">새 처방전</div>
            <div class="card-body">
                <form method="POST" action="{{ route('doctor.prescriptions.store') }}" id="prescriptionForm">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">환자 *</label>
                            <select name="patient_id" class="form-select" required>
                                <option value="">환자 선택</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" {{ (old('patient_id') ?? optional($selected_patient)->id) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->name }} ({{ $patient->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">진단 *</label>
                        <textarea name="diagnosis" class="form-control" rows="3" required>{{ old('diagnosis') }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">비고</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="mb-0">약품</h5>
                        <button type="button" class="btn btn-sm btn-success" onclick="addMedicine()"><i class="bi bi-plus-lg"></i> 약품 추가</button>
                    </div>

                    <div id="medicineContainer">
                        <div class="medicine-row border rounded p-3 mb-3">
                            <div class="row g-2">
                                <div class="col-6 col-md-3">
                                    <label class="form-label small">약품명 *</label>
                                    <input type="text" name="items[0][medicine_name]" class="form-control" required>
                                </div>
                                <div class="col-6 col-md-2">
                                    <label class="form-label small">용량 *</label>
                                    <input type="text" name="items[0][dosage]" class="form-control" placeholder="예: 500mg" required>
                                </div>
                                <div class="col-6 col-md-2">
                                    <label class="form-label small">복용 횟수 *</label>
                                    <input type="text" name="items[0][frequency]" class="form-control" placeholder="예: 1일 3회" required>
                                </div>
                                <div class="col-6 col-md-2">
                                    <label class="form-label small">기간 *</label>
                                    <input type="text" name="items[0][duration]" class="form-control" placeholder="예: 7일" required>
                                </div>
                                <div class="col-10 col-md-2">
                                    <label class="form-label small">복용법</label>
                                    <input type="text" name="items[0][instructions]" class="form-control" placeholder="식후">
                                </div>
                                <div class="col-2 col-md-1 d-flex align-items-end">
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeMedicine(this)" disabled><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary">처방전 저장</button>
                        <a href="{{ route('doctor.prescriptions') }}" class="btn btn-light">취소</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let medicineIndex = 1;

function addMedicine() {
    const container = document.getElementById('medicineContainer');
    const html = `
        <div class="medicine-row border rounded p-3 mb-3">
            <div class="row g-2">
                <div class="col-6 col-md-3">
                    <label class="form-label small">약품명 *</label>
                    <input type="text" name="items[${medicineIndex}][medicine_name]" class="form-control" required>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small">용량 *</label>
                    <input type="text" name="items[${medicineIndex}][dosage]" class="form-control" placeholder="예: 500mg" required>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small">복용 횟수 *</label>
                    <input type="text" name="items[${medicineIndex}][frequency]" class="form-control" placeholder="예: 1일 3회" required>
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small">기간 *</label>
                    <input type="text" name="items[${medicineIndex}][duration]" class="form-control" placeholder="예: 7일" required>
                </div>
                <div class="col-10 col-md-2">
                    <label class="form-label small">복용법</label>
                    <input type="text" name="items[${medicineIndex}][instructions]" class="form-control" placeholder="식후">
                </div>
                <div class="col-2 col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeMedicine(this)"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        </div>`;
    container.insertAdjacentHTML('beforeend', html);
    medicineIndex++;
}

function removeMedicine(btn) {
    btn.closest('.medicine-row').remove();
}
</script>
@endpush
