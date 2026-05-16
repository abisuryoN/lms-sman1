<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — LMS SMAN 1 Tajurhalang</title>
    <link rel="icon" type="image/png" href="{{ asset('assets/logo-sekolah.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Outfit', 'Inter', sans-serif;
            min-height: 100vh;
            display: flex; 
            align-items: center; 
            justify-content: center;
            background: #F8FAFC;
            background-image: 
                radial-gradient(at 0% 0%, rgba(59, 130, 246, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 0%, rgba(147, 51, 234, 0.1) 0px, transparent 50%),
                radial-gradient(at 100% 100%, rgba(59, 130, 246, 0.15) 0px, transparent 50%),
                radial-gradient(at 0% 100%, rgba(147, 51, 234, 0.1) 0px, transparent 50%);
            position: relative; 
            overflow-x: hidden;
            padding: 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px; 
            padding: 48px 40px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05), 0 0 0 1px rgba(255, 255, 255, 0.8);
            width: 100%; 
            max-width: 420px;
            position: relative; 
            z-index: 1;
            animation: cardIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-logo {
            text-align: center; 
            margin-bottom: 32px;
        }

        .login-logo img {
            width: 80px; 
            height: 80px; 
            margin-bottom: 16px;
            border-radius: 20px; 
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.1);
        }

        .login-logo h1 {
            font-size: 22px; 
            font-weight: 800; 
            color: #0F172A;
            margin-bottom: 6px;
            letter-spacing: -0.5px;
        }

        .login-logo p {
            font-size: 14px; 
            color: #64748B;
        }

        .form-group { margin-bottom: 20px; }

        .form-label {
            display: block; 
            font-size: 13px; 
            font-weight: 600;
            color: #475569; 
            margin-bottom: 8px;
            padding-left: 4px;
        }

        .form-control {
            width: 100%; 
            padding: 14px 16px;
            border: 1.5px solid #E2E8F0; 
            border-radius: 12px;
            font-size: 15px; 
            font-family: inherit;
            transition: all 0.2s ease; 
            background: #FFFFFF; 
            color: #0F172A;
        }

        .form-control:focus {
            outline: none; 
            border-color: #3B82F6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            background: #FFFFFF;
        }

        .btn-login {
            width: 100%; 
            padding: 14px; 
            border: none;
            background: #2563EB;
            color: #FFFFFF; 
            font-size: 16px; 
            font-weight: 700;
            border-radius: 12px; 
            cursor: pointer;
            transition: all 0.25s; 
            font-family: inherit;
            margin-top: 10px;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }

        .btn-login:hover {
            background: #1D4ED8;
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .alert-error {
            background: #FFF1F2; 
            color: #E11D48; 
            padding: 12px 16px;
            border-radius: 12px; 
            font-size: 13px; 
            margin-bottom: 20px;
            border: 1px solid #FFE4E6;
            font-weight: 500;
        }

        @media (max-width: 480px) {
            .login-card { padding: 36px 24px; border-radius: 28px; }
        }
    </style>
</head>
<body>
    <div class="login-card">
        <div class="login-logo">
            <img src="{{ asset('assets/logo-sekolah.png') }}" alt="Logo SMAN 1 Tajurhalang"
                 onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 72 72%22><rect fill=%22%233B82F6%22 width=%2272%22 height=%2272%22 rx=%2216%22/><text x=%2236%22 y=%2244%22 fill=%22white%22 font-size=%2228%22 text-anchor=%22middle%22 font-weight=%22bold%22>S1</text></svg>'">
            <h1>LMS SMAN 1 Tajurhalang</h1>
            <p>Learning Management System</p>
        </div>

        @if($errors->any())
            <div class="alert-error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Email</label>
                <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Masukkan email" required autofocus>
            </div>
            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <div class="remember-row">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Ingat saya</label>
            </div>
            <button type="submit" class="btn-login">Masuk</button>
        </form>
    </div>
</body>
</html>
