<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register - Plushie Shop</title>
    
    <!-- Memanggil aset JS/CSS bawaan Laravel (opsional agar fitur background berjalan lancar) -->
    @vite(['resources/js/app.js'])

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .register-container {
            background: white;
            border: 3px solid #949494;
            border-radius: 30px;
            padding: 50px 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.6);
        }

        .register-title {
            text-align: center;
            font-size: 32px;
            font-weight: 400;
            color: #333;
            margin-bottom: 40px;
            font-style: italic;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .input-wrapper {
            display: flex;
            align-items: center;
            background: #d0d0d0;
            border-radius: 20px;
            padding: 12px 18px;
            gap: 12px;
            border: 2px solid transparent;
            transition: border-color 0.2s;
        }

        .input-wrapper:focus-within {
            border-color: #5b9bd5;
        }

        .input-wrapper.error {
            border-color: #d32f2f;
        }

        .input-wrapper svg {
            width: 20px;
            height: 20px;
            color: #666;
            flex-shrink: 0;
        }

        .input-wrapper input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 14px;
            color: #333;
            width: 100%;
            font-family: inherit;
        }

        .input-wrapper input::placeholder {
            color: #999;
        }

        .error-message {
            color: #d32f2f;
            font-size: 12px;
            margin-top: 5px;
            display: block;
        }

        .register-btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #8b4557 0%, #a84c60 100%);
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 30px;
            transition: transform 0.2s, box-shadow 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 69, 87, 0.3);
        }

        .register-btn:active {
            transform: translateY(0);
        }

        .arrow {
            font-size: 18px;
        }

        .login-link-wrapper {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }

        .login-link {
            color: #d32f2f;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }

        .login-link:hover {
            color: #b71c1c;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <h1 class="register-title">Selamat Datang!</h1>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Username -->
            <div class="form-group">
                <label for="username">{{ __('Username') }}</label>
                <div class="input-wrapper @error('username') error @enderror">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                    <input 
                        id="username" 
                        type="text" 
                        name="username" 
                        value="{{ old('username') }}" 
                        required 
                        autocomplete="username"
                        placeholder="Username"
                        autofocus
                    />
                </div>
                @error('username')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

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
                        autocomplete="new-password"
                        placeholder="Password"
                    />
                </div>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="form-group">
                <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                <div class="input-wrapper @error('password_confirmation') error @enderror">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                    </svg>
                    <input 
                        id="password_confirmation" 
                        type="password" 
                        name="password_confirmation" 
                        required 
                        autocomplete="new-password"
                        placeholder="Confirm Password"
                    />
                </div>
                @error('password_confirmation')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <!-- Register Button -->
            <button type="submit" class="register-btn">
                {{ __('Register') }} <span class="arrow">→</span>
            </button>

            <!-- Login Link -->
            <p class="login-link-wrapper">
                {{ __('Already registered?') }} 
                <a href="{{ route('login') }}" class="login-link">{{ __('Sign in') }}</a>
            </p>
        </form>
    </div>
</body>
</html>