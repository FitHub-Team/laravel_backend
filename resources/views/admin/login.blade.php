<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول الأدمن</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>

<body>

    <div class="login-container">
        <div class="form-section">
            <div class="brand-mark">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round">
                    <path d="M11 20A7 7 0 0 1 4 13c0-6 6-10 15-11 0 9-3 15-8 18z"></path>
                    <path d="M4 13c3 0 6-1 8-3"></path>
                </svg>
            </div>
            <h2>تسجيل الدخول - أدمن</h2>
            <p class="sub">أهلاً بعودتك، سجّل دخولك لمتابعة إدارة SuperFit.</p>

            @if ($errors->any())
                <div class="error-box">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="post">
                @csrf

                <div class="input-group">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" placeholder="الاسم" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" placeholder="الايميل" name="email" value="{{ old('email') }}" required>
                </div>
                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" placeholder="كلمة المرور" name="password" required>
                </div>
                <button type="submit" class="register-btn">دخول</button>
            </form>
        </div>
        <div class="image-section">
            <img src="{{ asset('front/imag/super1.jpeg') }}" alt="SuperFit Admin" class="signup-image">
        </div>
    </div>

</body>

</html>
