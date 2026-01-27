<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>League Factory | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-zinc-950 text-zinc-200 font-sans overflow-x-hidden selection:bg-indigo-500 selection:text-white">

    <section
        class="min-h-screen flex flex-col items-center justify-center text-center px-4 relative border-b border-zinc-800">
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[500px] bg-indigo-600/20 blur-[120px] -z-10 rounded-full">
        </div>
        <div class="absolute inset-0 bg-pitch -z-10"></div>

        <div class="w-full max-w-md animate-fade-in-up space-y-8">
            <h1 class="text-5xl md:text-6xl font-black text-white tracking-tighter drop-shadow-xl">
                LEAGUE <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-teal-300">
                    FACTORY
                </span>
            </h1>
            <div
                class="bg-zinc-900/80 backdrop-blur-md border border-zinc-700/50 rounded-2xl shadow-2xl p-8 hover:border-indigo-500/30 transition-all duration-300 text-left">
                <h2 class="text-2xl font-bold text-white mb-6 text-center"><?php echo __('login'); ?></h2>

                <form action="index.php?action=login" method="POST" class="space-y-6">
                    <div>
                        <label for="identity"
                            class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Email ó Usuario</label>
                        <input type="text" name="identity" id="identity" placeholder="Usuario o Email" 
                            class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder:text-zinc-600"
                            value="<?php echo isset($_SESSION['old_input']['identity']) ? htmlspecialchars($_SESSION['old_input']['identity']) : ''; ?>" required>
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label for="password"
                                class="block text-xs font-bold text-zinc-400 uppercase tracking-wider"><?php echo __('password'); ?></label>
                        </div>
                        <input type="password" name="password" id="password"
                            class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder:text-zinc-600"
                            placeholder="••••••••" required>
                    </div>

                    <div class="pt-2">
                        <button type="submit"
                            class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-3.5 px-4 rounded-xl transition-all shadow-lg shadow-indigo-600/20 hover:shadow-indigo-600/40 hover:scale-[1.02] transform">
                            <?php echo __('login'); ?>
                        </button>
                    </div>
                </form>

                <div class="mt-8 pt-6 border-t border-zinc-800 text-center">
                    <p class="text-zinc-500 text-sm">
                        <?php echo __('noAccount'); ?>
                        <a href="index.php?action=register"
                            class="text-white font-bold hover:text-indigo-400 transition-colors"><?php echo __('register'); ?></a>
                    </p>
                </div>
            </div>
        </div>
        <?php unset($_SESSION['old_input']); ?>
    </section>