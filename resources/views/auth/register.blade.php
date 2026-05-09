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
        /* RESET & BASE */
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

        /* ANIMASI KUNCI (KEYFRAMES) */
        @keyframes floatUp {
            0% { opacity: 0; transform: translateY(40px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeIn {
            0% { opacity: 0; transform: translateX(-20px); }
            100% { opacity: 1; transform: translateX(0); }
        }

        /* CONTAINER UTAMA */
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid rgba(148, 148, 148, 0.3);
            border-radius: 30px;
            padding: 50px 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0,0,0,0.05);
            backdrop-filter: blur(10px);
            
            animation: floatUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .register-title {
            text-align: center;
            font-size: 32px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 40px;
            font-style: normal;
            letter-spacing: -0.5px;
            
            opacity: 0;
            animation: fadeIn 0.6s ease-out 0.2s forwards;
        }

        /* FORM ELEMENTS */
        .form-group {
            margin-bottom: 25px;
            opacity: 0;
            animation: fadeIn 0.6s ease-out forwards;
        }

        /* Animasi Berurutan (Staggered) */
        .form-group:nth-child(3) { animation-delay: 0.3s; } /* Username */
        .form-group:nth-child(4) { animation-delay: 0.4s; } /* Email */
        .form-group:nth-child(5) { animation-delay: 0.5s; } /* Password */
        .form-group:nth-child(6) { animation-delay: 0.6s; } /* Confirm Password */

        .form-group label {
            display: block;
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 10px;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .input-wrapper {
            display: flex;
            align-items: center;
            background: #f1f5f9;
            border-radius: 20px;
            padding: 12px 18px;
            gap: 12px;
            border: 2px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .input-wrapper:focus-within {
            background: #ffffff;
            border-color: #a84c60;
            box-shadow: 0 0 0 4px rgba(168, 76, 96, 0.15);
            transform: translateY(-2px);
        }

        .form-group:focus-within label {
            color: #a84c60;
        }

        .input-wrapper.error {
            border-color: #e53e3e;
            background: #fff5f5;
        }

        .input-wrapper svg {
            width: 20px;
            height: 20px;
            color: #a0aec0;
            flex-shrink: 0;
            transition: all 0.3s ease;
        }

        .input-wrapper:focus-within svg {
            color: #a84c60;
            transform: scale(1.1);
        }

        .input-wrapper input {
            border: none;
            background: transparent;
            outline: none;
            font-size: 14px;
            color: #2d3748;
            width: 100%;
            font-family: inherit;
        }

        .input-wrapper input::placeholder {
            color: #a0aec0;
            transition: opacity 0.3s ease;
        }

        .input-wrapper:focus-within input::placeholder {
            opacity: 0.5;
        }

        .error-message {
            color: #e53e3e;
            font-size: 12px;
            margin-top: 6px;
            display: block;
            font-weight: 500;
            animation: fadeIn 0.3s ease-out forwards;
        }

        /* TOMBOL REGISTER */
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
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(168, 76, 96, 0.3);
            
            opacity: 0;
            animation: fadeIn 0.6s ease-out 0.7s forwards; /* Delay tombol diperbesar karena form lebih banyak */
        }

        .register-btn::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
            transform: skewX(-25deg);
            transition: all 0.6s ease;
        }

        .register-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(168, 76, 96, 0.4);
        }

        .register-btn:hover::after {
            left: 150%;
        }

        .register-btn:active {
            transform: translateY(1px);
            box-shadow: 0 2px 10px rgba(168, 76, 96, 0.3);
        }

        .arrow {
            font-size: 18px;
            transition: transform 0.3s ease;
        }

        .register-btn:hover .arrow {
            transform: translateX(5px);
        }

        /* LINK LOGIN */
        .login-link-wrapper {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #718096;
            opacity: 0;
            animation: fadeIn 0.6s ease-out 0.8s forwards;
        }

        .login-link {
            color: #a84c60;
            text-decoration: none;
            font-weight: 700;
            position: relative;
            transition: color 0.3s ease;
        }

        .login-link::after {
            content: '';
            position: absolute;
            width: 100%;
            transform: scaleX(0);
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: #a84c60;
            transform-origin: bottom right;
            transition: transform 0.3s cubic-bezier(0.86, 0, 0.07, 1);
        }

        .login-link:hover {
            color: #8b4557;
        }

        .login-link:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
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