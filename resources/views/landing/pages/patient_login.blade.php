<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso pacientes - JM Clínica</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-[linear-gradient(180deg,#f7fbfb_0%,#eef4f5_100%)] text-slate-800">
    <div class="mx-auto flex min-h-screen max-w-6xl items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
        <div class="w-full max-w-md rounded-[28px] border border-[#dbe7e8] bg-white p-6 shadow-[0_24px_60px_rgba(35,49,51,0.08)] sm:p-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-500 transition hover:text-slate-700">
                <span>←</span>
                <span>Volver a la web</span>
            </a>

            <div class="mt-6 text-center">
                <div class="inline-flex h-16 w-16 items-center justify-center rounded-2xl bg-[#6ab7bd]/10 text-2xl font-extrabold tracking-[-0.06em] text-[#4d9fa6]">
                    JM
                </div>

                <h1 class="mt-5 text-3xl font-semibold tracking-[-0.03em] text-slate-900">
                    Acceso pacientes
                </h1>

                <p class="mt-3 text-sm leading-7 text-slate-500">
                    Entra con tu cuenta o crea un nuevo perfil para gestionar tus citas.
                </p>
            </div>

            @if (session('status'))
                <div class="mt-6 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-6 rounded-2xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="mt-8 rounded-2xl bg-slate-100 p-1.5">
                <div class="grid grid-cols-2 gap-1.5">
                    <button
                        type="button"
                        id="tab-login"
                        class="rounded-xl px-4 py-3 text-sm font-semibold text-slate-500 transition"
                        onclick="showForm('login')"
                    >
                        Entrar
                    </button>

                    <button
                        type="button"
                        id="tab-register"
                        class="rounded-xl px-4 py-3 text-sm font-semibold text-slate-500 transition"
                        onclick="showForm('register')"
                    >
                        Crear cuenta
                    </button>
                </div>
            </div>

            <form id="login-form" method="POST" action="{{ route('patient.auth.login') }}" class="mt-8 space-y-5">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Email o teléfono</label>
                    <input
                        type="text"
                        name="login"
                        value="{{ old('login') }}"
                        placeholder="Tu identificador"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-800 outline-none transition focus:border-[#6ab7bd] focus:ring-4 focus:ring-[#a8d5d8]/30 @error('login') border-rose-300 focus:border-rose-400 focus:ring-rose-100 @enderror"
                        required
                    >
                    @error('login')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Contraseña</label>
                    <input
                        type="password"
                        name="password"
                        placeholder="••••••••"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-800 outline-none transition focus:border-[#6ab7bd] focus:ring-4 focus:ring-[#a8d5d8]/30 @error('password') border-rose-300 focus:border-rose-400 focus:ring-rose-100 @enderror"
                        required
                    >
                    @error('password')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="inline-flex min-h-12 w-full items-center justify-center rounded-2xl bg-[#6ab7bd] px-5 py-3 text-sm font-semibold text-white shadow-[0_16px_30px_rgba(106,183,189,0.28)] transition hover:-translate-y-0.5 hover:bg-[#4d9fa6]"
                >
                    Acceder a mi área
                </button>

                <p class="text-center text-sm text-slate-500">
                    ¿Olvidaste tu contraseña? Lo conectaremos después.
                </p>
            </form>

            <form id="register-form" method="POST" action="{{ route('patient.auth.register') }}" class="mt-8 hidden space-y-5">
                @csrf

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Nombre</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Juan"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-800 outline-none transition focus:border-[#6ab7bd] focus:ring-4 focus:ring-[#a8d5d8]/30 @error('name') border-rose-300 focus:border-rose-400 focus:ring-rose-100 @enderror"
                            required
                        >
                        @error('name')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Apellidos</label>
                        <input
                            type="text"
                            name="surname"
                            value="{{ old('surname') }}"
                            placeholder="Pérez Ruiz"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-800 outline-none transition focus:border-[#6ab7bd] focus:ring-4 focus:ring-[#a8d5d8]/30 @error('surname') border-rose-300 focus:border-rose-400 focus:ring-rose-100 @enderror"
                            required
                        >
                        @error('surname')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Teléfono móvil</label>
                    <input
                        type="tel"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="600 000 000"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-800 outline-none transition focus:border-[#6ab7bd] focus:ring-4 focus:ring-[#a8d5d8]/30 @error('phone') border-rose-300 focus:border-rose-400 focus:ring-rose-100 @enderror"
                        required
                    >
                    @error('phone')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Email (opcional)</label>
                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Para recibir recordatorios"
                        class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-800 outline-none transition focus:border-[#6ab7bd] focus:ring-4 focus:ring-[#a8d5d8]/30 @error('email') border-rose-300 focus:border-rose-400 focus:ring-rose-100 @enderror"
                    >
                    @error('email')
                        <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-5 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Contraseña</label>
                        <input
                            type="password"
                            name="password"
                            placeholder="Mínimo 8 caracteres"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-800 outline-none transition focus:border-[#6ab7bd] focus:ring-4 focus:ring-[#a8d5d8]/30 @error('password') border-rose-300 focus:border-rose-400 focus:ring-rose-100 @enderror"
                            required
                        >
                        @error('password')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Repite la contraseña</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            placeholder="Confirmar contraseña"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3.5 text-sm text-slate-800 outline-none transition focus:border-[#6ab7bd] focus:ring-4 focus:ring-[#a8d5d8]/30 @error('password_confirmation') border-rose-300 focus:border-rose-400 focus:ring-rose-100 @enderror"
                            required
                        >
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button
                    type="submit"
                    class="inline-flex min-h-12 w-full items-center justify-center rounded-2xl bg-[#6ab7bd] px-5 py-3 text-sm font-semibold text-white shadow-[0_16px_30px_rgba(106,183,189,0.28)] transition hover:-translate-y-0.5 hover:bg-[#4d9fa6]"
                >
                    Registrarme y continuar
                </button>

                <p class="text-center text-xs leading-6 text-slate-400">
                    Al registrarte, aceptas recibir comunicaciones relacionadas con tus citas.
                </p>
            </form>
        </div>
    </div>

    @php
    $shouldOpenRegister =
        old('name') !== null ||
        old('surname') !== null ||
        old('phone') !== null ||
        $errors->has('name') ||
        $errors->has('surname') ||
        $errors->has('phone') ||
        $errors->has('password_confirmation');
@endphp

<div
    id="auth-tabs"
    data-open-register="{{ $shouldOpenRegister ? '1' : '0' }}"
>
    <!-- aquí va todo tu contenido actual -->
</div>

<script>
    function setActiveTab(activeTab) {
        const tabLogin = document.getElementById('tab-login');
        const tabRegister = document.getElementById('tab-register');

        const activeClasses = ['bg-white', 'text-[#4d9fa6]', 'shadow-[0_8px_20px_rgba(0,0,0,0.06)]'];
        const inactiveClasses = ['text-slate-500'];

        [tabLogin, tabRegister].forEach((tab) => {
            tab.classList.remove(...activeClasses, ...inactiveClasses);
            tab.classList.add(...inactiveClasses);
        });

        activeTab.classList.remove(...inactiveClasses);
        activeTab.classList.add(...activeClasses);
    }

    function showForm(type) {
        const loginForm = document.getElementById('login-form');
        const registerForm = document.getElementById('register-form');
        const tabLogin = document.getElementById('tab-login');
        const tabRegister = document.getElementById('tab-register');

        if (type === 'login') {
            loginForm.classList.remove('hidden');
            registerForm.classList.add('hidden');
            setActiveTab(tabLogin);
        } else {
            loginForm.classList.add('hidden');
            registerForm.classList.remove('hidden');
            setActiveTab(tabRegister);
        }
    }

    document.addEventListener('DOMContentLoaded', function () {
        const wrapper = document.getElementById('auth-tabs');
        const shouldOpenRegister = wrapper?.dataset.openRegister === '1';

        showForm(shouldOpenRegister ? 'register' : 'login');
    });
</script>
</body>
</html>
