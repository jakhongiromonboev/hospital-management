@extends('layouts.app')
@section('page-title', isset($doctor) ? '의사 수정' : '의사 등록')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header py-3">{{ isset($doctor) ? '의사 정보 수정' : '새 의사 등록' }}</div>
            <div class="card-body">
                <form method="POST" action="{{ isset($doctor) ? route('admin.doctors.update', $doctor) : route('admin.doctors.store') }}">
                    @csrf
                    @if(isset($doctor)) @method('PUT') @endif

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">이름 *</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $doctor->name ?? '') }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">이메일 *</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $doctor->email ?? '') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">비밀번호 {{ isset($doctor) ? '(변경 시에만 입력)' : '*' }}</label>
                            <input type="password" name="password" class="form-control" {{ isset($doctor) ? '' : 'required' }}>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">전화번호</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $doctor->phone ?? '') }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">전문과목 *</label>
                        <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $doctor->specialization ?? '') }}" required placeholder="예: 순환기내과, 정형외과">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">약력</label>
                        <textarea name="bio" class="form-control" rows="3">{{ old('bio', $doctor->bio ?? '') }}</textarea>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">{{ isset($doctor) ? '수정 저장' : '등록' }}</button>
                        <a href="{{ route('admin.doctors') }}" class="btn btn-light">취소</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
