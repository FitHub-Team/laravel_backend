import { useEffect, useState } from "react";
import { Mail, Lock, Eye, EyeOff, ArrowLeft } from "lucide-react";
import { useMutation } from "@tanstack/react-query";
import { useNavigate, Link } from "react-router-dom";
import api from "../../services/api";
import logoImage from "../../assets/logo.png";
import FlashMessage from "../../components/common/FlashMessage";

const Login = () => {
    const navigate = useNavigate();

    // ===== STATE =====
    const [formData, setFormData] = useState({
        email: "",
        password: "",
    });
    const [showPassword, setShowPassword] = useState(false);
    const [flashMessage, setFlashMessage] = useState("");
    const [flashType, setFlashType] = useState("success");

    // ===== FLASH MESSAGE (عند إعادة التوجيه من صفحة أخرى) =====
    useEffect(() => {
        const message = localStorage.getItem("flashMessage");

        if (message) {
            setFlashMessage(message);
            localStorage.removeItem("flashMessage");
        }
    }, []);

    // ===== LOGIN MUTATION (إرسال البيانات للـ Laravel) =====
    const loginMutation = useMutation({
        mutationFn: async (loginData) => {
            const response = await api.post("/login", loginData);
            return response.data;
        },
        onSuccess: (data) => {
            if (data.user.role !== "coach") {
                setFlashMessage("هذا الحساب ليس حساب مدرب");
                setFlashType("error");
                return;
            }
            // حفظ البيانات في localStorage
            localStorage.setItem("token", data.token);
            localStorage.setItem("user", JSON.stringify(data.user));
            // الذهاب للـ Dashboard
            navigate("/dashboard");
        },
        onError: (error) => {
            setFlashMessage("البريد الإلكتروني أو كلمة المرور غير صحيحة");
            setFlashType("error");
        },
    });

    // ===== HANDLERS =====
    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData((prev) => ({
            ...prev,
            [name]: value,
        }));
    };

    const handleSubmit = (e) => {
        e.preventDefault();
        loginMutation.mutate(formData);
    };

    // ===== RENDER =====
    return (
        <div
            dir="rtl"
            className="min-h-screen bg-[#F5FAF8] flex items-center justify-center px-5 py-10"
        >
            <FlashMessage
                message={flashMessage}
                type={flashType}
                onClose={() => setFlashMessage("")}
            />

            {/* خلفية ديكوريتيف */}
            <div className="absolute top-0 right-0 w-[420px] h-[420px] bg-emerald-100/50 rounded-full blur-3xl -z-0" />
            <div className="absolute bottom-0 left-0 w-[350px] h-[350px] bg-teal-100/50 rounded-full blur-3xl -z-0" />

            {/* البطاقة الرئيسية */}
            <div className="relative z-10 w-full max-w-[1050px] min-h-[620px] bg-white rounded-[28px] shadow-[0_20px_70px_rgba(15,61,46,0.10)] overflow-hidden flex flex-col lg:flex-row">
                {/* ===== الجانب الأيسر (الديسكتوب فقط) ===== */}
                <div className="hidden lg:flex lg:w-[48%] relative overflow-hidden bg-gradient-to-br from-[#0F3D2E] via-[#145A42] to-[#1F7A59] p-12 flex-col justify-between">
                    <div className="absolute -top-32 -right-32 w-[380px] h-[380px] rounded-full border border-white/10" />
                    <div className="absolute -bottom-40 -left-40 w-[500px] h-[500px] rounded-full border border-white/10" />
                    <div className="absolute top-1/3 -right-20 w-72 h-72 rounded-full bg-emerald-400/10 blur-3xl" />

                    {/* اللوجو */}
                    <div className="relative z-10 flex justify-center">
                        <img
                            src={logoImage}
                            alt="SuperFit"
                            className="max-w-[250px] max-h-[250px] object-contain"
                        />
                    </div>

                    {/* النص الرئيسي */}
                    <div className="relative z-10 max-w-[400px]">
                        <span className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-emerald-100 text-xs mb-6">
                            <span className="w-2 h-2 bg-emerald-300 rounded-full" />
                            منصة SuperFit للمدربين
                        </span>
                        <h2 className="text-white text-4xl font-bold leading-[1.5] mb-5">
                            طوّر أداءك،
                            <br />
                            <span className="text-emerald-300">
                                واصنع فرقاً
                            </span>
                        </h2>
                        <p className="text-emerald-50/70 text-sm leading-7">
                            أدِر تدريبات عملائك، تابع تقدمهم، ونظّم خططهم
                            التدريبية والغذائية
                        </p>
                    </div>

                    {/* التذييل */}
                    <div className="relative z-10 flex items-center gap-3 text-white/50 text-xs">
                        <div className="h-px w-10 bg-white/20" />
                        <span>Smart Fitness Management</span>
                    </div>
                </div>

                {/* ===== الجانب الأيمن (نموذج الدخول) ===== */}
                <div className="w-full lg:w-[52%] flex items-center justify-center px-7 py-10 sm:px-12 lg:px-16">
                    <div className="w-full max-w-[400px]">
                        {/* لوجو الموبايل */}
                        <div className="flex lg:hidden justify-center mb-8">
                            <img
                                src={logoImage}
                                alt="SuperFit"
                                className="max-w-[145px] max-h-[60px] object-contain"
                            />
                        </div>

                        {/* رأس النموذج */}
                        <div className="mb-9">
                            <p className="text-emerald-600 text-sm font-semibold mb-3">
                                مرحباً بعودتك ..
                            </p>
                            <h1 className="text-[#123F31] text-3xl font-bold mb-3">
                                تسجيل الدخول
                            </h1>
                            <p className="text-slate-400 text-sm leading-6">
                                سجّل دخولك إلى حساب المدرب للوصول إلى لوحة
                                التحكم
                            </p>
                        </div>

                        {/* النموذج */}
                        <form
                            onSubmit={handleSubmit}
                            className="flex flex-col gap-5"
                        >
                            {/* حقل البريد الإلكتروني */}
                            <div className="flex flex-col gap-2">
                                <label
                                    htmlFor="email"
                                    className="text-sm font-semibold text-[#173F32]"
                                >
                                    البريد الإلكتروني
                                </label>
                                <div className="relative">
                                    <Mail
                                        size={19}
                                        className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                                    />
                                    <input
                                        type="email"
                                        id="email"
                                        name="email"
                                        value={formData.email}
                                        onChange={handleChange}
                                        placeholder="أدخل بريدك الإلكتروني"
                                        required
                                        className="w-full h-[52px] rounded-xl border border-slate-200 bg-slate-50 pr-12 pl-4 text-sm text-slate-700 outline-none transition-all placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                                    />
                                </div>
                            </div>

                            {/* حقل كلمة المرور */}
                            <div className="flex flex-col gap-2">
                                <label
                                    htmlFor="password"
                                    className="text-sm font-semibold text-[#173F32]"
                                >
                                    كلمة المرور
                                </label>
                                <div className="relative">
                                    <Lock
                                        size={19}
                                        className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                                    />
                                    <input
                                        type={
                                            showPassword ? "text" : "password"
                                        }
                                        id="password"
                                        name="password"
                                        value={formData.password}
                                        onChange={handleChange}
                                        placeholder="أدخل كلمة المرور"
                                        required
                                        className="w-full h-[52px] rounded-xl border border-slate-200 bg-slate-50 pr-12 pl-12 text-sm text-slate-700 outline-none transition-all placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                                    />
                                    <button
                                        type="button"
                                        onClick={() =>
                                            setShowPassword(!showPassword)
                                        }
                                        className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-600 transition"
                                    >
                                        {showPassword ? (
                                            <EyeOff size={19} />
                                        ) : (
                                            <Eye size={19} />
                                        )}
                                    </button>
                                </div>
                            </div>

                            {/* خيارات إضافية */}
                            <div className="flex items-center justify-between text-sm mt-1">
                                <label className="flex items-center gap-2 cursor-pointer text-slate-500">
                                    <input
                                        type="checkbox"
                                        className="w-4 h-4 accent-emerald-600 cursor-pointer"
                                    />
                                    <span>تذكرني</span>
                                </label>
                                <a
                                    href="#"
                                    className="text-emerald-600 font-semibold hover:text-emerald-700"
                                >
                                    نسيت كلمة المرور؟
                                </a>
                            </div>

                            {/* زر الدخول */}
                            <button
                                type="submit"
                                disabled={loginMutation.isPending}
                                className="group mt-3 w-full h-[52px] rounded-xl bg-gradient-to-l from-[#0F8F68] to-[#12A878] text-white font-bold text-sm flex items-center justify-center gap-3 shadow-[0_8px_20px_rgba(16,185,129,0.20)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_25px_rgba(16,185,129,0.30)] disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <span>
                                    {loginMutation.isPending
                                        ? "جاري الدخول..."
                                        : "تسجيل الدخول"}
                                </span>
                                {!loginMutation.isPending && (
                                    <ArrowLeft
                                        size={18}
                                        className="transition-transform duration-300 group-hover:-translate-x-1"
                                    />
                                )}
                            </button>
                        </form>

                        {/* التذييل */}
                        <div className="mt-8 pt-6 border-t border-slate-100 text-center">
                            <p className="text-sm text-slate-400">
                                ليس لديك حساب؟
                                <Link
                                    to="/register"
                                    className="mr-1.5 text-emerald-600 font-bold hover:text-emerald-700"
                                >
                                    إنشاء حساب كوتش
                                </Link>
                            </p>
                            <p className="text-sm text-slate-400"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
};

export default Login;
