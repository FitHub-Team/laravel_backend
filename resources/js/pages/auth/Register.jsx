import { useState } from "react";
import {
    Mail,
    Lock,
    Eye,
    EyeOff,
    ArrowLeft,
    ArrowRight,
    User,
    Briefcase,
    MapPin,
    Calendar,
    DollarSign,
    Award,
    Check,
} from "lucide-react";
import { useMutation } from "@tanstack/react-query";
import { Link, useNavigate } from "react-router-dom";
import api from "../../services/api";
import logoImage from "../../assets/logo.png";
import FlashMessage from "../../components/common/FlashMessage";

const RegisterCoach = () => {
    const navigate = useNavigate();

    // ===== STATE =====
    const [step, setStep] = useState(1);
    const [formData, setFormData] = useState({
        full_name: "",
        email: "",
        password: "",
        password_confirmation: "",
        specialization: "",
        experience: "",
        location: "",
        birth_year: "",
        price: "",
    });

    const [showPassword, setShowPassword] = useState(false);
    const [showConfirmPassword, setShowConfirmPassword] = useState(false);
    const [flashMessage, setFlashMessage] = useState("");
    const [flashType, setFlashType] = useState("success");
    const [agreed, setAgreed] = useState(false);

    const totalSteps = 3;
    const stepsInfo = [
        { id: 1, title: "البيانات الشخصية" },
        { id: 2, title: "البيانات المهنية" },
        { id: 3, title: "الأمان" },
    ];

    // ===== MUTATION =====
    const registerMutation = useMutation({
        mutationFn: async (registerData) => {
            const response = await api.post("/register", registerData);
            return response.data;
        },
        onSuccess: (data) => {
            localStorage.setItem("token", data.token);
            localStorage.setItem("user", JSON.stringify(data.user));
            localStorage.setItem(
                "flashMessage",
                "تم إنشاء حساب المدرب بنجاح 🎉",
            );
            navigate("/dashboard");
        },
        onError: (error) => {
            const errors = error?.response?.data?.errors;
            const firstError = errors ? Object.values(errors)[0]?.[0] : null;
            setFlashMessage(
                firstError ||
                    error?.response?.data?.message ||
                    "حدث خطأ أثناء إنشاء الحساب",
            );
            setFlashType("error");
        },
    });

    // ===== HANDLERS =====
    const handleChange = (e) => {
        const { name, value } = e.target;
        setFormData((prev) => ({ ...prev, [name]: value }));
    };

    const getPasswordStrength = (pass) => {
        let score = 0;
        if (pass.length >= 8) score++;
        if (/[A-Z]/.test(pass)) score++;
        if (/[a-z]/.test(pass)) score++;
        if (/\d/.test(pass)) score++;
        if (/[^A-Za-z0-9]/.test(pass)) score++;
        return score;
    };

    const passwordStrength = getPasswordStrength(formData.password);

    // ===== VALIDATION لكل خطوة =====
    const validateStep = (currentStep) => {
        if (currentStep === 1) {
            if (!formData.full_name.trim()) return "الرجاء إدخال الاسم الكامل";
            if (!formData.email.trim()) return "الرجاء إدخال البريد الإلكتروني";
            if (!/^.+@gmail\.com$/.test(formData.email))
                return "البريد الإلكتروني يجب أن يكون Gmail";
            if (!formData.birth_year) return "الرجاء إدخال سنة الميلاد";
            const year = Number(formData.birth_year);
            if (year < 1900 || year > new Date().getFullYear())
                return "سنة الميلاد غير صحيحة";
            if (!formData.location.trim()) return "الرجاء إدخال الموقع";
        }
        if (currentStep === 2) {
            if (!formData.specialization.trim()) return "الرجاء إدخال التخصص";
            if (formData.experience === "") return "الرجاء إدخال سنوات الخبرة";
            if (!formData.price || Number(formData.price) < 0)
                return "الرجاء إدخال سعر صحيح";
        }
        return null;
    };

    const handleNext = () => {
        const error = validateStep(step);
        if (error) {
            setFlashMessage(error);
            setFlashType("error");
            return;
        }
        setFlashMessage("");
        setStep((s) => Math.min(s + 1, totalSteps));
    };

    const handlePrev = () => {
        setFlashMessage("");
        setStep((s) => Math.max(s - 1, 1));
    };

    const handleSubmit = (e) => {
        e.preventDefault();

        // تحقق من كل الخطوات
        for (let i = 1; i <= totalSteps; i++) {
            const error = validateStep(i);
            if (error) {
                setFlashMessage(error);
                setFlashType("error");
                setStep(i);
                return;
            }
        }

        if (formData.password !== formData.password_confirmation) {
            setFlashMessage("كلمتا المرور غير متطابقتين");
            setFlashType("error");
            return;
        }

        if (passwordStrength < 5) {
            setFlashMessage(
                "كلمة المرور يجب أن تحتوي على 8 أحرف، كبيرة وصغيرة، رقم، ورمز",
            );
            setFlashType("error");
            return;
        }

        if (!agreed) {
            setFlashMessage("يجب الموافقة على الشروط والأحكام");
            setFlashType("error");
            return;
        }

        registerMutation.mutate({
            full_name: formData.full_name,
            email: formData.email,
            password: formData.password,
            role: "coach",
            specialization: formData.specialization,
            experience: Number(formData.experience),
            location: formData.location,
            birth_year: Number(formData.birth_year),
            price: Number(formData.price),
        });
    };

    // ===== STYLES مشتركة =====
    const inputClass =
        "w-full h-[50px] rounded-xl border border-slate-200 bg-slate-50 pr-11 pl-4 text-sm text-slate-700 outline-none transition-all placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10";

    const labelClass = "text-sm font-semibold text-[#173F32]";

    return (
        <div
            dir="rtl"
            className="min-h-screen bg-[#F5FAF8] flex items-center justify-center px-4 py-6 lg:py-10"
        >
            <FlashMessage
                message={flashMessage}
                type={flashType}
                onClose={() => setFlashMessage("")}
            />

            {/* خلفيات ديكوريتيف */}
            <div className="fixed top-0 right-0 w-[420px] h-[420px] bg-emerald-100/50 rounded-full blur-3xl -z-0 pointer-events-none" />
            <div className="fixed bottom-0 left-0 w-[350px] h-[350px] bg-teal-100/50 rounded-full blur-3xl -z-0 pointer-events-none" />

            <div className="relative z-10 w-full max-w-[1080px] bg-white rounded-[28px] shadow-[0_20px_70px_rgba(15,61,46,0.10)] overflow-hidden flex flex-col lg:flex-row lg:h-[92vh] lg:max-h-[820px]">
                {/* ===== الجانب الأيسر ===== */}
                <div className="hidden lg:flex lg:w-[42%] relative overflow-hidden bg-gradient-to-br from-[#0F3D2E] via-[#145A42] to-[#1F7A59] p-12 flex-col justify-between">
                    <div className="absolute -top-32 -right-32 w-[380px] h-[380px] rounded-full border border-white/10" />
                    <div className="absolute -bottom-40 -left-40 w-[500px] h-[500px] rounded-full border border-white/10" />
                    <div className="absolute top-1/3 -right-20 w-72 h-72 rounded-full bg-emerald-400/10 blur-3xl" />

                    <div className="relative z-10 flex justify-center">
                        <img
                            src={logoImage}
                            alt="SuperFit"
                            className="max-w-[220px] max-h-[220px] object-contain"
                        />
                    </div>

                    <div className="relative z-10 max-w-[360px]">
                        <span className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 border border-white/10 text-emerald-100 text-xs mb-5">
                            <span className="w-2 h-2 bg-emerald-300 rounded-full" />
                            انضم كمدرب معتمد
                        </span>
                        <h2 className="text-white text-3xl font-bold leading-[1.5] mb-4">
                            كن مدرباً،
                            <br />
                            <span className="text-emerald-300">
                                واصنع التغيير
                            </span>
                        </h2>
                        <p className="text-emerald-50/70 text-sm leading-7">
                            أنشئ ملفك الشخصي كمدرب واحصل على عملاء جدد، وإدارة
                            كاملة لخططهم التدريبية والغذائية
                        </p>
                    </div>

                    <div className="relative z-10 flex flex-col gap-3">
                        {[
                            "ملف شخصي احترافي",
                            "استقبال طلبات العملاء",
                            "إدارة كاملة للتدريبات",
                        ].map((item, i) => (
                            <div
                                key={i}
                                className="flex items-center gap-3 text-emerald-50/80 text-sm"
                            >
                                <span className="w-1.5 h-1.5 rounded-full bg-emerald-300" />
                                {item}
                            </div>
                        ))}
                    </div>
                </div>

                {/* ===== الجانب الأيمن: الفورم ===== */}
                <div className="w-full lg:w-[58%] relative flex flex-col">
                    <div className="flex-1 overflow-y-auto px-6 py-7 sm:px-10 lg:px-14 register-scroll">
                        <div className="w-full max-w-[460px] mx-auto">
                            {/* لوجو الموبايل */}
                            <div className="flex lg:hidden justify-center mb-6">
                                <img
                                    src={logoImage}
                                    alt="SuperFit"
                                    className="max-w-[130px] max-h-[55px] object-contain"
                                />
                            </div>

                            {/* رأس النموذج */}
                            <div className="mb-6">
                                <p className="text-emerald-600 text-sm font-semibold mb-2">
                                    انضم إلينا كمدرب ..
                                </p>
                                <h1 className="text-[#123F31] text-2xl sm:text-3xl font-bold mb-2">
                                    تسجيل حساب مدرب
                                </h1>
                                <p className="text-slate-400 text-sm leading-6">
                                    املأ بياناتك المهنية لإنشاء حسابك
                                </p>
                            </div>

                            {/* ===== Progress Steps ===== */}
                            <div className="mb-7">
                                <div className="flex items-center justify-between mb-3">
                                    {stepsInfo.map((s, i) => (
                                        <div
                                            key={s.id}
                                            className="flex items-center flex-1"
                                        >
                                            <div className="flex flex-col items-center gap-2">
                                                <div
                                                    className={`w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold transition-all duration-300 ${
                                                        step > s.id
                                                            ? "bg-emerald-500 text-white"
                                                            : step === s.id
                                                              ? "bg-emerald-500 text-white ring-4 ring-emerald-500/20"
                                                              : "bg-slate-100 text-slate-400"
                                                    }`}
                                                >
                                                    {step > s.id ? (
                                                        <Check size={16} />
                                                    ) : (
                                                        s.id
                                                    )}
                                                </div>
                                                <span
                                                    className={`text-[11px] font-semibold whitespace-nowrap ${
                                                        step >= s.id
                                                            ? "text-emerald-600"
                                                            : "text-slate-400"
                                                    }`}
                                                >
                                                    {s.title}
                                                </span>
                                            </div>
                                            {i < stepsInfo.length - 1 && (
                                                <div className="flex-1 h-[2px] mx-2 mt-[-18px] rounded-full bg-slate-100 overflow-hidden">
                                                    <div
                                                        className={`h-full bg-emerald-500 transition-all duration-500 ${
                                                            step > s.id
                                                                ? "w-full"
                                                                : "w-0"
                                                        }`}
                                                    />
                                                </div>
                                            )}
                                        </div>
                                    ))}
                                </div>
                            </div>

                            {/* ===== FORM ===== */}
                            <form
                                onSubmit={handleSubmit}
                                className="flex flex-col gap-4"
                            >
                                {/* ========== STEP 1 ========== */}
                                {step === 1 && (
                                    <div className="flex flex-col gap-4 animate-fadeIn">
                                        {/* الاسم */}
                                        <div className="flex flex-col gap-2">
                                            <label
                                                htmlFor="full_name"
                                                className={labelClass}
                                            >
                                                الاسم الكامل
                                            </label>
                                            <div className="relative">
                                                <User
                                                    size={18}
                                                    className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                                                />
                                                <input
                                                    type="text"
                                                    id="full_name"
                                                    name="full_name"
                                                    value={formData.full_name}
                                                    onChange={handleChange}
                                                    placeholder="أدخل اسمك الكامل"
                                                    maxLength={100}
                                                    className={inputClass}
                                                />
                                            </div>
                                        </div>

                                        {/* الإيميل */}
                                        <div className="flex flex-col gap-2">
                                            <label
                                                htmlFor="email"
                                                className={labelClass}
                                            >
                                                البريد الإلكتروني
                                                <span className="text-xs font-normal text-slate-400 mr-1">
                                                    (Gmail)
                                                </span>
                                            </label>
                                            <div className="relative">
                                                <Mail
                                                    size={18}
                                                    className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                                                />
                                                <input
                                                    type="email"
                                                    id="email"
                                                    name="email"
                                                    value={formData.email}
                                                    onChange={handleChange}
                                                    placeholder="example@gmail.com"
                                                    className={inputClass}
                                                />
                                            </div>
                                        </div>

                                        {/* سنة الميلاد + الموقع */}
                                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div className="flex flex-col gap-2">
                                                <label
                                                    htmlFor="birth_year"
                                                    className={labelClass}
                                                >
                                                    سنة الميلاد
                                                </label>
                                                <div className="relative">
                                                    <Calendar
                                                        size={18}
                                                        className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                                                    />
                                                    <input
                                                        type="number"
                                                        id="birth_year"
                                                        name="birth_year"
                                                        value={
                                                            formData.birth_year
                                                        }
                                                        onChange={handleChange}
                                                        placeholder="1995"
                                                        min={1900}
                                                        max={new Date().getFullYear()}
                                                        className={inputClass}
                                                    />
                                                </div>
                                            </div>

                                            <div className="flex flex-col gap-2">
                                                <label
                                                    htmlFor="location"
                                                    className={labelClass}
                                                >
                                                    الموقع
                                                </label>
                                                <div className="relative">
                                                    <MapPin
                                                        size={18}
                                                        className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                                                    />
                                                    <input
                                                        type="text"
                                                        id="location"
                                                        name="location"
                                                        value={
                                                            formData.location
                                                        }
                                                        onChange={handleChange}
                                                        placeholder="غزة"
                                                        maxLength={150}
                                                        className={inputClass}
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                )}

                                {/* ========== STEP 2 ========== */}
                                {step === 2 && (
                                    <div className="flex flex-col gap-4 animate-fadeIn">
                                        {/* التخصص */}
                                        <div className="flex flex-col gap-2">
                                            <label
                                                htmlFor="specialization"
                                                className={labelClass}
                                            >
                                                التخصص
                                            </label>
                                            <div className="relative">
                                                <Award
                                                    size={18}
                                                    className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                                                />
                                                <input
                                                    type="text"
                                                    id="specialization"
                                                    name="specialization"
                                                    value={
                                                        formData.specialization
                                                    }
                                                    onChange={handleChange}
                                                    placeholder="كمال أجسام"
                                                    maxLength={150}
                                                    className={inputClass}
                                                />
                                            </div>
                                        </div>

                                        {/* الخبرة */}
                                        <div className="flex flex-col gap-2">
                                            <label
                                                htmlFor="experience"
                                                className={labelClass}
                                            >
                                                سنوات الخبرة
                                            </label>
                                            <div className="relative">
                                                <Briefcase
                                                    size={18}
                                                    className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                                                />
                                                <input
                                                    type="number"
                                                    id="experience"
                                                    name="experience"
                                                    value={formData.experience}
                                                    onChange={handleChange}
                                                    placeholder="0"
                                                    min={0}
                                                    className={inputClass}
                                                />
                                            </div>
                                        </div>

                                        {/* السعر */}
                                        <div className="flex flex-col gap-2">
                                            <label
                                                htmlFor="price"
                                                className={labelClass}
                                            >
                                                سعر الجلسة
                                                <span className="text-xs font-normal text-slate-400 mr-1">
                                                    (بالدولار)
                                                </span>
                                            </label>
                                            <div className="relative">
                                                <DollarSign
                                                    size={18}
                                                    className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                                                />
                                                <input
                                                    type="number"
                                                    id="price"
                                                    name="price"
                                                    value={formData.price}
                                                    onChange={handleChange}
                                                    placeholder="0.00"
                                                    min={0}
                                                    step="0.01"
                                                    className={inputClass}
                                                />
                                            </div>
                                        </div>
                                    </div>
                                )}

                                {/* ========== STEP 3 ========== */}
                                {step === 3 && (
                                    <div className="flex flex-col gap-4 animate-fadeIn">
                                        {/* كلمة المرور */}
                                        <div className="flex flex-col gap-2">
                                            <label
                                                htmlFor="password"
                                                className={labelClass}
                                            >
                                                كلمة المرور
                                            </label>
                                            <div className="relative">
                                                <Lock
                                                    size={18}
                                                    className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                                                />
                                                <input
                                                    type={
                                                        showPassword
                                                            ? "text"
                                                            : "password"
                                                    }
                                                    id="password"
                                                    name="password"
                                                    value={formData.password}
                                                    onChange={handleChange}
                                                    placeholder="Aa1! مثال"
                                                    minLength={8}
                                                    className="w-full h-[50px] rounded-xl border border-slate-200 bg-slate-50 pr-11 pl-12 text-sm text-slate-700 outline-none transition-all placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                                                />
                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        setShowPassword(
                                                            !showPassword,
                                                        )
                                                    }
                                                    className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-600 transition"
                                                >
                                                    {showPassword ? (
                                                        <EyeOff size={18} />
                                                    ) : (
                                                        <Eye size={18} />
                                                    )}
                                                </button>
                                            </div>

                                            {formData.password && (
                                                <div className="flex flex-col gap-1.5 mt-1">
                                                    <div className="flex gap-1">
                                                        {[1, 2, 3, 4, 5].map(
                                                            (i) => (
                                                                <div
                                                                    key={i}
                                                                    className={`h-1 flex-1 rounded-full transition-all ${
                                                                        i <=
                                                                        passwordStrength
                                                                            ? passwordStrength <=
                                                                              2
                                                                                ? "bg-red-400"
                                                                                : passwordStrength <=
                                                                                    4
                                                                                  ? "bg-amber-400"
                                                                                  : "bg-emerald-500"
                                                                            : "bg-slate-200"
                                                                    }`}
                                                                />
                                                            ),
                                                        )}
                                                    </div>
                                                    <p
                                                        className={`text-xs ${
                                                            passwordStrength <=
                                                            2
                                                                ? "text-red-500"
                                                                : passwordStrength <=
                                                                    4
                                                                  ? "text-amber-500"
                                                                  : "text-emerald-600"
                                                        }`}
                                                    >
                                                        {passwordStrength <= 2
                                                            ? "ضعيفة"
                                                            : passwordStrength <=
                                                                4
                                                              ? "متوسطة"
                                                              : "قوية"}
                                                    </p>
                                                </div>
                                            )}
                                        </div>

                                        {/* تأكيد كلمة المرور */}
                                        <div className="flex flex-col gap-2">
                                            <label
                                                htmlFor="password_confirmation"
                                                className={labelClass}
                                            >
                                                تأكيد كلمة المرور
                                            </label>
                                            <div className="relative">
                                                <Lock
                                                    size={18}
                                                    className="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"
                                                />
                                                <input
                                                    type={
                                                        showConfirmPassword
                                                            ? "text"
                                                            : "password"
                                                    }
                                                    id="password_confirmation"
                                                    name="password_confirmation"
                                                    value={
                                                        formData.password_confirmation
                                                    }
                                                    onChange={handleChange}
                                                    placeholder="أعد إدخال كلمة المرور"
                                                    minLength={8}
                                                    className="w-full h-[50px] rounded-xl border border-slate-200 bg-slate-50 pr-11 pl-12 text-sm text-slate-700 outline-none transition-all placeholder:text-slate-400 focus:border-emerald-500 focus:bg-white focus:ring-4 focus:ring-emerald-500/10"
                                                />
                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        setShowConfirmPassword(
                                                            !showConfirmPassword,
                                                        )
                                                    }
                                                    className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-emerald-600 transition"
                                                >
                                                    {showConfirmPassword ? (
                                                        <EyeOff size={18} />
                                                    ) : (
                                                        <Eye size={18} />
                                                    )}
                                                </button>
                                            </div>
                                        </div>

                                        {/* الشروط */}
                                        <label className="flex items-start gap-2 cursor-pointer text-slate-500 text-sm mt-1">
                                            <input
                                                type="checkbox"
                                                checked={agreed}
                                                onChange={(e) =>
                                                    setAgreed(e.target.checked)
                                                }
                                                className="w-4 h-4 mt-0.5 accent-emerald-600 cursor-pointer"
                                            />
                                            <span>
                                                أوافق على{" "}
                                                <a
                                                    href="#"
                                                    className="text-emerald-600 font-semibold hover:text-emerald-700"
                                                >
                                                    الشروط والأحكام
                                                </a>{" "}
                                                وسياسة الخصوصية
                                            </span>
                                        </label>
                                    </div>
                                )}

                                {/* ===== أزرار التنقل ===== */}
                                <div className="flex items-center gap-3 mt-2">
                                    {step > 1 && (
                                        <button
                                            type="button"
                                            onClick={handlePrev}
                                            disabled={
                                                registerMutation.isPending
                                            }
                                            className="group flex items-center justify-center gap-2 h-[50px] px-6 rounded-xl border border-slate-200 bg-white text-slate-600 font-semibold text-sm transition-all hover:bg-slate-50 hover:border-slate-300 disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            <ArrowRight
                                                size={18}
                                                className="transition-transform duration-300 group-hover:translate-x-1"
                                            />
                                            السابق
                                        </button>
                                    )}

                                    {step < totalSteps ? (
                                        <button
                                            type="button"
                                            onClick={handleNext}
                                            className="group flex-1 h-[50px] rounded-xl bg-gradient-to-l from-[#0F8F68] to-[#12A878] text-white font-bold text-sm flex items-center justify-center gap-3 shadow-[0_8px_20px_rgba(16,185,129,0.20)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_25px_rgba(16,185,129,0.30)]"
                                        >
                                            <span>التالي</span>
                                            <ArrowLeft
                                                size={18}
                                                className="transition-transform duration-300 group-hover:-translate-x-1"
                                            />
                                        </button>
                                    ) : (
                                        <button
                                            type="submit"
                                            disabled={
                                                registerMutation.isPending
                                            }
                                            className="group flex-1 h-[50px] rounded-xl bg-gradient-to-l from-[#0F8F68] to-[#12A878] text-white font-bold text-sm flex items-center justify-center gap-3 shadow-[0_8px_20px_rgba(16,185,129,0.20)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_25px_rgba(16,185,129,0.30)] disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            <span>
                                                {registerMutation.isPending
                                                    ? "جاري إنشاء الحساب..."
                                                    : "إنشاء حساب المدرب"}
                                            </span>
                                            {!registerMutation.isPending && (
                                                <Check
                                                    size={18}
                                                    className="transition-transform duration-300 group-hover:scale-110"
                                                />
                                            )}
                                        </button>
                                    )}
                                </div>
                            </form>

                            {/* التذييل */}
                            <div className="mt-5 pt-4 border-t border-slate-100 text-center">
                                <p className="text-sm text-slate-400">
                                    لديك حساب بالفعل؟
                                    <Link
                                        to="/login"
                                        className="mr-1.5 text-emerald-600 font-bold hover:text-emerald-700"
                                    >
                                        تسجيل الدخول
                                    </Link>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {/* ستايل السكرول + أنيميشن */}
            <style>{`
        .register-scroll::-webkit-scrollbar {
          width: 6px;
        }
        .register-scroll::-webkit-scrollbar-track {
          background: transparent;
        }
        .register-scroll::-webkit-scrollbar-thumb {
          background: #d1fae5;
          border-radius: 999px;
        }
        .register-scroll::-webkit-scrollbar-thumb:hover {
          background: #6ee7b7;
        }
        .register-scroll {
          scrollbar-width: thin;
          scrollbar-color: #d1fae5 transparent;
        }
        @keyframes fadeIn {
          from { opacity: 0; transform: translateX(15px); }
          to { opacity: 1; transform: translateX(0); }
        }
        .animate-fadeIn {
          animation: fadeIn 0.35s ease-out;
        }
      `}</style>
        </div>
    );
};

export default RegisterCoach;
