<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>로그인 - 병원 관리 시스템</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 50%, #0f172a 100%); display: flex; align-items: center; justify-content: center; padding: 1rem; font-family: 'Malgun Gothic', 'Apple SD Gothic Neo', 'Noto Sans KR', sans-serif; }
        .login-card { max-width: 420px; width: 100%; background: #fff; border-radius: 16px; padding: 2.5rem; box-shadow: 0 20px 60px rgba(0,0,0,0.3); }
        @media (max-width: 480px) { .login-card { padding: 1.5rem; border-radius: 12px; } }
        .login-icon { width: 70px; height: 70px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; }
        .login-icon i { font-size: 2rem; color: #fff; }
        .form-control:focus { border-color: #3b82f6; box-shadow: 0 0 0 0.2rem rgba(59,130,246,0.25); }
        .btn-primary { background: linear-gradient(135deg, #3b82f6, #1d4ed8); border: none; padding: 0.75rem; font-weight: 600; }
        .btn-primary:hover { background: linear-gradient(135deg, #2563eb, #1e40af); }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-icon"><i class="bi bi-hospital"></i></div>
        <h3 class="text-center fw-bold mb-1">병원 관리 시스템</h3>
        <p class="text-center text-muted mb-4">계정으로 로그인하세요</p>

        @if($errors->any())
            <div class="alert alert-danger py-2">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">이메일</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="이메일을 입력하세요" required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">비밀번호</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="비밀번호를 입력하세요" required>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">로그인 상태 유지</label>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">로그인</button>
            <p class="text-center mb-0">계정이 없으신가요? <a href="{{ route('register') }}">회원가입</a></p>
        </form>

        <hr>
        <div class="text-muted small">
            <p class="mb-1 fw-semibold">데모 계정:</p>
            <p class="mb-0">관리자: admin@hospital.com</p>
            <p class="mb-0">의사: doctor1@hospital.com</p>
            <p class="mb-0">환자: patient1@hospital.com</p>
            <p class="mb-0">비밀번호: <code>password</code></p>
        </div>
    </div>
</body>
</html>
