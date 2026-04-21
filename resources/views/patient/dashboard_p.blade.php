<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Área - JM Clínica</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .text-primary { color: #6ab7bd; }
        .bg-primary { background-color: #6ab7bd; }
        .border-primary { border-color: #6ab7bd; }
    </style>
</head>
<body class="bg-[#f3f4f6] text-[#374151]">

    <div id="overlay" class="fixed inset-0 bg-black/40 z-[1150] hidden transition-opacity" onclick="closeProfile()"></div>

    <nav class="bg-white border-b border-[#e5e7eb] h-16 flex items-center justify-between px-8 sticky top-0 z-[1100]">
        <a href="{{ route('home') }}" class="font-extrabold text-xl text-primary no-underline">JM Clínica</a>

        <div class="flex items-center gap-4">
            <button onclick="openProfile()" class="flex items-center gap-2 bg-[#f9fafb] border border-[#e5e7eb] px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 transition">
                <div class="w-6 h-6 bg-primary text-white rounded-full flex items-center justify-center text-[10px]">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}{{ strtoupper(substr(Auth::user()->surname, 0, 1)) }}
                </div>
                <span class="text-sm">Mi Perfil</span>
            </button>

            <form method="POST" action="{{ route('patient.logout') }}" class="m-0">
                @csrf
                <button type="submit" class="text-red-500 font-semibold text-sm hover:underline">
                    Salir
                </button>
            </form>
        </div>
    </nav>

    <div class="max-w-[1400px] mx-auto p-8 flex flex-col lg:flex-row gap-5 items-start">

        <aside class="w-full lg:w-[300px] bg-white border border-[#e5e7eb] rounded-xl p-6 shadow-sm sticky top-24">
            <h3 class="font-bold text-[#374151] border-b border-[#e5e7eb] pb-2 mb-4 flex items-center gap-2">
                Mis Citas Cogidas
            </h3>
            <div id="citas-container" class="space-y-3">
                <!-- ejemplo -->
                <div class="bg-[#f9fafb] border border-[#e5e7eb] rounded-lg p-3">
                    <b class="block text-[0.85rem] text-primary">Próxima cita</b>
                    <span class="text-sm font-semibold">Fisioterapia - 22/04 a las 10:30</span>
                </div>
                <p class="text-xs text-gray-400 italic">No tienes más citas programadas.</p>
            </div>
        </aside>

        <main class="flex-1 min-w-0 w-full">

            <div class="bg-white p-6 rounded-xl border border-[#e5e7eb] mb-6 flex flex-col md:flex-row justify-between items-center gap-4 shadow-sm">
                <div class="w-full md:w-auto">
                    <label class="block text-xs font-bold text-gray-400 uppercase mb-1">1. Elige tu tratamiento</label>
                    <select id="serviceSelect" onchange="toggleCalendar()" class="w-full md:w-64 p-3 rounded-lg border border-[#e5e7eb] font-bold text-primary outline-none focus:border-primary transition-all">
                        <option value="">Selecciona servicio...</option>
                        <option value="fisio">Fisioterapia (José María)</option>
                        <option value="suelo">Suelo Pélvico (José María)</option>
                        <option value="pilates">Pilates Grupal (Grecia)</option>
                        <option value="Entrenamiento">Entrenamiento personal (Carlos)</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button class="px-4 py-2 bg-white border border-[#e5e7eb] rounded-lg text-sm font-semibold hover:bg-gray-50">← Anterior</button>
                    <button class="px-4 py-2 bg-white border border-[#e5e7eb] rounded-lg text-sm font-semibold hover:bg-gray-50">Siguiente →</button>
                </div>
            </div>

            <div id="welcome-msg" class="bg-white rounded-xl border border-[#e5e7eb] py-20 text-center shadow-sm">
                <h3 class="text-xl font-bold text-[#374151]">👋 Bienvenido, {{ Auth::user()->name }}.</h3>
                <p class="text-gray-400 mt-2">Selecciona un tratamiento para ver horarios disponibles.</p>
            </div>

            <div id="mainCalendar" class="hidden bg-white rounded-xl border border-[#e5e7eb] overflow-hidden shadow-sm grid grid-cols-[80px_repeat(5,1fr)]">
                <div class="bg-[#f9fafb] border-b border-[#e5e7eb]"></div>
                @foreach(['LUN', 'MAR', 'MIÉ', 'JUE', 'VIE'] as $dia)
                    <div class="bg-[#f9fafb] p-4 text-center border-b border-l border-[#e5e7eb] font-bold text-primary text-sm uppercase">{{ $dia }}</div>
                @endforeach

                @php $horas = ["09:30", "10:30", "11:30", "12:30", "16:30", "17:30", "18:30", "19:30"]; @endphp
                @foreach($horas as $h)
                    <div class="h-[70px] flex items-center justify-center text-[0.75rem] font-bold text-[#9ca3af] border-b border-[#f3f4f6]">{{ $h }}</div>
                    @for($i=0; $i<5; $i++)
                        <div class="h-[70px] border-l border-b border-[#f3f4f6] p-1">
                            <button onclick="confirmarCita('{{ $h }}')" class="w-full h-full border border-dashed border-[#cbd5e1] rounded-md text-[0.65rem] font-bold text-primary hover:bg-[#f0fdfa] hover:border-primary transition-all uppercase">
                                Disponible
                            </button>
                        </div>
                    @endfor
                @endforeach
            </div>
        </main>
    </div>

    <div id="profilePanel" class="fixed top-0 right-[-400px] w-full max-w-[350px] h-full bg-white shadow-2xl transition-all duration-300 z-[1200] p-8 overflow-y-auto">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold">Mi Perfil</h3>
            <button onclick="closeProfile()" class="text-gray-400 hover:text-black text-xl">&times;</button>
        </div>

        <form action="{{ route('settings.profile') }}" method="GET" class="space-y-4">
            <div>
                <label class="block text-[0.7rem] font-extrabold text-[#9ca3af] uppercase mb-1">Nombre</label>
                <input type="text" class="w-full p-2.5 bg-gray-50 border border-[#e5e7eb] rounded-lg text-sm" value="{{ Auth::user()->name }}" readonly>
            </div>
            <div>
                <label class="block text-[0.7rem] font-extrabold text-[#9ca3af] uppercase mb-1">Apellidos</label>
                <input type="text" class="w-full p-2.5 bg-gray-50 border border-[#e5e7eb] rounded-lg text-sm" value="{{ Auth::user()->surname }}" readonly>
            </div>
            <div>
                <label class="block text-[0.7rem] font-extrabold text-[#9ca3af] uppercase mb-1">Email</label>
                <input type="email" class="w-full p-2.5 bg-gray-50 border border-[#e5e7eb] rounded-lg text-sm" value="{{ Auth::user()->email }}" readonly>
            </div>
            <div>
                <label class="block text-[0.7rem] font-extrabold text-[#9ca3af] uppercase mb-1">Teléfono</label>
                <input type="text" class="w-full p-2.5 bg-gray-50 border border-[#e5e7eb] rounded-lg text-sm" value="{{ Auth::user()->phone ?? 'No indicado' }}" readonly>
            </div>

            <div>
                <label class="block text-[0.7rem] font-extrabold text-[#9ca3af] uppercase mb-1">Fecha de Nacimiento</label>
                <input type="text" class="w-full p-2.5 bg-gray-50 border border-[#e5e7eb] rounded-lg text-sm" value="{{ Auth::user()->birth_date ?? 'No indicada' }}" readonly>
            </div>

            <button type="submit" class="w-full py-3 bg-primary text-white rounded-lg font-bold shadow-lg hover:opacity-90 transition mt-6">
                Editar mis datos
            </button>
        </form>
    </div>

    <script>
        function toggleCalendar() {
            const select = document.getElementById('serviceSelect');
            const calendar = document.getElementById('mainCalendar');
            const msg = document.getElementById('welcome-msg');
            if(select.value !== "") {
                calendar.classList.remove('hidden');
                msg.classList.add('hidden');
            } else {
                calendar.classList.add('hidden');
                msg.classList.remove('hidden');
            }
        }

        function openProfile() {
            document.getElementById('profilePanel').style.right = "0";
            document.getElementById('overlay').classList.remove('hidden');
        }

        function closeProfile() {
            document.getElementById('profilePanel').style.right = "-400px";
            document.getElementById('overlay').classList.add('hidden');
        }

        function confirmarCita(hora) {
            const service = document.getElementById('serviceSelect').options[document.getElementById('serviceSelect').selectedIndex].text;
            if(confirm(`¿Deseas reservar tu cita de ${service} a las ${hora}?`)) {
                alert("¡Cita reservada! Recibirás un SMS de confirmación.");
            }
        }
    </script>
</body>
</html>
