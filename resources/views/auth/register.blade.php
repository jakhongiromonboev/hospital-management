<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>회원가입 - 병원 관리 시스템</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%); display: flex; align-items: center; justify-content: center; padding: 1rem; font-family: 'Malgun Gothic', 'Apple SD Gothic Neo', 'Noto Sans KR', sans-serif; }
        .register-card { max-width: 560px; width: 100%; background: #fff; border-radius: 16px; padding: 2.5rem; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        @media (max-width: 480px) { .register-card { padding: 1.5rem; border-radius: 12px; } }
        .login-icon { width: 70px; height: 70px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; }
        .login-icon i { font-size: 2rem; color: #fff; }
        .form-control:focus, .form-select:focus { border-color: #3b82f6; box-shadow: 0 0 0 0.2rem rgba(59,130,246,0.25); }
        .btn-primary { background: linear-gradient(135deg, #3b82f6, #1d4ed8); border: none; padding: 0.75rem; font-weight: 600; }
        .btn-primary:hover { background: linear-gradient(135deg, #2563eb, #1e40af); }
    </style>
</head>
<body>
    <div class="register-card">
        <div class="login-icon"><i class="bi bi-hospital"></i></div>
        <h3 class="text-center fw-bold mb-1">환자 회원가입</h3>
        <p class="text-center text-muted mb-4">환자 계정을 만드세요</p>

        @if($errors->any())
            <div class="alert alert-danger py-2">
                <ul class="mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">이름 *</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">이메일 *</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">비밀번호 *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">비밀번호 확인 *</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">전화번호</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">생년월일</label>
                    <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">성별</label>
                    <select name="gender" class="form-select">
                        <option value="">선택</option>
                        <option value="male" {{ old('gender') === 'male' ? 'selected' : '' }}>남성</option>
                        <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>여성</option>
                        <option value="other" {{ old('gender') === 'other' ? 'selected' : '' }}>기타</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-semibold">혈액형</label>
                    <select name="blood_group" class="form-select">
                        <option value="">선택</option>
                        @foreach(['A+','A-','B+','B-','AB+','AB-','O+','O-'] as $bg)
                            <option value="{{ $bg }}" {{ old('blood_group') === $bg ? 'selected' : '' }}>{{ $bg }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">주소</label>
                <textarea name="address" class="form-control" rows="2">{{ old('address') }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">가입하기</button>
            <p class="text-center mb-0">이미 계정이 있으신가요? <a href="{{ route('login') }}">로그인</a></p>
        </form>
    </div>
</body>
</html>
