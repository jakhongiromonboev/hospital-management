<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('auth.login.title') }} - {{ __('auth.login.app_title') }}</title>
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
        .lang-switcher-corner {
            position: fixed; top: 20px; right: 20px;
            display: inline-flex; align-items: center; gap: 2px;
            background: rgba(255,255,255,0.12); backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 999px; padding: 3px 4px 3px 10px;
            z-index: 10;
        }
        .lang-switcher-corner .lang-icon { font-size: 0.85rem; color: rgba(255,255,255,0.7); margin-right: 4px; }
        .lang-switcher-corner .lang-option {
            padding: 4px 11px; font-size: 0.72rem; font-weight: 700;
            letter-spacing: 0.06em; color: rgba(255,255,255,0.65);
            border-radius: 999px; text-decoration: none;
            transition: all 0.2s ease;
        }
        .lang-switcher-corner .lang-option:hover { color: #fff; }
        .lang-switcher-corner .lang-option.active {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: #fff !important;
            box-shadow: 0 1px 3px rgba(59,130,246,0.4);
        }
    </style>
</head>
<body>
    <div class="lang-switcher-corner" role="group" aria-label="Language switcher">
        <i class="bi bi-translate lang-icon"></i>
        <a href="{{ route('locale.switch', 'ko') }}" class="lang-option {{ app()->getLocale() === 'ko' ? 'active' : '' }}">KO</a>
        <a href="{{ route('locale.switch', 'en') }}" class="lang-option {{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
    </div>

    <div class="login-card">
        <div class="login-icon"><i class="bi bi-hospital"></i></div>
        <h3 class="text-center fw-bold mb-1">{{ __('auth.login.app_title') }}</h3>
        <p class="text-center text-muted mb-4">{{ __('auth.login.subtitle') }}</p>

        @if($errors->any())
            <div class="alert alert-danger py-2">
                @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">{{ __('auth.login.email') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="{{ __('auth.login.email_placeholder') }}" required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">{{ __('auth.login.password') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="{{ __('auth.login.password_placeholder') }}" required>
                </div>
            </div>
            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label" for="remember">{{ __('auth.login.remember') }}</label>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3">{{ __('auth.login.submit') }}</button>
            <p class="text-center mb-0">{{ __('auth.login.no_account') }} <a href="{{ route('register') }}">{{ __('auth.login.register_link') }}</a></p>
        </form>

        <hr>
        <div class="text-muted small">
            <p class="mb-1 fw-semibold">{{ __('auth.login.demo_heading') }}</p>
            <p class="mb-0">{{ __('auth.login.demo_admin') }}: admin@hospital.com</p>
            <p class="mb-0">{{ __('auth.login.demo_doctor') }}: doctor1@hospital.com</p>
            <p class="mb-0">{{ __('auth.login.demo_patient') }}: patient1@hospital.com</p>
            <p class="mb-0">{{ __('auth.login.demo_password') }}: <code>password</code></p>
        </div>
    </div>
</body>
</html>
