<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>League Factory</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-950 text-zinc-200 flex flex-col min-h-screen">

    <header class="bg-zinc-900 border-b border-zinc-800 py-4 px-8 sticky top-0 z-50 shadow-lg">
        <div class="container mx-auto flex items-center justify-between">
            <div>
                <a href="/" class="text-2xl font-bold text-white tracking-wide hover:text-indigo-400 transition-colors duration-300">
                    LEAGUE FACTORY
                </a>
            </div>

            <nav>
                <ul class="flex flex-row gap-6 text-sm font-medium">
                    <li><a href="index.php?action=posts" class="text-zinc-400 hover:text-white transition-colors duration-200">Inicio</a></li>
                    <li><a href="index.php?action=login" class="text-zinc-400 hover:text-white transition-colors duration-200">Crea tu Equipo</a></li>
                    <li><a href="index.php?action=login" class="text-zinc-400 hover:text-white transition-colors duration-200">Unirse a Liga</a></li>
                    <li><a href="index.php?action=login" class="text-indigo-400 hover:text-indigo-300 transition-colors duration-200">Clasificación</a></li>
                </ul>
            </nav>
        </div>
    </header>
