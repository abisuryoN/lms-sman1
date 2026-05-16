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
            height: 100vh;
            display: flex; 
            align-items: center; 
            justify-content: center;
            background: #1e293b;
            position: relative; 
            overflow: hidden;
            padding: 20px;
        }

        /* Buang panah naik turun di input angka */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }
        input[type=number] {
            -moz-appearance: textfield;
        }

        /* --- Atur Background Slide --- */
        .slideshow-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
            background: #0f172a;
        }

        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
            transform: scale(1.05);
        }

        .slide.active {
            opacity: 1;
            transform: scale(1);
        }

        .slideshow-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.2) 0%, rgba(30, 58, 138, 0.2) 100%);
            z-index: 1;
        }

        /* --- Layout Konten --- */
        .login-content {
            position: relative;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 60px;
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
        }

        .welcome-section {
            flex: 1;
            color: white;
            max-width: 450px;
            animation: fadeInRight 0.8s ease-out;
            text-align: left;
        }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .welcome-section h2 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 16px;
            line-height: 1.3;
            text-shadow: 0 4px 12px rgba(0,0,0,0.5);
        }

        .welcome-section p {
            font-size: 15px;
            line-height: 1.6;
            opacity: 0.9;
            text-shadow: 0 2px 8px rgba(0,0,0,0.4);
        }

        .motivation-badge {
            display: inline-block;
            padding: 5px 14px;
            background: rgba(37, 99, 235, 0.2);
            backdrop-filter: blur(10px);
            border-radius: 100px;
            border: 1px solid rgba(255,255,255,0.3);
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 20px;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 24px; 
            padding: 36px 32px;
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.2);
            width: 100%; 
            max-width: 380px;
            animation: cardIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes cardIn {
            from { opacity: 0; transform: scale(0.95) translateY(30px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        @media (max-width: 992px) {
            .login-content { flex-direction: column-reverse; gap: 32px; text-align: center; }
            .welcome-section { max-width: 100%; text-align: center; padding: 0 20px; }
            .welcome-section h2 { font-size: 24px; }
            .welcome-section p { font-size: 14px; }
            .motivation-badge { margin-bottom: 12px; }
        }

        @media (max-width: 480px) {
            .login-card { padding: 32px 20px; border-radius: 24px; }
            .welcome-section { display: none; }
        }

        .login-logo {
            text-align: center;
            margin-bottom: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .login-logo img {
            width: 70px; 
            height: 70px; 
            margin-bottom: 12px;
            border-radius: 18px; 
            box-shadow: 0 8px 16px rgba(59, 130, 246, 0.1);
        }

        .login-logo h1 {
            font-size: 20px; 
            font-weight: 800; 
            color: #0F172A;
            margin-bottom: 4px;
            letter-spacing: -0.5px;
        }

        .login-logo p {
            font-size: 13px; 
            color: #64748B;
        }

        .form-group { margin-bottom: 16px; }

        .form-label {
            display: block; 
            font-size: 12px; 
            font-weight: 600;
            color: #475569; 
            margin-bottom: 6px;
            padding-left: 4px;
        }

        .form-control {
            width: 100%; 
            padding: 12px 14px;
            border: 1.5px solid #E2E8F0; 
            border-radius: 10px;
            font-size: 14px; 
            font-family: inherit;
            transition: all 0.2s ease; 
            background: rgba(255, 255, 255, 0.95); 
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
            padding: 12px; 
            border: none;
            background: #2563EB;
            color: #FFFFFF; 
            font-size: 15px; 
            font-weight: 700;
            border-radius: 10px; 
            cursor: pointer;
            transition: all 0.25s; 
            font-family: inherit;
            margin-top: 8px;
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
            padding: 10px 14px;
            border-radius: 10px; 
            font-size: 12px; 
            margin-bottom: 16px;
            border: 1px solid #FFE4E6;
            font-weight: 500;
        }

        @media (max-width: 480px) {
            .login-card { padding: 32px 20px; border-radius: 24px; }
        }
    </style>
</head>
<body>
    <div class="slideshow-container">
        <!-- Daftar Slide Pake Foto Picsum -->
        <div class="slide active" data-bg="https://picsum.photos/id/1/1920/1080"></div>
        <div class="slide" data-bg="https://picsum.photos/id/20/1920/1080"></div>
        <div class="slide" data-bg="https://picsum.photos/id/119/1920/1080"></div>
        <div class="slide" data-bg="https://picsum.photos/id/180/1920/1080"></div>
        <div class="slide" data-bg="https://picsum.photos/id/201/1920/1080"></div>
        <div class="slide" data-bg="https://picsum.photos/id/367/1920/1080"></div>
        <div class="slide" data-bg="https://picsum.photos/id/445/1920/1080"></div>
        <div class="slide" data-bg="https://picsum.photos/id/1073/1920/1080"></div>
        <div class="slideshow-overlay"></div>
    </div>

    <div class="login-content">
        <div class="login-card">
            <div class="login-logo">
                <img src="{{ asset('assets/logo-sekolah.png') }}" alt="Logo SMAN 1 Tajurhalang"
                     onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 72 72%22><rect fill=%22%233B82F6%22 width=%2272%22 height=%2272%22 rx=%2216%22/><text x=%2236%22 y=%2244%22 fill=%22white%22 font-size=%2228%22 text-anchor=%22middle%22 font-weight=%22bold%22>S1</text></svg>'">
                <h1>LMS SMAN 1 Tajurhalang</h1>
                <p>Portal Pembelajaran Digital</p>
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
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label">Verifikasi Keamanan</label>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <div style="background: rgba(241, 245, 249, 0.8); padding: 10px 14px; border-radius: 10px; font-weight: 700; color: #334155; font-size: 14px; border: 1.5px solid #E2E8F0; min-width: 90px; text-align: center;">
                            {{ $captcha_question }} = 
                        </div>
                        <input type="number" name="captcha" class="form-control" placeholder="?" required style="text-align: center;">
                    </div>
                </div>

                <div class="remember-row" style="margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                    <input type="checkbox" id="remember" name="remember" style="width: 15px; height: 15px; cursor: pointer;">
                    <label for="remember" style="font-size: 13px; color: #64748B; cursor: pointer;">Ingat saya</label>
                </div>
                <button type="submit" class="btn-login">Masuk</button>
            </form>
        </div>

        <div class="welcome-section">
            <div class="motivation-badge">OFFICIAL LMS</div>
            <h2 id="motivation-title">Selamat Datang di LMS SMAN 1 Tajurhalang</h2>
            <p id="motivation-desc">Belajar Lebih Mudah, Terstruktur, dan Modern untuk masa depan yang lebih gemilang.</p>
        </div>
    </div>

    <script>
        // Logika buat gonta-ganti slide otomatis
        document.addEventListener('DOMContentLoaded', function() {
            const slides = document.querySelectorAll('.slide');
            const titles = [
                "Selamat Datang di LMS SMAN 1 Tajurhalang",
                "Belajar Lebih Mudah, Terstruktur, dan Modern",
                "Membentuk Generasi Cerdas, Disiplin, dan Berprestasi",
                "Inovasi Pembelajaran Digital Tanpa Batas",
                "Raih Cita-cita Bersama Pendidikan Berkualitas",
                "Eksplorasi Ilmu dengan Fasilitas Modern",
                "Portal Digital SMAN 1 Tajurhalang",
                "Teknologi untuk Masa Depan Pendidikan"
            ];
            const descs = [
                "Belajar Lebih Mudah, Terstruktur, dan Modern untuk masa depan yang lebih gemilang.",
                "Platform pembelajaran digital terintegrasi untuk mendukung kegiatan belajar mengajar yang efektif.",
                "Wujudkan impian melalui pendidikan berkualitas dengan teknologi mutakhir.",
                "Kemudahan akses materi dan tugas kapan saja dan di mana saja untuk siswa dan guru.",
                "Kami berkomitmen memberikan pengalaman belajar terbaik bagi seluruh civitas akademika.",
                "Transformasi pendidikan menuju era digital yang inklusif dan kolaboratif.",
                "Pusat informasi dan kegiatan belajar mengajar berbasis teknologi.",
                "Membangun ekosistem belajar yang modern, aman, dan efisien."
            ];
            
            const titleEl = document.getElementById('motivation-title');
            const descEl = document.getElementById('motivation-desc');
            
            let currentSlide = 0;
            const slideCount = slides.length;

            // Pasang foto dari data-bg
            slides.forEach(slide => {
                const imgUrl = slide.getAttribute('data-bg');
                if (imgUrl) {
                    const img = new Image();
                    img.onload = () => {
                        slide.style.backgroundImage = `url('${imgUrl}')`;
                    };
                    img.onerror = () => {
                        slide.style.background = 'linear-gradient(135deg, #1e293b 0%, #334155 100%)';
                        console.warn('Image failed to load:', imgUrl);
                    };
                    img.src = imgUrl;
                }
            });

            function nextSlide() {
                slides[currentSlide].classList.remove('active');
                currentSlide = (currentSlide + 1) % slideCount;
                slides[currentSlide].classList.add('active');

                // Ganti teks pake efek pudar
                if (titleEl && descEl) {
                    titleEl.style.opacity = 0;
                    descEl.style.opacity = 0;
                    
                    setTimeout(() => {
                        titleEl.innerText = titles[currentSlide % titles.length];
                        descEl.innerText = descs[currentSlide % descs.length];
                        titleEl.style.opacity = 1;
                        descEl.style.opacity = 0.9;
                    }, 500);
                }
            }

            // Gaya transisi pudar buat teks
            if (titleEl && descEl) {
                titleEl.style.transition = 'opacity 0.5s ease-in-out';
                descEl.style.transition = 'opacity 0.5s ease-in-out';
            }

            setInterval(nextSlide, 5000);
        });
    </script>
</body>
</html>
