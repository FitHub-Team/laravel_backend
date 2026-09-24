
<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>تسجيل دخول الأدمن - SuperFit</title>

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">

    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap"
        rel="stylesheet"
    >

    <!-- Font Awesome -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    >

    <!-- CSS -->
    <link
        rel="stylesheet"
        href="{{ asset('front/css/style.css') }}"
    >
    <script src="https://cdn.tailwindcss.com"></script>

</head>


<body>

<div
    dir="rtl"
    class="min-h-screen bg-[#F5FAF8] flex items-center justify-center px-5 py-10 relative overflow-hidden"
>

    <!-- Background decoration -->

    <div
        class="absolute top-0 right-0 w-[420px] h-[420px] bg-emerald-100/50 rounded-full blur-3xl -z-0"
    ></div>

    <div
        class="absolute bottom-0 left-0 w-[350px] h-[350px] bg-teal-100/50 rounded-full blur-3xl -z-0"
    ></div>


    <!-- Main Card -->

    <div
        class="relative z-10 w-full max-w-[1050px] min-h-[620px] bg-white rounded-[28px] shadow-[0_20px_70px_rgba(15,61,46,0.10)] overflow-hidden flex flex-col lg:flex-row"
    >


        <!-- ================= LEFT SIDE ================= -->

        <div
            class="hidden lg:flex lg:w-[48%] relative overflow-hidden bg-gradient-to-br from-[#0F3D2E] via-[#145A42] to-[#1F7A59] p-12 flex-col justify-between"
        >

            <!-- Decorative circles -->

            <div
                class="absolute -top-32 -right-32 w-[380px] h-[380px] rounded-full border border-white/10"
            ></div>

            <div
                class="absolute -bottom-40 -left-40 w-[500px] h-[500px] rounded-full border border-white/10"
            ></div>

            <div
                class="absolute top-1/3 -right-20 w-72 h-72 rounded-full bg-emerald-400/10 blur-3xl"
            ></div>


            <!-- Logo -->

            <div class="relative z-10 flex justify-center">

                <img
                    src="{{ asset('front/imag/super5.png') }}"
                    alt="SuperFit"
                    class="max-w-[250px] max-h-[250px] object-contain"
                >

            </div>


            <!-- Main Text -->

            <div class="relative z-10 max-w-[400px]">

                <span
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-emerald-100 text-xs mb-6"
                >

                    <span class="w-2 h-2 bg-emerald-300 rounded-full"></span>

                    منصة SuperFit للأدمن

                </span>


                <h2
                    class="text-white text-4xl font-bold leading-[1.5] mb-5"
                >

                    طوّر أدائك،
                    <br>

                    <span class="text-emerald-300">
                        وأدر منصتك
                    </span>

                </h2>


                <p
                    class="text-emerald-50/70 text-sm leading-7"
                >

                    أدِر المستخدمين والمدربين والاشتراكات،
                    وتابع جميع عمليات منصة SuperFit من مكان واحد.

                </p>

            </div>


            <!-- Bottom -->

            <div
                class="relative z-10 flex items-center gap-3 text-white/50 text-xs"
            >

                <div class="h-px w-10 bg-white/20"></div>

                <span>
                    Smart Fitness Management
                </span>

            </div>

        </div>


        <!-- ================= RIGHT SIDE ================= -->

        <div
            class="w-full lg:w-[52%] flex items-center justify-center px-7 py-10 sm:px-12 lg:px-16"
        >

            <div class="w-full max-w-[400px]">


                <!-- Mobile Logo -->

                <div class="flex lg:hidden justify-center mb-8">

                    <div
                        class="w-[160px] h-[65px] flex items-center justify-center"
                    >

                        <img
                            src="{{ asset('front/imag/super5.png') }}"
                            alt="SuperFit"
                            class="max-w-[145px] max-h-[60px] object-contain"
                        >

                    </div>

                </div>


                <!-- Header -->

                <div class="mb-9">

                    <p
                        class="text-emerald-600 text-sm font-semibold mb-3"
                    >
                    مرحبا بعودتك
                    </p>


                    <h1
                        class="text-[#123F31] text-3xl font-bold mb-3"
                    >
                        تسجيل الدخول
                    </h1>


                    <p
                        class="text-slate-400 text-sm leading-6"
                    >
                        سجّل دخولك إلى حساب الأدمن للوصول إلى لوحة التحكم
                    </p>

                </div>


                <!-- Laravel Errors -->

                @if ($errors->any())

                    <div
                        class="mb-5 rounded-xl bg-red-50 border border-red-100 px-4 py-3 text-red-600 text-sm"
                    >

                        @foreach ($errors->all() as $error)

                            <p class="mb-1 last:mb-0">
                                {{ $error }}
                            </p>

                        @endforeach

                    </div>

                @endif


                <!-- Form -->

                <form
                    action="{{ route('admin.login.submit') }}"
                    method="POST"
                    class="flex flex-col gap-5"
                >

                    @csrf


                    <!-- Name -->

                    <div class="flex flex-col gap-2">

                        <label
                            for="name"
                            class="text-sm font-semibold text-[#173F32]"
                        >
                            الاسم
                        </label>


                        <div class="relative">

                            <i
                                class="fa-solid fa-user absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                            ></i>


                            <input
                                type="text"
                                id="name"
                                name="name"
                                value="{{ old('name') }}"
                                placeholder="أدخل اسمك"
                                required
                                class="w-full h-[52px] rounded-xl border border-slate-200 bg-slate-50 pr-12 pl-4 text-sm text-slate-700 outline-none transition-all placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                            >

                        </div>

                    </div>


                    <!-- Email -->

                    <div class="flex flex-col gap-2">

                        <label
                            for="email"
                            class="text-sm font-semibold text-[#173F32]"
                        >
                            البريد الإلكتروني
                        </label>


                        <div class="relative">

                            <i
                                class="fa-solid fa-envelope absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                            ></i>


                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="أدخل بريدك الإلكتروني"
                                required
                                class="w-full h-[52px] rounded-xl border border-slate-200 bg-slate-50 pr-12 pl-4 text-sm text-slate-700 outline-none transition-all placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                            >

                        </div>

                    </div>


                    <!-- Password -->

                    <div class="flex flex-col gap-2">

                        <label
                            for="password"
                            class="text-sm font-semibold text-[#173F32]"
                        >
                            كلمة المرور
                        </label>


                   <div class="relative">
    <input
        type="password"
        id="password"
        name="password"
        placeholder="كلمة المرور"
        class="w-full px-4 py-3 pr-12 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-teal-500"
    >

    <button
        type="button"
        id="togglePassword"
        class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-teal-600"
    >
        <i id="passwordIcon" class="fa-solid fa-eye-slash"></i>
    </button>
</div>
                    </div>


                    <!-- Options -->

                    <div
                        class="flex items-center justify-between text-sm mt-1"
                    >

                        <label
                            class="flex items-center gap-2 cursor-pointer text-slate-500"
                        >

                            <input
                                type="checkbox"
                                name="remember"
                                value="1"
                                class="w-4 h-4 accent-emerald-600 cursor-pointer"
                            >

                            <span>
                                تذكرني
                            </span>

                        </label>


                        <a
                            href="#"
                            class="text-emerald-600 font-semibold hover:text-emerald-700 transition"
                        >
                            نسيت كلمة المرور؟
                        </a>

                    </div>


                    <!-- Login Button -->

                    <button
                        type="submit"
                        class="group mt-3 w-full h-[52px] rounded-xl bg-gradient-to-l from-[#0F8F68] to-[#12A878] text-white font-bold text-sm flex items-center justify-center gap-3 shadow-[0_8px_20px_rgba(16,185,129,0.20)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_25px_rgba(16,185,129,0.30)]"
                    >

                        <span>
                            تسجيل الدخول
                        </span>


                        <i
                            class="fa-solid fa-arrow-left text-sm transition-transform duration-300 group-hover:-translate-x-1"
                        ></i>

                    </button>

                </form>


                <!-- Footer -->

                <div
                    class="mt-8 pt-6 border-t border-slate-100 text-center"
                >

                    <p class="text-sm text-slate-400">

                        SuperFit Admin Panel

                    </p>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- Show / Hide Password -->

<script>

    const togglePassword = document.getElementById('togglePassword');

    const password = document.getElementById('password');

    const passwordIcon = document.getElementById('passwordIcon');


    togglePassword.addEventListener('click', function () {

        if (password.type === 'password') {

            password.type = 'text';

            passwordIcon.classList.remove('fa-eye');

            passwordIcon.classList.add('fa-eye-slash');

        } else {

            password.type = 'password';

            passwordIcon.classList.remove('fa-eye-slash');

            passwordIcon.classList.add('fa-eye');

        }

    });

</script>

<script src="{{ asset('front/js/login.js') }}"></script>
</body>

</html>



