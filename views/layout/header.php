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
                <a href="index.php?action=home"
                    class="text-2xl font-bold text-white tracking-wide hover:text-indigo-400 transition-colors duration-300">
                    LEAGUE FACTORY
                </a>
            </div>

            <nav class="flex items-center gap-8">
                <ul class="flex flex-row gap-6 text-sm font-medium items-center">
                    <li><a href="index.php?action=home" class="text-zinc-400 hover:text-white transition-colors duration-200">Inicio</a></li>
                    <li><a href="#" class="text-zinc-400 hover:text-white transition-colors duration-200">Crea tu Equipo</a></li>
                    <li><a href="#" class="text-zinc-400 hover:text-white transition-colors duration-200">Unirse a Liga</a></li>
                    <li><a href="#" class="text-zinc-400 hover:text-white transition-colors duration-200">Clasificación</a></li>

                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="flex items-center gap-6 ml-4 border-l border-zinc-700 pl-6">
                        <span class="text-indigo-400 font-bold">
                            <?php echo htmlspecialchars($_SESSION['user_name']); ?>
                        </span>
                        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                            <a href="adminPanel.php?action=admin"
                                class="text-xs text-indigo-500 hover:text-indigo-400 transition-colors uppercase tracking-widest font-bold border border-zinc-700 px-3 py-1 rounded-lg hover:border-indigo-400">
                                Panel Admin
                            </a>
                        <?php endif; ?>
                        <a href="index.php?action=logout"
                            class="text-xs text-zinc-500 hover:text-red-400 transition-colors duration-200 uppercase tracking-widest font-bold border border-zinc-700 px-3 py-1 rounded-lg hover:border-red-400">
                            Salir
                        </a>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="index.php?action=login"
                            class="rounded-3xl py-1 px-4 border border-indigo-400 text-indigo-400 hover:text-indigo-300 hover:border-indigo-300 transition-colors duration-300">
                            Registrarse o Iniciar Sesión
                        </a>
                    </li>
                <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>