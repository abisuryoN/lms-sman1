<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — LMS SMAN 1 Tajurhalang</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 30%, #BFDBFE 60%, #93C5FD 100%);
            position: relative; overflow: hidden;
        }

        /* Decorative blobs */
        body::before {
            content: ''; position: absolute; width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(59,130,246,0.15), transparent 70%);
            top: -100px; right: -100px; border-radius: 50%;
        }
        body::after {
            content: ''; position: absolute; width: 300px; height: 300px;
            background: radial-gradient(circle, rgba(14,165,233,0.12), transparent 70%);
            bottom: -50px; left: -50px; border-radius: 50%;
        }

        .login-card {
            background: rgba(255,255,255,0.92); backdrop-filter: blur(20px);
            border-radius: 20px; padding: 48px 40px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.08), 0 0 0 1px rgba(255,255,255,0.5);
            width: 100%; max-width: 420px;
            position: relative; z-index: 1;
            animation: cardIn 0.5s ease;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-logo {
            text-align: center; margin-bottom: 28px;
        }

        .login-logo img {
            width: 72px; height: 72px; margin-bottom: 14px;
            border-radius: 16px; box-shadow: 0 4px 12px rgba(59,130,246,0.15);
        }

        .login-logo h1 {
            font-size: 20px; font-weight: 700; color: #1E293B;
            margin-bottom: 4px;
        }

        .login-logo p {
            font-size: 13px; color: #64748B;
        }

        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block; font-size: 13px; font-weight: 500;
            color: #334155; margin-bottom: 6px;
        }

        .form-control {
            width: 100%; padding: 12px 16px;
            border: 1.5px solid #E2E8F0; border-radius: 10px;
            font-size: 14px; font-family: inherit;
            transition: all 0.2s ease; background: #fff; color: #1E293B;
        }

        .form-control:focus {
            outline: none; border-color: #3B82F6;
            box-shadow: 0 0 0 4px rgba(59,130,246,0.1);
        }

        .form-error { color: #EF4444; font-size: 12px; margin-top: 4px; }

        .remember-row {
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 20px; font-size: 13px; color: #64748B;
        }

        .remember-row input { accent-color: #3B82F6; width: 16px; height: 16px; }

        .btn-login {
            width: 100%; padding: 13px; border: none;
            background: linear-gradient(135deg, #3B82F6, #1D4ED8);
            color: #fff; font-size: 15px; font-weight: 600;
            border-radius: 10px; cursor: pointer;
            transition: all 0.2s ease; font-family: inherit;
        }

        .btn-login:hover {
            background: linear-gradient(135deg, #2563EB, #1E40AF);
            transform: translateY(-1px);
            box-shadow: 0 8px 20px rgba(59,130,246,0.3);
        }

        .alert-error {
            background: #FEE2E2; color: #991B1B; padding: 10px 14px;
            border-radius: 8px; font-size: 13px; margin-bottom: 16px;
            border: 1px solid #FECACA;
        }

        @media (max-width: 480px) {
            .login-card { margin: 16px; padding: 32px 24px; }
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
