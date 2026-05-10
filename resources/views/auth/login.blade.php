<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Plushie Shop</title>
    
    @vite(['resources/js/app.js'])

    @vite(['resources/css/auth.css'])
</head>
<body class="page-login">
    <div class="login-container">
        <h1 class="login-title">Selamat Datang!</h1>

        <!-- Form action diubah ke route('login') -->
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Menampilkan status session (misal pesan berhasil register) -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Email Address -->
            <div class="form-group">
                <label for="email">{{ __('Email Address') }}</label>
                <div class="input-wrapper @error('email') error @enderror">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                        <polyline points="22,6 12,13 2,6"></polyline>
                    </svg>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autocomplete="email"
                        placeholder="Email Address"
                        autofocus
                    />
                </div>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div class="form-group">
                <label for="password">{{ __('Password') }}</label>
                <div class="input-wrapper @error('password') error @enderror">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <input 
                        id="password" 
                        type="password" 
                        name="password" 
                        required 
                        autocomplete="current-password"
                        placeholder="Password"
                    />
                </div>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Login Button -->
            <button type="submit" class="login-btn">
                {{ __('Sign in') }} <span class="arrow">→</span>
            </button>

            <!-- Register Link -->
            <p class="register-link-wrapper">
                {{ __('Don\'t have an account?') }} 
                <a href="{{ route('register') }}" class="register-link">{{ __('Create an account') }}</a>
            </p>
        </form>
    </div>
</body>
</html>