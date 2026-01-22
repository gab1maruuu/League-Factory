<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.6s ease-out forwards;
        }
    </style>
    <title><?php echo __('create_team_title'); ?></title>
</head>
<body class="bg-zinc-950 text-zinc-200 font-sans overflow-x-hidden selection:bg-indigo-500 selection:text-white">

    <section class="min-h-screen flex flex-col items-center justify-center px-4 relative">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[500px] bg-indigo-600/20 blur-[120px] -z-10 rounded-full"></div>
        <div class="absolute inset-0 bg-pitch -z-10"></div>

        <div class="animate-fade-in-up max-w-2xl w-full bg-zinc-900/50 backdrop-blur-xl border border-zinc-800 rounded-3xl p-8 shadow-2xl">
            
            <div class="test-center mb-8">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-sm font-bold tracking-widest uppercase mb-4">
                    <?php echo __('new_entry'); ?>
                </span>
                <h1 class="text-4xl font-black text-white tracking-tighter drop-shadow-xl mb-2">
                    <?php echo __('create_team_title'); ?>
                </h1>
                <p class="text-zinc-400">
                    <?php echo __('create_team_desc'); ?>
                </p>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
                    <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="index.php?action=store_team" method="POST" enctype="multipart/form-data" class="space-y-6">
                
                <div class="space-y-2">
                    <label for="nombre" class="block text-sm font-bold text-zinc-300 uppercase tracking-wider"><?php echo __('team_name'); ?></label>
                    <input type="text" id="nombre" name="nombre" 
                        class="w-full px-4 py-3 bg-zinc-950/50 border border-zinc-800 rounded-xl text-white placeholder-zinc-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium"
                        placeholder="<?php echo __('team_name_placeholder'); ?>" required>
                </div>

                <div class="space-y-2">
                    <label for="escudo" class="block text-sm font-bold text-zinc-300 uppercase tracking-wider"><?php echo __('badge_png'); ?></label>
                    <input type="file" id="escudo" name="escudo" accept="image/png"
                        class="w-full px-4 py-3 bg-zinc-950/50 border border-zinc-800 rounded-xl text-white placeholder-zinc-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500">
                </div>

                <div class="space-y-2">
                    <label for="capitan_id" class="block text-sm font-bold text-zinc-300 uppercase tracking-wider"><?php echo __('captain_id'); ?></label>
                    <input type="number" id="capitan_id" name="capitan_id" 
                        class="w-full px-4 py-3 bg-zinc-950/50 border border-zinc-800 rounded-xl text-white placeholder-zinc-600 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all font-medium"
                        placeholder="<?php echo __('captain_id_placeholder'); ?>">
                    <p class="text-xs text-zinc-500"><?php echo __('captain_hint'); ?></p>
                </div>

                <div class="pt-4 flex flex-col sm:flex-row gap-4">
                    <button type="submit" 
                        class="w-full py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-indigo-900/20 hover:scale-[1.02] active:scale-[0.98]">
                        <?php echo __('create_team_title'); ?>
                    </button>
                    <a href="index.php?action=home" 
                        class="w-full sm:w-auto py-4 px-8 bg-zinc-900 border border-zinc-800 hover:bg-zinc-800 text-white font-bold rounded-xl transition-all text-center hover:scale-[1.02] active:scale-[0.98]">
                        <?php echo __('cancel'); ?>
                    </a>
                </div>

            </form>
        </div>
    </section>

</body>
</html>