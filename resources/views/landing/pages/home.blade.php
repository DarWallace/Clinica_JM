@extends('landing.layouts.app')

@section('title', 'JM Fsioterapia | Centro de fisioterapia avanzada')
@section('meta_description', 'Centro de fisioterapia avanzada, rehabilitación funcional, pilates terapéutico y atención personalizada en Sevilla.')

@section('content')
@php
    $jmImage = asset('images/landing/jm.png');
    $jmFallback = 'https://images.unsplash.com/photo-1612531386530-97286d97c2d2?auto=format&fit=crop&w=900&q=80';
@endphp

<div class="bg-white text-[#233133]">
    <header class="fixed inset-x-0 top-0 z-[1000] border-b border-[rgba(219,231,232,0.8)] bg-white/90 backdrop-blur-[12px]">
        <div class="mx-auto flex min-h-[84px] w-[min(1240px,calc(100%-48px))] items-center justify-between gap-6">
            <a href="#home" class="inline-flex items-center gap-[10px] no-underline" aria-label="Ir al inicio">
                <span class="text-2xl font-extrabold tracking-[-0.04em] text-[#4d9fa6]">JM</span>
                <span class="text-[0.95rem] font-semibold uppercase tracking-[0.04em] text-[#5b6162]">José María Medina</span>
            </a>

            <nav class="hidden items-center gap-[30px] lg:flex" aria-label="Navegación principal">
                <a href="#home" class="text-[0.96rem] font-medium text-[#233133] transition-colors duration-200 hover:text-[#4d9fa6]">Inicio</a>
                <a href="#metodologia" class="text-[0.96rem] font-medium text-[#233133] transition-colors duration-200 hover:text-[#4d9fa6]">Filosofía</a>
                <a href="#about" class="text-[0.96rem] font-medium text-[#233133] transition-colors duration-200 hover:text-[#4d9fa6]">Equipo</a>
                <a href="#services" class="text-[0.96rem] font-medium text-[#233133] transition-colors duration-200 hover:text-[#4d9fa6]">Servicios</a>
                <a href="#reviews" class="text-[0.96rem] font-medium text-[#233133] transition-colors duration-200 hover:text-[#4d9fa6]">Reseñas</a>
                <a href="#contact" class="text-[0.96rem] font-medium text-[#233133] transition-colors duration-200 hover:text-[#4d9fa6]">Contacto</a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ route('patient.login') }}"
                   class="inline-flex min-h-[48px] items-center justify-center rounded-full bg-[#6ab7bd] px-5 text-[0.95rem] font-semibold text-white shadow-[0_12px_24px_rgba(106,183,189,0.25)] transition-all duration-200 hover:-translate-y-px hover:bg-[#4d9fa6]">
                    Área paciente
                </a>
            </div>
        </div>
    </header>

    <main>
        <section id="home"
                 class="relative flex min-h-screen items-center overflow-hidden bg-[url('https://aspirephysiotherapy.ca/storage/benefits-of-physiotherapy-edmonton-south.jpg')] bg-cover bg-center bg-no-repeat pt-[84px]">
            <div class="absolute inset-0 bg-[linear-gradient(rgba(39,109,130,0.48),rgba(39,109,130,0.78))]"></div>
            <div class="absolute inset-0 bg-[linear-gradient(90deg,rgba(14,28,30,0.35)_0%,rgba(14,28,30,0.08)_60%,rgba(14,28,30,0.18)_100%)]"></div>

            <div class="relative z-[1] mx-auto grid w-[min(1240px,calc(100%-48px))] items-center gap-[60px] py-16 lg:grid-cols-[1.2fr_0.8fr]">
                <div class="text-white">
                    <span class="inline-flex rounded-full bg-white/15 px-4 py-[10px] text-[0.82rem] font-semibold tracking-[0.04em] backdrop-blur-[8px]">
                        Fisioterapia avanzada y atención personalizada
                    </span>

                    <h1 class="mt-6 text-[clamp(2.8rem,5vw,4.8rem)] font-bold leading-[1.03] tracking-[-0.05em]">
                        Tu recuperación merece un tratamiento profesional, cercano y sin prisas
                    </h1>

                    <p class="mt-6 max-w-[760px] text-[1.12rem] leading-[1.9] text-white/90">
                        En José María Fisioterapia trabajamos con un enfoque totalmente individualizado,
                        combinando terapia manual, ejercicio terapéutico y tecnología de apoyo para ayudarte
                        a recuperar movilidad, funcionalidad y bienestar.
                    </p>

                    <div class="mt-[34px] flex flex-wrap gap-4">
                        <a href="{{ route('patient.login') }}"
                           class="inline-flex min-h-[48px] items-center justify-center rounded-full bg-white px-6 text-[0.95rem] font-semibold text-[#4d9fa6] shadow-[0_16px_30px_rgba(0,0,0,0.12)] transition-all duration-200 hover:-translate-y-px">
                            Pedir cita
                        </a>
                        <a href="#contact"
                           class="inline-flex min-h-[48px] items-center justify-center rounded-full border border-white/60 px-6 text-[0.95rem] font-semibold text-white transition-all duration-200 hover:-translate-y-px hover:bg-white/10">
                            Solicitar información
                        </a>
                    </div>
                </div>

                <div class="flex justify-end">
                    <div class="max-w-[420px] rounded-[28px] bg-white/95 p-8 shadow-[0_20px_50px_rgba(35,49,51,0.08)]">
                        <p class="text-[0.82rem] font-bold uppercase tracking-[0.08em] text-[#4d9fa6]">Primera toma de contacto</p>
                        <h2 class="mt-[14px] text-[1.7rem] leading-[1.2] text-[#233133]">
                            Reserva, consulta tus citas y gestiona tu perfil
                        </h2>
                        <p class="mt-[14px] leading-[1.8] text-[#5b6162]">
                            Desde nuestra página puedes acceder a tu área privada, reservar sesiones y
                            revisar tus próximas citas.
                        </p>

                        <div class="mt-6 flex flex-col gap-3">
                            <a href="{{ route('patient.login') }}" class="font-semibold text-[#4d9fa6] no-underline">Entrar en mi perfil</a>
                            <a href="#services" class="font-semibold text-[#4d9fa6] no-underline">Ver tratamientos</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="metodologia" class="bg-white py-[110px]">
            <div class="mx-auto w-[min(920px,calc(100%-48px))]">
                <div class="mb-12 text-center">
                    <span class="mb-[14px] inline-block text-[0.84rem] font-bold uppercase tracking-[0.08em] text-[#4d9fa6]">Nuestra filosofía</span>
                    <h2 class="text-[clamp(2rem,3vw,3rem)] font-bold leading-[1.1] tracking-[-0.03em] text-[#233133]">
                        Una forma de trabajar basada en criterio, cercanía y exclusividad
                    </h2>
                </div>

                <div class="text-center">
                    <p class="text-[1.08rem] leading-[1.95] text-[#5b6162]">
                        En la clínica de <strong>José María Medina</strong>, entendemos la fisioterapia
                        como un camino hacia la recuperación funcional donde el paciente es el protagonista.
                        Nuestro enfoque combina la evidencia científica más actual con un trato profundamente
                        humano y cercano.
                    </p>
                </div>

                <div class="mt-9 grid gap-6 lg:grid-cols-2">
                    <div class="rounded-[28px] border border-[#dbe7e8] bg-[linear-gradient(180deg,#ffffff_0%,#f8fbfb_100%)] p-[34px] shadow-[0_20px_50px_rgba(35,49,51,0.08)]">
                        <span class="mb-[14px] inline-block text-[0.8rem] font-bold uppercase tracking-[0.08em] text-[#4d9fa6]">Nuestra base de trabajo</span>
                        <h3 class="mb-4 text-[1.45rem] leading-[1.2] text-[#233133]">La terapia manual como núcleo del tratamiento</h3>
                        <p class="mb-[14px] text-[1.05rem] leading-[1.8] text-[#233133]">
                            Creemos firmemente que la <strong>terapia manual</strong> es el núcleo de nuestro tratamiento.
                        </p>
                        <p class="leading-[1.85] text-[#5b6162]">
                            A diferencia de otros centros, defendemos que las manos del fisioterapeuta son
                            insustituibles para el diagnóstico y la mejora del paciente. Aunque empleamos
                            tecnología de última generación como herramienta de apoyo, esta nunca sustituye
                            el criterio ni la sensibilidad del especialista.
                        </p>
                    </div>

                    <div class="rounded-[28px] border border-[#dbe7e8] bg-[linear-gradient(180deg,#f8fbfb_0%,#eef6f7_100%)] p-[34px] shadow-[0_20px_50px_rgba(35,49,51,0.08)]">
                        <span class="mb-[14px] inline-block text-[0.8rem] font-bold uppercase tracking-[0.08em] text-[#4d9fa6]">Nuestra forma de cuidar</span>
                        <h3 class="mb-4 text-[1.45rem] leading-[1.2] text-[#233133]">Compromiso y exclusividad</h3>
                        <p class="leading-[1.85] text-[#5b6162]">
                            El ambiente de nuestra clínica es familiar y de absoluta confianza. Para nosotros,
                            cada caso es único, por lo que garantizamos:
                        </p>

                        <ul class="mt-[22px] grid gap-[14px]">
                            <li class="rounded-[18px] border border-[#dbe7e8] bg-white/90 p-[18px]">
                                <strong class="mb-2 block pl-3 text-base text-[#233133]">Tratamiento individualizado</strong>
                                <span class="block pl-3 leading-[1.75] text-[#5b6162]">
                                    El tiempo de cada sesión es exclusivo para un solo paciente; no simultaneamos
                                    tratamientos con distintas personas al mismo tiempo.
                                </span>
                            </li>
                            <li class="rounded-[18px] border border-[#dbe7e8] bg-white/90 p-[18px]">
                                <strong class="mb-2 block pl-3 text-base text-[#233133]">Comunicación constante</strong>
                                <span class="block pl-3 leading-[1.75] text-[#5b6162]">
                                    Escucharte y entender tu día a día es la base para adaptar el tratamiento
                                    a tus necesidades reales.
                                </span>
                            </li>
                            <li class="rounded-[18px] border border-[#dbe7e8] bg-white/90 p-[18px]">
                                <strong class="mb-2 block pl-3 text-base text-[#233133]">Sesiones completas</strong>
                                <span class="block pl-3 leading-[1.75] text-[#5b6162]">
                                    Dedicamos 1 hora íntegra a tu bienestar, exceptuando la valoración inicial
                                    de 15 minutos, para trabajar sin prisas.
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <section id="about" class="bg-[linear-gradient(180deg,#f8fbfb_0%,#f3f7f8_100%)] py-[110px]">
            <div class="mx-auto w-[min(1240px,calc(100%-48px))]">
                <div class="mb-12 text-center">
                    <span class="mb-[14px] inline-block text-[0.84rem] font-bold uppercase tracking-[0.08em] text-[#4d9fa6]">Equipo</span>
                    <h2 class="text-[clamp(2rem,3vw,3rem)] font-bold leading-[1.1] tracking-[-0.03em] text-[#233133]">
                        Profesionales especializados en distintas áreas de tratamiento
                    </h2>
                    <p class="mx-auto mt-4 max-w-[760px] text-[1.05rem] text-[#5b6162]">
                        Un equipo cercano, con experiencia y con un enfoque coordinado para ofrecer una
                        atención de calidad.
                    </p>
                </div>

                <div class="grid gap-6 lg:grid-cols-3">
                    <article
                        class="cursor-pointer overflow-hidden rounded-[28px] border border-[#dbe7e8] bg-white shadow-[0_20px_50px_rgba(35,49,51,0.08)] transition-all duration-200 hover:-translate-y-[6px] hover:border-[#a8d5d8] hover:shadow-[0_28px_50px_rgba(35,49,51,0.12)]"
                        data-name="José María Medina"
                        data-role="Fisioterapeuta y director"
                        data-desc="Especialista en terapia manual y recuperación funcional. Lidera el centro con una atención totalmente personalizada y un enfoque clínico individualizado."
                        data-img="{{ $jmImage }}"
                        data-fallback="{{ $jmFallback }}"
                        onclick="openDetailsFromCard(this)">
                        <div class="aspect-[4/3] overflow-hidden bg-[#f3f7f8]">
                            <img
                                src="{{ $jmImage }}"
                                alt="José María Medina"
                                class="block h-full w-full object-cover object-top"
                                onerror="this.onerror=null;this.src='{{ $jmFallback }}';">
                        </div>
                        <div class="p-6">
                            <h3 class="text-[1.2rem] text-[#233133]">José María Medina</h3>
                            <p class="mt-2 text-[#5b6162]">Fisioterapia avanzada</p>
                            <span class="mt-4 inline-block font-semibold text-[#4d9fa6]">Ver perfil</span>
                        </div>
                    </article>

                    <article
                        class="cursor-pointer overflow-hidden rounded-[28px] border border-[#dbe7e8] bg-white shadow-[0_20px_50px_rgba(35,49,51,0.08)] transition-all duration-200 hover:-translate-y-[6px] hover:border-[#a8d5d8] hover:shadow-[0_28px_50px_rgba(35,49,51,0.12)]"
                        data-name="Grecia Priego"
                        data-role="Neurofisiología y pilates terapéutico"
                        data-desc="Especialista en neurorrehabilitación y pilates en grupos reducidos, con un enfoque de trabajo muy controlado y adaptado a cada paciente."
                        data-img="https://images.unsplash.com/photo-1594824476967-48c8b964273f?auto=format&amp;fit=crop&amp;w=600&amp;q=80"
                        onclick="openDetailsFromCard(this)">
                        <div class="aspect-[4/3] overflow-hidden bg-[#f3f7f8]">
                            <img src="https://images.unsplash.com/photo-1594824476967-48c8b964273f?auto=format&fit=crop&w=600&q=80" alt="Grecia Priego" class="block h-full w-full object-cover">
                        </div>
                        <div class="p-6">
                            <h3 class="text-[1.2rem] text-[#233133]">Grecia</h3>
                            <p class="mt-2 text-[#5b6162]">Pilates y neurofisiología</p>
                            <span class="mt-4 inline-block font-semibold text-[#4d9fa6]">Ver perfil</span>
                        </div>
                    </article>

                    <article
                        class="cursor-pointer overflow-hidden rounded-[28px] border border-[#dbe7e8] bg-white shadow-[0_20px_50px_rgba(35,49,51,0.08)] transition-all duration-200 hover:-translate-y-[6px] hover:border-[#a8d5d8] hover:shadow-[0_28px_50px_rgba(35,49,51,0.12)]"
                        data-name="Carlos"
                        data-role="Entrenamiento personal"
                        data-desc="Especialista en readaptación y entrenamiento funcional con sesiones adaptadas a la evolución y necesidades de cada caso."
                        data-img="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&amp;fit=crop&amp;w=600&amp;q=80"
                        onclick="openDetailsFromCard(this)">
                        <div class="aspect-[4/3] overflow-hidden bg-[#f3f7f8]">
                            <img src="https://images.unsplash.com/photo-1534438327276-14e5300c3a48?auto=format&fit=crop&w=600&q=80" alt="Carlos" class="block h-full w-full object-cover">
                        </div>
                        <div class="p-6">
                            <h3 class="text-[1.2rem] text-[#233133]">Carlos</h3>
                            <p class="mt-2 text-[#5b6162]">Entrenamiento personal</p>
                            <span class="mt-4 inline-block font-semibold text-[#4d9fa6]">Ver perfil</span>
                        </div>
                    </article>
                </div>
            </div>
        </section>

        <section id="services" class="bg-white py-[110px]">
            <div class="mx-auto w-[min(1240px,calc(100%-48px))]">
                <div class="mb-12 text-center">
                    <span class="mb-[14px] inline-block text-[0.84rem] font-bold uppercase tracking-[0.08em] text-[#4d9fa6]">Servicios</span>
                    <h2 class="text-[clamp(2rem,3vw,3rem)] font-bold leading-[1.1] tracking-[-0.03em] text-[#233133]">
                        Tratamientos orientados a la recuperación funcional y al bienestar
                    </h2>
                </div>

                <div class="grid gap-6 md:grid-cols-2">
                    <article class="overflow-hidden rounded-[28px] border border-[#dbe7e8] bg-white shadow-[0_20px_50px_rgba(35,49,51,0.08)]">
                        <div class="aspect-[16/9] overflow-hidden bg-[#f3f7f8]">
                            <img src="https://images.unsplash.com/photo-1519824145371-296894a0daa9?auto=format&fit=crop&w=1200&q=80" alt="Tratamiento de columna y traumatología" class="block h-full w-full object-cover">
                        </div>
                        <div class="p-6 pb-7">
                            <h3 class="text-[1.1rem] text-[#233133]">Columna y traumatología</h3>
                            <p class="mt-3 leading-[1.75] text-[#5b6162]">Tratamiento de columna vertebral, lesiones deportivas y procesos traumatológicos.</p>
                            <strong class="mt-[18px] inline-block text-[#4d9fa6]">60 min · 45,00 €</strong>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-[28px] border border-[#dbe7e8] bg-white shadow-[0_20px_50px_rgba(35,49,51,0.08)]">
                        <div class="aspect-[16/9] overflow-hidden bg-[#f3f7f8]">
                            <img src="https://images.unsplash.com/photo-1518611012118-696072aa579a?auto=format&fit=crop&w=1200&q=80" alt="Suelo pélvico y embarazo" class="block h-full w-full object-cover">
                        </div>
                        <div class="p-6 pb-7">
                            <h3 class="text-[1.1rem] text-[#233133]">Suelo pélvico y embarazo</h3>
                            <p class="mt-3 leading-[1.75] text-[#5b6162]">Fisioterapia especializada para la mujer en sus distintas etapas y necesidades.</p>
                            <strong class="mt-[18px] inline-block text-[#4d9fa6]">60 min · 45,00 €</strong>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-[28px] border border-[#dbe7e8] bg-white shadow-[0_20px_50px_rgba(35,49,51,0.08)]">
                        <div class="aspect-[16/9] overflow-hidden bg-[#f3f7f8]">
                            <img src="https://images.unsplash.com/photo-1545205597-3d9d02c29597?auto=format&fit=crop&w=1200&q=80" alt="Yoga y pilates terapéutico" class="block h-full w-full object-cover">
                        </div>
                        <div class="p-6 pb-7">
                            <h3 class="text-[1.1rem] text-[#233133]">Yoga y pilates</h3>
                            <p class="mt-3 leading-[1.75] text-[#5b6162]">Sesiones dirigidas en grupos reducidos, controladas y adaptadas al paciente.</p>
                            <strong class="mt-[18px] inline-block text-[#4d9fa6]">60 min · 20,00 €</strong>
                        </div>
                    </article>

                    <article class="overflow-hidden rounded-[28px] border border-[#dbe7e8] bg-white shadow-[0_20px_50px_rgba(35,49,51,0.08)]">
                        <div class="aspect-[16/9] overflow-hidden bg-[#f3f7f8]">
                            <img src="https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&w=1200&q=80" alt="Entrenamiento personal" class="block h-full w-full object-cover">
                        </div>
                        <div class="p-6 pb-7">
                            <h3 class="text-[1.1rem] text-[#233133]">Entrenamiento personal</h3>
                            <p class="mt-3 leading-[1.75] text-[#5b6162]">Readaptación y ejercicio terapéutico con supervisión profesional individual.</p>
                            <strong class="mt-[18px] inline-block text-[#4d9fa6]">60 min · Consultar</strong>
                        </div>
                    </article>
                </div>

                <div class="mt-9 flex justify-center">
                    <a href="{{ route('patient.login') }}"
                       class="inline-flex min-h-[48px] items-center justify-center rounded-full bg-[#6ab7bd] px-5 text-[0.95rem] font-semibold text-white shadow-[0_12px_24px_rgba(106,183,189,0.25)] transition-all duration-200 hover:-translate-y-px hover:bg-[#4d9fa6]">
                        Reservar cita online
                    </a>
                </div>
            </div>
        </section>

        <section id="reviews" class="bg-[linear-gradient(180deg,#f8fbfb_0%,#f3f7f8_100%)] py-[110px]">
    <div class="mx-auto w-[min(1240px,calc(100%-48px))]">
        <div class="mb-12 text-center">
            <span class="mb-[14px] inline-block text-[0.84rem] font-bold uppercase tracking-[0.08em] text-[#4d9fa6]">Reseñas</span>
            <h2 class="text-[clamp(2rem,3vw,3rem)] font-bold leading-[1.1] tracking-[-0.03em] text-[#233133]">
                La confianza de nuestros pacientes es nuestra mejor carta de presentación
            </h2>
        </div>

        <a href="https://search.google.com/local/reviews?placeid=ChIJCc9mYIxvEg0REvbtRYp8aH8"
           target="_blank"
           class="group mb-[42px] block text-center transition-transform hover:scale-105">
            <div class="text-5xl font-bold text-[#233133]">5.0 / 5</div>
            <div class="mt-2 text-2xl tracking-[0.08em] text-[#e7b321]">★★★★★</div>
            <p class="mt-[10px] text-[#5b6162] group-hover:text-[#4d9fa6] group-hover:underline">
                Basado en 41 reseñas públicas en Google (Ver todas)
            </p>
        </a>

        <div class="grid gap-6 md:grid-cols-2">
                    <article class="rounded-[20px] border-l-4 border-[#6ab7bd] bg-white p-7 shadow-[0_20px_50px_rgba(35,49,51,0.08)]">
                        <div class="mb-[18px] flex items-center gap-[14px]">
                            <div class="flex h-[46px] w-[46px] items-center justify-center rounded-full bg-[#f3f7f8] font-bold text-[#4d9fa6]">E</div>
                            <div>
                                <h3 class="m-0 text-base">Eugenia Diez</h3>
                                <span class="text-[0.88rem] text-[#5b6162]">Reseña pública</span>
                            </div>
                        </div>
                        <p class="italic leading-[1.8] text-[#5b6162]">
                            Acudí por recomendación de una amiga, y ha sido todo un acierto. Las instalaciones
                            son totalmente nuevas, un entorno muy cómodo para el tratamiento. José María tiene
                            un trato cercano y muy profesional.
                        </p>
                    </article>

                    <article class="rounded-[20px] border-l-4 border-[#6ab7bd] bg-white p-7 shadow-[0_20px_50px_rgba(35,49,51,0.08)]">
                        <div class="mb-[18px] flex items-center gap-[14px]">
                            <div class="flex h-[46px] w-[46px] items-center justify-center rounded-full bg-[#f3f7f8] font-bold text-[#4d9fa6]">A</div>
                            <div>
                                <h3 class="m-0 text-base">Alicia Gomez</h3>
                                <span class="text-[0.88rem] text-[#5b6162]">Reseña pública</span>
                            </div>
                        </div>
                        <p class="italic leading-[1.8] text-[#5b6162]">
                            Atención muy profesional y muy cercana. Acudí por un problema en el pie y en un
                            par de sesiones lo tenía como nuevo y con una conversación súper agradable.
                            Un trato de 10/10; volveré sin duda.
                        </p>
                    </article>

                    <article class="rounded-[20px] border-l-4 border-[#6ab7bd] bg-white p-7 shadow-[0_20px_50px_rgba(35,49,51,0.08)]">
                        <div class="mb-[18px] flex items-center gap-[14px]">
                            <div class="flex h-[46px] w-[46px] items-center justify-center rounded-full bg-[#f3f7f8] font-bold text-[#4d9fa6]">J</div>
                            <div>
                                <h3 class="m-0 text-base">José Antonio Sánchez</h3>
                                <span class="text-[0.88rem] text-[#5b6162]">Reseña pública</span>
                            </div>
                        </div>
                        <p class="italic leading-[1.8] text-[#5b6162]">
                            Excelente trato al paciente. Variedad de tratamientos para diversas dolencias.
                            Recomendable 100%.
                        </p>
                    </article>

                    <article class="rounded-[20px] border-l-4 border-[#6ab7bd] bg-white p-7 shadow-[0_20px_50px_rgba(35,49,51,0.08)]">
                        <div class="mb-[18px] flex items-center gap-[14px]">
                            <div class="flex h-[46px] w-[46px] items-center justify-center rounded-full bg-[#f3f7f8] font-bold text-[#4d9fa6]">S</div>
                            <div>
                                <h3 class="m-0 text-base">Sam S</h3>
                                <span class="text-[0.88rem] text-[#5b6162]">Reseña pública</span>
                            </div>
                        </div>
                        <p class="italic leading-[1.8] text-[#5b6162]">
                            Estuve 1,5 años probando varios fisios en Sevilla por problemas de rodilla sin
                            mejorar hasta encontrar a José María. Gracias a él, mi rodilla ha mejorado
                            completamente. Lo recomiendo 100%.
                        </p>
                    </article>
                </div>
                <div class="mt-12 text-center">
            <a href="https://search.google.com/local/writereview?placeid=ChIJCc9mYIxvEg0REvbtRYp8aH8"
               target="_blank"
               class="inline-flex items-center gap-2 rounded-full bg-[#233133] px-6 py-3 text-sm font-bold text-white transition-all hover:bg-[#4d9fa6]">
                Escribir mi propia reseña
            </a>
        </div>
            </div>
        </section>

        <section id="contact" class="bg-white py-[110px]">
    <div class="mx-auto w-[min(1240px,calc(100%-48px))]">
        <div class="mb-12 text-center">
            <span class="mb-[14px] inline-block text-[0.84rem] font-bold uppercase tracking-[0.08em] text-[#4d9fa6]">Contacto</span>
            <h2 class="text-[clamp(2rem,3vw,3rem)] font-bold leading-[1.1] tracking-[-0.03em] text-[#233133]">
                Da el primer paso para empezar tu recuperación
            </h2>
            <p class="mx-auto mt-4 max-w-[760px] text-[1.05rem] text-[#5b6162]">
                Puedes escribirnos, pedir información o acceder a tu área privada para gestionar tus citas.
            </p>
        </div>

        <div class="grid items-stretch gap-7 lg:grid-cols-2">
            <div class="flex flex-col rounded-[28px] border border-[#dbe7e8] bg-[linear-gradient(180deg,#ffffff_0%,#f9fbfb_100%)] p-8 shadow-[0_20px_50px_rgba(35,49,51,0.08)]">
                <h3 class="mb-[18px] text-[1.45rem] text-[#233133]">Cuéntanos tu caso</h3>

                <form class="grid flex-grow gap-[14px]">
                    <input type="text" placeholder="Nombre completo" required class="w-full rounded-[14px] border border-[#dbe7e8] bg-white px-4 py-[15px] text-[0.96rem] text-[#233133] outline-none transition-all duration-200 focus:border-[#a8d5d8] focus:shadow-[0_0_0_4px_rgba(168,213,216,0.18)]">

                    <input type="email" placeholder="Correo electrónico" required class="w-full rounded-[14px] border border-[#dbe7e8] bg-white px-4 py-[15px] text-[0.96rem] text-[#233133] outline-none transition-all duration-200 focus:border-[#a8d5d8] focus:shadow-[0_0_0_4px_rgba(168,213,216,0.18)]">

                    <select required class="w-full rounded-[14px] border border-[#dbe7e8] bg-white px-4 py-[15px] text-[0.96rem] text-[#233133] outline-none transition-all duration-200 focus:border-[#a8d5d8] focus:shadow-[0_0_0_4px_rgba(168,213,216,0.18)]">
                        <option value="" disabled selected>Selecciona el motivo de tu consulta...</option>
                        <option value="valoracion">Primera cita</option>
                        <option value="columna">Problemas de columna</option>
                        <option value="deportiva">Lesión deportiva</option>
                        <option value="suelo-pelvico">Suelo pélvico / embarazo</option>
                        <option value="geriatria">Fisioterapia geriátrica</option>
                        <option value="traumato">Fisioterapia traumatológica</option>
                        <option value="pilates-yoga">Yoga o pilates</option>
                        <option value="entrenamiento">Entrenamiento personal</option>
                        <option value="otro">Otro motivo</option>
                    </select>

                    <textarea rows="5" placeholder="Cuéntanos un poco más sobre tu caso..." class="min-h-[180px] w-full resize-none rounded-[14px] border border-[#dbe7e8] bg-white px-4 py-[15px] text-[0.96rem] text-[#233133] outline-none transition-all duration-200 focus:border-[#a8d5d8] focus:shadow-[0_0_0_4px_rgba(168,213,216,0.18)]"></textarea>

                    <button type="submit"
                            class="mt-auto inline-flex min-h-[48px] w-full items-center justify-center rounded-full bg-[#6ab7bd] px-5 text-[0.95rem] font-semibold text-white shadow-[0_12px_24px_rgba(106,183,189,0.25)] transition-all duration-200 hover:-translate-y-px hover:bg-[#4d9fa6]">
                        Enviar consulta
                    </button>
                </form>
            </div>

            <div class="flex flex-col rounded-[28px] border border-[#dbe7e8] bg-[linear-gradient(180deg,#ffffff_0%,#f9fbfb_100%)] p-8 shadow-[0_20px_50px_rgba(35,49,51,0.08)]">
                <span class="mb-[14px] inline-block text-[0.84rem] font-bold uppercase tracking-[0.08em] text-[#4d9fa6]">Dónde estamos</span>
                <h3 class="mb-[18px] text-[1.45rem] text-[#233133]">Clínica José María Medina</h3>

                <div class="mb-[24px] w-full overflow-hidden rounded-[18px] flex-grow">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3170.231238699114!2d-5.9818!3d37.385!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd126c1969420043%3A0xc31697205307b99c!2sC.%20Jos%C3%A9%20Mar%C3%ADa%20Bedoya%2C%2013%2C%2041018%20Sevilla!5e0!3m2!1ses!2ses!4v1710000000000!5m2!1ses!2ses"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                        class="block h-full min-h-[300px] w-full border-0"></iframe>
                </div>

                <div class="grid gap-[8px] mb-6">
                    <p class="m-0 text-[0.96rem] leading-[1.7] text-[#5b6162]"><strong>Dirección:</strong> C/ José María Bedoya 13, Sevilla</p>
                    <p class="m-0 text-[0.96rem] leading-[1.7] text-[#5b6162]"><strong>Teléfono:</strong> 633 408 352</p>
                    <p class="m-0 text-[0.96rem] leading-[1.7] text-[#5b6162]"><strong>Email:</strong> clinicajosemariamedina@gmail.com</p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('patient.login') }}"
                       class="inline-flex min-h-[48px] items-center justify-center rounded-full bg-[#6ab7bd] px-5 text-[0.95rem] font-semibold text-white shadow-[0_12px_24px_rgba(106,183,189,0.25)] transition-all duration-200 hover:-translate-y-px hover:bg-[#4d9fa6]">
                        Acceder / registrarme
                    </a>
                    <a href="tel:633408352"
                       class="inline-flex min-h-[48px] items-center justify-center rounded-full border border-[#dbe7e8] bg-white px-5 text-[0.95rem] font-semibold text-[#233133] transition-all duration-200 hover:-translate-y-px hover:bg-[#f8fbfb]">
                        Llamar ahora
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
    </main>

    <footer class="mt-10 bg-[#1f2d2f] pt-[60px] text-white/80">
        <div class="mx-auto grid w-[min(1240px,calc(100%-48px))] gap-8 pb-9 lg:grid-cols-[1.3fr_1fr_1fr_1fr]">
            <div>
                <div class="inline-flex items-center gap-[10px]">
                    <span class="text-2xl font-extrabold tracking-[-0.04em] text-[#4d9fa6]">JM</span>
                    <span class="text-[0.95rem] font-semibold uppercase tracking-[0.04em] text-white/80">José María Medina</span>
                </div>
                <p class="mt-[18px] max-w-[360px] leading-[1.8]">
                    Centro de fisioterapia avanzada enfocado en la recuperación funcional,
                    el trato cercano y la atención personalizada.
                </p>
            </div>

            <div>
                <h4 class="mb-4 text-base text-white">Enlaces</h4>
                <ul class="grid gap-[10px]">
                    <li><a href="#home" class="leading-[1.7] text-white/75 no-underline transition-colors duration-200 hover:text-white">Inicio</a></li>
                    <li><a href="#metodologia" class="leading-[1.7] text-white/75 no-underline transition-colors duration-200 hover:text-white">Filosofía</a></li>
                    <li><a href="#about" class="leading-[1.7] text-white/75 no-underline transition-colors duration-200 hover:text-white">Equipo</a></li>
                    <li><a href="#services" class="leading-[1.7] text-white/75 no-underline transition-colors duration-200 hover:text-white">Servicios</a></li>
                    <li><a href="#contact" class="leading-[1.7] text-white/75 no-underline transition-colors duration-200 hover:text-white">Contacto</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 text-base text-white">Pacientes</h4>
                <ul class="grid gap-[10px]">
                    <li><a href="{{ route('patient.login') }}" class="leading-[1.7] text-white/75 no-underline transition-colors duration-200 hover:text-white">Acceder a mi perfil</a></li>
                    <li><a href="{{ route('patient.login') }}" class="leading-[1.7] text-white/75 no-underline transition-colors duration-200 hover:text-white">Reservar cita</a></li>
                    <li><a href="{{ route('patient.login') }}" class="leading-[1.7] text-white/75 no-underline transition-colors duration-200 hover:text-white">Consultar mis citas</a></li>
                </ul>
            </div>

            <div>
                <h4 class="mb-4 text-base text-white">Contacto</h4>
                <ul class="grid gap-[10px]">
                    <li><a href="tel:633408352" class="leading-[1.7] text-white/75 no-underline transition-colors duration-200 hover:text-white">633 408 352</a></li>
                    <li><a href="mailto:clinicajosemariamedina@gmail.com" class="leading-[1.7] text-white/75 no-underline transition-colors duration-200 hover:text-white">clinicajosemariamedina@gmail.com</a></li>
                    <li class="leading-[1.7] text-white/75">C/ José María Bedoya 13, Sevilla</li>
                </ul>
            </div>
        </div>

        <div class="mx-auto w-[min(1240px,calc(100%-48px))] border-t border-white/10 py-[18px] text-[0.92rem] text-white/60">
            <p>© {{ now()->year }} Clínica José María Medina. Todos los derechos reservados.</p>
        </div>
    </footer>

    <div id="overlay"
         class="pointer-events-none fixed inset-0 z-[1500] bg-[rgba(20,27,28,0.58)] opacity-0 backdrop-blur-[3px] transition-opacity duration-300"
         onclick="closeDetails()"></div>

    <aside id="offcanvas"
           aria-hidden="true"
           class="fixed top-0 right-0 z-[2000] h-full w-[min(430px,100%)] translate-x-full bg-white px-7 pb-7 pt-[90px] shadow-[-20px_0_50px_rgba(35,49,51,0.12)] transition-transform duration-300">
        <button class="absolute right-[18px] top-[18px] border-0 bg-transparent text-[2rem] text-[#233133]"
                type="button"
                onclick="closeDetails()"
                aria-label="Cerrar">
            ×
        </button>

        <div class="text-center">
            <img id="off-img" src="" alt="Especialista" class="mx-auto h-[148px] w-[148px] rounded-full border-4 border-[#a8d5d8] bg-[#f3f7f8] object-cover">
            <h2 id="off-name" class="mt-[18px] text-[1.7rem]"></h2>
            <h4 id="off-role" class="mt-2 text-base font-semibold text-[#4d9fa6]"></h4>
            <p id="off-desc" class="mb-7 mt-5 text-left leading-[1.8] text-[#5b6162]"></p>

            <a href="{{ route('patient.login') }}"
               class="inline-flex min-h-[48px] w-full items-center justify-center rounded-full bg-[#6ab7bd] px-5 text-[0.95rem] font-semibold text-white shadow-[0_12px_24px_rgba(106,183,189,0.25)] transition-all duration-200 hover:-translate-y-px hover:bg-[#4d9fa6]">
                Pedir cita con este especialista
            </a>
        </div>
    </aside>

    <button type="button"
            onclick="openChat()"
            aria-label="Abrir asistente"
            class="fixed bottom-7 right-7 z-[1200] flex h-[68px] w-[68px] items-center justify-center rounded-full border-0 bg-[linear-gradient(135deg,#6ab7bd,#4d9fa6)] text-base font-extrabold tracking-[0.04em] text-white shadow-[0_18px_30px_rgba(77,159,166,0.35)]">
        <span>IA</span>
    </button>
</div>

<script>
    function openChat() {
        alert('Aquí dejaremos preparado el acceso al futuro asistente virtual conectado con n8n.');
    }

    function setOffcanvasImage(img, fallback = '') {
        const offImg = document.getElementById('off-img');
        offImg.onerror = null;
        offImg.src = img || fallback || '';

        if (fallback) {
            offImg.onerror = function () {
                this.onerror = null;
                this.src = fallback;
            };
        }
    }

    function openDetails(name, role, desc, img, fallback = '') {
        document.getElementById('off-name').innerText = name;
        document.getElementById('off-role').innerText = role;
        document.getElementById('off-desc').innerText = desc;
        setOffcanvasImage(img, fallback);

        const offcanvas = document.getElementById('offcanvas');
        const overlay = document.getElementById('overlay');

        offcanvas.classList.remove('translate-x-full');
        overlay.classList.remove('pointer-events-none', 'opacity-0');
        overlay.classList.add('opacity-100');
        offcanvas.setAttribute('aria-hidden', 'false');
        document.body.classList.add('overflow-hidden');
    }

    function openDetailsFromCard(card) {
        openDetails(
            card.dataset.name || '',
            card.dataset.role || '',
            card.dataset.desc || '',
            card.dataset.img || '',
            card.dataset.fallback || ''
        );
    }

    function closeDetails() {
        const offcanvas = document.getElementById('offcanvas');
        const overlay = document.getElementById('overlay');

        offcanvas.classList.add('translate-x-full');
        overlay.classList.add('pointer-events-none', 'opacity-0');
        overlay.classList.remove('opacity-100');
        offcanvas.setAttribute('aria-hidden', 'true');
        document.body.classList.remove('overflow-hidden');
    }

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeDetails();
        }
    });
</script>
@endsection
