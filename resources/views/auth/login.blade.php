<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login - Plushie Shop</title>
    
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
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid rgba(148, 148, 148, 0.3); /* Border diperhalus */
            border-radius: 30px;
            padding: 50px 40px;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0,0,0,0.05); /* Shadow lebih modern & soft */
            backdrop-filter: blur(10px);
            
            /* Pemicu Animasi Masuk */
            animation: floatUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        .login-title {
            text-align: center;
            font-size: 32px;
            font-weight: 600; /* Sedikit ditebalkan agar lebih tegas */
            color: #2c3e50;
            margin-bottom: 40px;
            font-style: normal; /* Dibuat normal agar lebih modern, hapus baris ini jika tetap ingin italic */
            letter-spacing: -0.5px;
            
            /* Animasi Staggered (Muncul berurutan) */
            opacity: 0;
            animation: fadeIn 0.6s ease-out 0.2s forwards;
        }

        /* FORM ELEMENTS */
        .form-group {
            margin-bottom: 25px;
            opacity: 0;
            animation: fadeIn 0.6s ease-out 0.4s forwards; /* Delay 0.4s */
        }

        .form-group:nth-child(3) {
            animation-delay: 0.5s; /* Delay untuk input password */
        }

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
            background: #f1f5f9; /* Warna background input lebih cerah/modern */
            border-radius: 20px;
            padding: 12px 18px;
            gap: 12px;
            border: 2px solid transparent;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); /* Transisi lebih mulus */
        }

        /* EFEK SAAT INPUT DIKLIK (FOCUS) */
        .input-wrapper:focus-within {
            background: #ffffff;
            border-color: #a84c60; /* Mengikuti warna tema tombol */
            box-shadow: 0 0 0 4px rgba(168, 76, 96, 0.15); /* Efek glow/pulse */
            transform: translateY(-2px); /* Input sedikit terangkat */
        }

        .form-group:focus-within label {
            color: #a84c60; /* Warna label ikut berubah saat input aktif */
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
            color: #a84c60; /* Icon ikut berwarna saat aktif */
            transform: scale(1.1); /* Icon sedikit membesar */
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
            opacity: 0.5; /* Placeholder meredup saat mulai mengetik */
        }

        .error-message {
            color: #e53e3e;
            font-size: 12px;
            margin-top: 6px;
            display: block;
            font-weight: 500;
            animation: fadeIn 0.3s ease-out forwards;
        }

        /* TOMBOL LOGIN */
        .login-btn {
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
            animation: fadeIn 0.6s ease-out 0.6s forwards; /* Muncul paling akhir */
        }

        /* Efek Kilap pada Tombol saat di-hover */
        .login-btn::after {
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

        .login-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(168, 76, 96, 0.4); /* Glow lebih kuat */
        }

        .login-btn:hover::after {
            left: 150%; /* Menggerakkan kilap dari kiri ke kanan */
        }

        .login-btn:active {
            transform: translateY(1px);
            box-shadow: 0 2px 10px rgba(168, 76, 96, 0.3);
        }

        .arrow {
            font-size: 18px;
            transition: transform 0.3s ease;
        }

        .login-btn:hover .arrow {
            transform: translateX(5px); /* Panah maju sedikit saat di-hover */
        }

        /* LINK REGISTER */
        .register-link-wrapper {
            text-align: center;
            margin-top: 25px;
            font-size: 14px;
            color: #718096;
            opacity: 0;
            animation: fadeIn 0.6s ease-out 0.7s forwards;
        }

        .register-link {
            color: #a84c60;
            text-decoration: none;
            font-weight: 700;
            position: relative;
            transition: color 0.3s ease;
        }

        /* Garis bawah animasi bergaya modern */
        .register-link::after {
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

        .register-link:hover {
            color: #8b4557;
        }

        .register-link:hover::after {
            transform: scaleX(1);
            transform-origin: bottom left;
        }
    </style>

</head>
<body>
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