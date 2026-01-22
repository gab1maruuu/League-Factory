    <section class="min-h-screen py-24 px-4 relative">
        <div class="absolute inset-0 bg-pitch -z-10 opacity-20"></div>

        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <span
                    class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-sm font-bold tracking-widest uppercase mb-4">
                    <?php echo __('active_competition'); ?>
                </span>
                <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter drop-shadow-xl mb-4">
                    <?php echo __('search_league'); ?>
                </h1>
                <p class="text-zinc-400 max-w-2xl mx-auto">
                    <?php echo __('join_league_desc'); ?>
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if (empty($leagues)): ?>
                    <p class="text-center text-zinc-500 col-span-full"><?php echo __('no_leagues'); ?></p>
                <?php else: ?>
                    <?php foreach ($leagues as $league): ?>
                        <?php
                        // Logic for status visuals
                        // 'abierta' (Future), 'en_curso' (Current Week - Joinable based on user logic of "7 days duration"), 'finalizada' (Past)
                        
                        // User Request: 
                        // "aparezcan cada domingo, duren 7 dias par que los equipos puedan unirse (Open), y luego cierren plazas (Closed)"
                        // My DB Logic created:
                        // > NOW: 'abierta' (Future)
                        // <= NOW & > -7 days: 'en_curso' (Current) -> This is the "7 days window".
                        // < -7 days: 'finalizada' (Past)
                        
                        // So: 'en_curso' is the MAIN joinable status.
                        // 'abierta' (Future) -> Let's say "Coming Soon" or just Open.
                        // 'finalizada' -> Closed.
                        
                        $isJoinable = ($league['estado'] === 'en_curso' || $league['estado'] === 'abierta');
                        $cardOpacity = $isJoinable ? 'opacity-100' : 'opacity-50 grayscale';
                        $borderColor = $isJoinable ? 'border-zinc-800 hover:border-indigo-500/50' : 'border-zinc-800/50';
                        $statusText = $league['estado'] === 'finalizada' ? __('status_closed') : ($league['estado'] === 'abierta' ? __('status_soon') : __('status_open'));
                        $statusColor = $league['estado'] === 'finalizada' ? 'text-zinc-500' : ($league['estado'] === 'abierta' ? 'text-teal-400' : 'text-green-400');
                        ?>

                        <div class="bg-zinc-900 rounded-2xl border <?php echo $borderColor; ?> p-6 transition-all duration-300 group relative <?php echo $cardOpacity; ?>">
                            
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <span class="text-xs font-bold uppercase tracking-wider <?php echo $statusColor; ?>">
                                        <?php echo $statusText; ?>
                                    </span>
                                    <h3 class="text-xl font-bold text-white mt-1"><?php echo htmlspecialchars($league['nombre']); ?></h3>
                                </div>
                                <div class="bg-zinc-800 p-2 rounded-lg">
                                    <!-- Icon placeholder based on sport -->
                                    <span class="text-xl">⚽</span>
                                </div>
                            </div>

                            <p class="text-zinc-400 text-sm mb-6 line-clamp-2">
                                <?php echo htmlspecialchars($league['descripcion']); ?>
                            </p>

                            <div class="flex items-center justify-between mt-auto">
                                <div class="text-xs text-zinc-500">
                                    <?php echo $league['deporte']; ?> • <?php echo $league['temporada']; ?>
                                </div>
                                
                                <?php if ($isJoinable): ?>
                                    <a href="index.php?action=join_specific_league&id=<?php echo $league['id']; ?>" 
                                       class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-lg transition-colors shadow-lg shadow-indigo-900/20">
                                        <?php echo __('join_btn'); ?>
                                    </a>
                                <?php else: ?>
                                    <button disabled class="px-4 py-2 bg-zinc-800 text-zinc-500 text-sm font-bold rounded-lg cursor-not-allowed">
                                        <?php echo __('status_closed'); ?>
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            
            <div class="mt-12 text-center">
                <a href="index.php?action=home" class="text-zinc-500 hover:text-white transition-colors text-sm font-bold uppercase tracking-widest">
                    <?php echo __('back_home'); ?>
                </a>
            </div>
    </section>
