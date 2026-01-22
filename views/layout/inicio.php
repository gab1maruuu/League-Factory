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
                transform: translateY(60px) scale(0.95);
                filter: blur(5px);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
                filter: blur(0);
            }
        }

        /*         .scroll-reveal {
            animation: fadeInUp linear both;
            
            animation-timeline: view();
            
            animation-range: entry 5% cover 35%;
        } */
    </style>
</head>

<body class="bg-zinc-950 text-zinc-200 font-sans overflow-x-hidden selection:bg-indigo-500 selection:text-white">

    <section
        class="min-h-screen flex flex-col items-center justify-center text-center px-4 relative border-b border-zinc-800">
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-[500px] bg-indigo-600/20 blur-[120px] -z-10 rounded-full">
        </div>
        <div class="absolute inset-0 bg-pitch -z-10"></div>

        <div class="scroll-reveal space-y-8 max-w-4xl">
            <span
                class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-sm font-bold tracking-widest uppercase">
                <?php echo __('hero_tagline'); ?>

            </span>

            <h1 class="text-6xl md:text-8xl font-black text-white tracking-tighter drop-shadow-xl">
                LEAGUE <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-teal-300">
                    FACTORY
                </span>
            </h1>

            <p class="text-xl text-zinc-400 max-w-2xl mx-auto leading-relaxed">
                <?php echo __('hero_description'); ?>
            </p>

            <div class="pt-4 flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#ligas"
                    class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition-all shadow-indigo hover:scale-105">
                    <?php echo __('find_team'); ?>
                </a>
                <button
                    class="px-8 py-4 bg-zinc-900 border border-zinc-700 hover:bg-zinc-800 text-white font-bold rounded-xl transition-all">
                    <?php echo __('create_my_league'); ?>
                </button>
            </div>
        </div>
    </section>

    <section class="py-16 border-b border-zinc-800 bg-zinc-900/30">
        <div class="max-w-7xl mx-auto px-4 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <?php
            $stats = [
                ["n" => "2,450", "t" => __('Matches played')],
                ["n" => "12,800", "t" => __('Scored goals')],
                ["n" => "350", "t" => __('Referees')],
                ["n" => "85", "t" => __('Active leagues')]
            ];
            foreach ($stats as $s): ?>
                <div class="scroll-reveal">
                    <div class="text-4xl font-black text-white mb-1"><?php echo $s['n']; ?></div>
                    <div class="text-indigo-500 text-xs font-bold uppercase tracking-wider"><?php echo $s['t']; ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section class="py-24 px-4 max-w-7xl mx-auto">
        <div class="text-center mb-16 scroll-reveal">
            <h2 class="text-3xl font-bold text-white mb-4"><?php echo __('tools_title'); ?></h2>
            <p class="text-zinc-500"><?php echo __('tools_subtitle'); ?></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div
                class="scroll-reveal p-8 bg-zinc-900 rounded-2xl border border-zinc-800 hover:border-indigo-500/50 transition duration-300">
                <h3 class="text-xl font-bold text-white mb-2"><?php echo __('digital_records_title'); ?></h3>
                <p class="text-zinc-400 text-sm"><?php echo __('digital_records_desc'); ?></p>
            </div>
            <div
                class="scroll-reveal p-8 bg-zinc-900 rounded-2xl border border-zinc-800 hover:border-indigo-500/50 transition duration-300">
                <h3 class="text-xl font-bold text-white mb-2"><?php echo __('mvp_stats_title'); ?></h3>
                <p class="text-zinc-400 text-sm"><?php echo __('mvp_stats_desc'); ?></p>
            </div>
            <div
                class="scroll-reveal p-8 bg-zinc-900 rounded-2xl border border-zinc-800 hover:border-indigo-500/50 transition duration-300">
                <h3 class="text-xl font-bold text-white mb-2"><?php echo __('auto_calendar_title'); ?></h3>
                <p class="text-zinc-400 text-sm"><?php echo __('auto_calendar_desc'); ?>
                </p>
            </div>
        </div>
    </section>

</body>

</html>