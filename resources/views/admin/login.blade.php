<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول الأدمن</title>
    <link rel="stylesheet" href="{{ asset('front/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
</div>

<body>
    <div class="login-container">
        <div class="form-section">
            <h2>تسجيل الدخول - أدمن</h2>
@if ($errors->any())
<div style="color: red; margin-bottom: 15px;">
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
            <img src="{{ asset('front/imag/سوبر فت.jpg') }}" alt="SuperFit Admin" class="signup-image">
        </div>
    </div>
</body>

</html>
