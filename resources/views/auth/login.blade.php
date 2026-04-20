<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Impacto Móvil — Iniciar sesión</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Figtree', sans-serif;
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .login-card {
            display: flex;
            width: 100%;
            max-width: 820px;
            min-height: 500px;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 8px 32px rgba(0,0,0,0.12);
        }

        /* ── Panel izquierdo ── */
        .login-left {
            width: 42%;
            background: #29abe2;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2.5rem 2rem;
            gap: 1.25rem;
        }

        .brand-block {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }

        .brand-name {
            font-size: 26px;
            letter-spacing: 1.5px;
            line-height: 1;
            font-family: 'Arial Black', Arial, sans-serif;
            display: flex;
            align-items: baseline;
        }

        .brand-impacto { color: #ffffff; font-weight: 900; font-style: italic; }
        .brand-movil   { color: #1a1a1a; font-weight: 900; font-style: italic; }

        .brand-tag {
            font-size: 10px;
            color: rgba(255,255,255,0.85);
            letter-spacing: 2px;
            text-transform: uppercase;
            font-family: Arial, sans-serif;
            font-weight: 400;
            margin-top: 2px;
        }

        .login-divider {
            width: 40px;
            height: 1.5px;
            background: rgba(255,255,255,0.35);
            border-radius: 2px;
        }

        .slogan {
            font-size: 12px;
            color: rgba(255,255,255,0.8);
            text-align: center;
            font-style: italic;
            line-height: 1.6;
            font-family: Arial, sans-serif;
        }

        /* ── Panel derecho ── */
        .login-right {
            flex: 1;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 2.5rem 2.5rem;
        }

        .login-right h2 {
            font-size: 20px;
            font-weight: 600;
            color: #1a1a2e;
            margin-bottom: 6px;
        }

        .login-right .subtitle {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 2rem;
        }

        .form-group { margin-bottom: 1.25rem; }

        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.6px;
        }

        .form-group input {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            font-size: 14px;
            background: #f9fafb;
            color: #1a1a2e;
            outline: none;
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .form-group input:focus {
            border-color: #29abe2;
            box-shadow: 0 0 0 3px rgba(41,171,226,0.12);
            background: #fff;
        }

        .form-error {
            font-size: 12px;
            color: #ef4444;
            margin-top: 4px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 1.5rem;
        }

        .form-check input[type=checkbox] {
            width: 15px;
            height: 15px;
            accent-color: #29abe2;
        }

        .form-check label {
            font-size: 13px;
            color: #6b7280;
        }

        .btn-login {
            width: 100%;
            padding: 11px;
            background: #29abe2;
            color: #ffffff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            letter-spacing: 0.3px;
            transition: background 0.15s;
        }

        .btn-login:hover { background: #1a8fc4; }

        .forgot {
            text-align: center;
            margin-top: 1.25rem;
            font-size: 12px;
            color: #6b7280;
        }

        .forgot a {
            color: #29abe2;
            text-decoration: none;
            font-weight: 500;
        }

        .forgot a:hover { text-decoration: underline; }

        .footer-note {
            margin-top: 2rem;
            padding-top: 1.25rem;
            border-top: 1px solid #f3f4f6;
            font-size: 11px;
            color: #9ca3af;
            text-align: center;
        }

        .session-status {
            background: #ecfdf5;
            border: 1px solid #6ee7b7;
            color: #065f46;
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 1.25rem;
        }
    </style>
</head>
<body>

<div class="login-card">

    {{-- Panel izquierdo --}}
    <div class="login-left">

        <svg width="90" height="70" viewBox="0 0 90 70" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16 52 Q7 35 16 18" stroke="white" stroke-width="5.5" fill="none" stroke-linecap="round"/>
            <path d="M26 46 Q19 35 26 24" stroke="white" stroke-width="5.5" fill="none" stroke-linecap="round"/>
            <path d="M64 46 Q71 35 64 24" stroke="white" stroke-width="5.5" fill="none" stroke-linecap="round"/>
            <path d="M74 52 Q83 35 74 18" stroke="white" stroke-width="5.5" fill="none" stroke-linecap="round"/>
            <rect x="41.5" y="33" width="7" height="22" rx="3" fill="white"/>
            <circle cx="45" cy="24" r="4.5" fill="white"/>
        </svg>

        <div class="brand-block">
            <div class="brand-name">
                <span class="brand-impacto">IMPACTO</span><span class="brand-movil">MÓVIL</span>
            </div>
            <div class="brand-tag">Distribuidor Autorizado</div>
        </div>

        <div class="login-divider"></div>

        <div class="slogan">Juntos construyendo<br>grandes conexiones</div>

    </div>

    {{-- Panel derecho --}}
    <div class="login-right">

        <h2>Bienvenido</h2>
        <p class="subtitle">Ingresa tus credenciales para continuar</p>

        @if (session('status'))
            <div class="session-status">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <label for="email">Correo electrónico</label>
                <input id="email" type="email" name="email"
                       value="{{ old('email') }}"
                       placeholder="usuario@impactomovil.com"
                       required autofocus autocomplete="username" />
                @error('email')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Contraseña</label>
                <input id="password" type="password" name="password"
                       placeholder="••••••••"
                       required autocomplete="current-password" />
                @error('password')
                    <p class="form-error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-check">
                <input id="remember_me" type="checkbox" name="remember" />
                <label for="remember_me">Recordar sesión</label>
            </div>

            <button type="submit" class="btn-login">Iniciar sesión</button>

            @if (Route::has('password.request'))
                <div class="forgot">
                    <a href="{{ route('password.request') }}">¿Olvidaste tu contraseña?</a>
                </div>
            @endif

        </form>

        <div class="footer-note">Impacto Móvil &copy; {{ date('Y') }} &middot; Acceso restringido</div>

    </div>
</div>

</body>
</html>