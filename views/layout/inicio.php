<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* --- LA MAGIA DEL SCROLL (CSS PURO) --- */
        
        /* Definimos el movimiento: empieza invisible y abajo, termina visible y en su sitio */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(60px) scale(0.95);
                filter: blur(5px);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
                filter: blur(0);
            }
        }

        /* Clase que aplicaremos a todo lo que queramos animar */
        .scroll-reveal {
            /* Aplica la animación definida arriba */
            animation: fadeInUp linear both;
            
            /* Vincula la animación al scroll del navegador */
            animation-timeline: view();
            
            /* RANGO: Empieza al entrar un 5% en pantalla y termina al cubrir el 35% */
            animation-range: entry 5% cover 35%;
        }
    </style>
</head>
<body class="bg-zinc-950 text-zinc-200 font-sans overflow-x-hidden selection:bg-emerald-500 selection:text-white">

    <section class="min-h-screen flex flex-col items-center justify-center text-center px-4 relative border-b border-zinc-800">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[500px] bg-emerald-600/20 blur-[120px] -z-10 rounded-full"></div>
        <div class="absolute inset-0 bg-pitch -z-10"></div>

        <div class="scroll-reveal space-y-8 max-w-4xl">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-bold tracking-widest uppercase">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 17.93c-3.95-.49-7-3.85-7-7.93 0-.62.08-1.21.21-1.79L9 15v1c0 1.1.9 2 2 2v1.93zm6.9-2.54c-.26-.81-1-1.39-1.9-1.39h-1v-3c0-.55-.45-1-1-1H8v-2h2c.55 0 1-.45 1-1V7h2c1.1 0 2-.9 2-2v-.41c2.93 1.19 5 4.06 5 7.41 0 2.08-.8 3.97-2.1 5.39z"/></svg>
                Gestión Profesional de Ligas
            </span>

            <h1 class="text-6xl md:text-8xl font-black text-white tracking-tighter drop-shadow-xl">
                LEAGUE <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 to-teal-300">
                    FACTORY
                </span>
            </h1>

            <p class="text-xl text-zinc-400 max-w-2xl mx-auto leading-relaxed">
                Organiza tu liga de <strong>Fútbol 7, 11 o Sala</strong> como un profesional. Calendarios automáticos, actas digitales y estadísticas en tiempo real.
            </p>

            <div class="pt-4 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#ligas" class="px-8 py-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl transition-all shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:scale-105">
                    Buscar Equipo
                </a>
                <button class="px-8 py-4 bg-zinc-900 border border-zinc-700 hover:bg-zinc-800 text-white font-bold rounded-xl transition-all">
                    Crear mi Liga
                </button>
            </div>
        </div>
    </section>

    <section class="py-16 border-b border-zinc-800 bg-zinc-900/30">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <?php 
            $stats = [
                ["n" => "2,450", "t" => "Partidos Jugados"],
                ["n" => "12,800", "t" => "Goles Anotados"],
                ["n" => "350", "t" => "Árbitros"],
                ["n" => "85", "t" => "Ligas Activas"]
            ];
            foreach($stats as $s): ?>
                <div class="scroll-reveal">
                    <div class="text-4xl font-black text-white mb-1"><?php echo $s['n']; ?></div>
                    <div class="text-emerald-500 text-xs font-bold uppercase tracking-wider"><?php echo $s['t']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="py-24 px-4 max-w-7xl mx-auto">
        <div class="text-center mb-16 scroll-reveal">
            <h2 class="text-3xl font-bold text-white mb-4">Herramientas de Primera División</h2>
            <p class="text-zinc-500">Todo lo que necesitas para gestionar el torneo del barrio o una liga federada.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="scroll-reveal p-8 bg-zinc-900 rounded-2xl border border-zinc-800 hover:border-emerald-500/50 transition duration-300">
                <div class="w-12 h-12 bg-emerald-500/10 rounded-lg flex items-center justify-center mb-6 text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Actas Digitales</h3>
                <p class="text-zinc-400 text-sm">Los árbitros suben los resultados, goles y tarjetas desde el móvil al instante.</p>
            </div>
            <div class="scroll-reveal p-8 bg-zinc-900 rounded-2xl border border-zinc-800 hover:border-emerald-500/50 transition duration-300">
                <div class="w-12 h-12 bg-emerald-500/10 rounded-lg flex items-center justify-center mb-6 text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Estadísticas MVP</h3>
                <p class="text-zinc-400 text-sm">Ranking automático de pichichis, zamoras (porteros) y asistencias.</p>
            </div>
            <div class="scroll-reveal p-8 bg-zinc-900 rounded-2xl border border-zinc-800 hover:border-emerald-500/50 transition duration-300">
                <div class="w-12 h-12 bg-emerald-500/10 rounded-lg flex items-center justify-center mb-6 text-emerald-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-white mb-2">Calendario Auto</h3>
                <p class="text-zinc-400 text-sm">Genera ligas de ida y vuelta o torneos de eliminación con un solo clic.</p>
            </div>
        </div>
    </section>

    <section id="ligas" class="py-24 bg-zinc-900/20 border-y border-zinc-800">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-end mb-12 scroll-reveal">
                <div>
                    <h2 class="text-4xl font-bold text-white">Ligas Disponibles</h2>
                    <p class="text-zinc-400 mt-2">Inscribe a tu equipo en las mejores competiciones.</p>
                </div>
                <a href="#" class="hidden md:block text-emerald-400 font-bold hover:text-emerald-300">Ver todas &rarr;</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                // DATOS DE FÚTBOL
                $ligas = [
                    ["tipo" => "Fútbol 7", "nombre" => "Liga de los Lunes", "estado" => "Inscripciones", "horario" => "Lun 20:00h", "equipos" => "8/12"],
                    ["tipo" => "Fútbol 11", "nombre" => "División de Honor", "estado" => "En Juego", "horario" => "Sáb Mañana", "equipos" => "16/16"],
                    ["tipo" => "Futsal", "nombre" => "Torneo Relámpago", "estado" => "Últimas Plazas", "horario" => "Vie Noche", "equipos" => "10/12"],
                    ["tipo" => "Fútbol 7", "nombre" => "Liga Veteranos +30", "estado" => "En Juego", "horario" => "Dom Tarde", "equipos" => "14/14"],
                    ["tipo" => "Empresas", "nombre" => "Copa Corporativa", "estado" => "Inscripciones", "horario" => "Jue 19:00h", "equipos" => "2/10"],
                    ["tipo" => "Fútbol 7", "nombre" => "Liga Universitaria", "estado" => "En Juego", "horario" => "Mié Tarde", "equipos" => "20/20"],
                ];

                foreach ($ligas as $liga):
                    // Lógica de colores según estado
                    $badgeColor = match($liga['estado']) {
                        'Inscripciones' => 'bg-emerald-500 text-black',
                        'Últimas Plazas' => 'bg-amber-400 text-black animate-pulse',
                        'En Juego' => 'bg-zinc-700 text-white',
                    };
                ?>
                    <div class="scroll-reveal relative group bg-zinc-900 border border-zinc-800 rounded-xl overflow-hidden hover:border-emerald-600 transition-all duration-300 hover:-translate-y-1">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <span class="text-xs font-bold text-zinc-400 border border-zinc-700 px-2 py-1 rounded uppercase">
                                    <?php echo $liga['tipo']; ?>
                                </span>
                                <span class="text-xs font-bold px-3 py-1 rounded-full <?php echo $badgeColor; ?>">
                                    <?php echo $liga['estado']; ?>
                                </span>
                            </div>
                            
                            <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-emerald-400 transition-colors">
                                <?php echo $liga['nombre']; ?>
                            </h3>

                            <div class="space-y-2 mt-4">
                                <div class="flex items-center text-sm text-zinc-400">
                                    <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    <?php echo $liga['horario']; ?>
                                </div>
                                <div class="flex items-center text-sm text-zinc-400">
                                    <svg class="w-4 h-4 mr-2 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    <?php echo $liga['equipos']; ?> Equipos
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-zinc-950/50 border-t border-zinc-800">
                            <button class="w-full py-2 rounded-lg bg-zinc-800 text-white font-medium hover:bg-emerald-600 transition-colors text-sm">
                                Ver Clasificación
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section class="py-24 px-4 bg-zinc-950 relative overflow-hidden">
        <div class="absolute inset-0 bg-pitch opacity-10"></div>
        <div class="max-w-5xl mx-auto">
            <h2 class="text-3xl font-bold mb-8 text-center scroll-reveal">Partidos de la Semana</h2>

            <div class="flex flex-col gap-3">
                <?php
                $partidos = [
                    ["local" => "Rayos FC", "visitante" => "Inter Móstoles", "hora" => "20:00", "campo" => "Campo 1 (F7)", "fecha" => "Hoy"],
                    ["local" => "Los Galácticos", "visitante" => "Vodka Juniors", "hora" => "21:00", "campo" => "Campo 2 (F7)", "fecha" => "Hoy"],
                    ["local" => "At. Birra Real", "visitante" => "Nottingham Miedo", "hora" => "10:00", "campo" => "Estadio Central", "fecha" => "Mañana"],
                    ["local" => "Schalke Temeto", "visitante" => "Real Suciedad", "hora" => "12:30", "campo" => "Pabellón Cubierto", "fecha" => "Mañana"],
                ];

                foreach($partidos as $p):
                ?>
                <div class="scroll-reveal bg-zinc-900 border border-zinc-800 rounded-lg p-4 flex flex-col md:flex-row items-center justify-between hover:bg-zinc-800 transition">
                    <div class="flex items-center justify-center gap-6 w-full md:w-1/2 mb-4 md:mb-0">
                        <span class="font-bold text-white w-32 text-right"><?php echo $p['local']; ?></span>
                        <div class="bg-zinc-800 px-3 py-1 rounded text-xs text-zinc-400 font-mono">VS</div>
                        <span class="font-bold text-white w-32 text-left"><?php echo $p['visitante']; ?></span>
                    </div>

                    <div class="flex items-center gap-6 text-sm">
                        <div class="flex flex-col items-center">
                            <span class="text-white font-bold text-lg"><?php echo $p['hora']; ?></span>
                            <span class="text-emerald-500 text-xs uppercase"><?php echo $p['fecha']; ?></span>
                        </div>
                        <div class="text-zinc-500 text-right">
                            <div class="flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                <?php echo $p['campo']; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</body>
</html>