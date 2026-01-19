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

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-500/10 border border-red-500/50 text-red-400 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-sm font-medium"><?php echo $_SESSION['error']; ?></span>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <form action="index.php?action=login" method="POST" class="space-y-6">
                    <div>
                        <label for="email"
                            class="block text-xs font-bold text-zinc-400 uppercase tracking-wider mb-2">Email</label>
                        <input type="email" name="email" id="email" placeholder="ejemplo@leaguefactory.com" 
                            class="w-full bg-zinc-950 border border-zinc-800 text-white rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all placeholder:text-zinc-600"
                            value="<?php echo isset($_SESSION['old_input']['email']) ? htmlspecialchars($_SESSION['old_input']['email']) : ''; ?>" required>
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