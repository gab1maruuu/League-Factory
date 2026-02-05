<section class="min-h-screen py-24 px-4 relative">
    <div class="absolute inset-0 bg-pitch -z-10 opacity-20"></div>

    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-16">
            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-sm font-bold tracking-widest uppercase mb-4">
                <?php echo __('my_competitions'); ?>
            </span>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tighter drop-shadow-xl mb-4">
                <?php echo __('standings'); ?>
            </h1>
        </div>

        <?php if (empty($myLeagues)): ?>
            <div class="text-center py-12 bg-zinc-900/50 rounded-2xl border border-zinc-800">
                <span class="text-4xl mb-4 block">⚽</span>
                <p class="text-zinc-400 text-lg mb-4"><?php echo __('no_active_leagues'); ?></p>
                <a href="index.php?action=join_league" class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-lg transition-colors">
                    <?php echo __('join_a_league'); ?>
                </a>
            </div>
        <?php else: ?>
            <div class="space-y-12">
                <?php foreach ($myLeagues as $league): ?>
                    <div class="bg-zinc-900 rounded-2xl border border-zinc-800 overflow-hidden shadow-xl">
                        <div class="p-6 border-b border-zinc-800 flex justify-between items-center bg-zinc-800/50">
                            <div>
                                <h3 class="text-2xl font-bold text-white"><?php echo htmlspecialchars($league['nombre']); ?></h3>
                                <p class="text-zinc-400 text-sm"><?php echo htmlspecialchars($league['temporada']); ?> • <?php echo htmlspecialchars($league['deporte']); ?></p>
                            </div>
                            <div class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                <?php echo $league['estado'] === 'finalizada' ? 'bg-zinc-800 text-zinc-500' : 'bg-green-500/10 text-green-400'; ?>">
                                <?php echo $league['estado']; ?>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead class="bg-zinc-900/50 text-xs uppercase text-zinc-500 font-bold tracking-wider">
                                    <tr>
                                        <th class="px-6 py-4 text-center w-12">#</th>
                                        <th class="px-6 py-4"><?php echo __('team_col'); ?></th>
                                        <th class="px-6 py-4 text-center"><?php echo __('played_col'); ?></th>
                                        <th class="px-6 py-4 text-center"><?php echo __('won_col'); ?></th>
                                        <th class="px-6 py-4 text-center"><?php echo __('drawn_col'); ?></th>
                                        <th class="px-6 py-4 text-center"><?php echo __('lost_col'); ?></th>
                                        <th class="px-6 py-4 text-center"><?php echo __('goals_for_col'); ?></th>
                                        <th class="px-6 py-4 text-center"><?php echo __('goals_against_col'); ?></th>
                                        <th class="px-6 py-4 text-center"><?php echo __('goal_diff_col'); ?></th>
                                        <th class="px-6 py-4 text-center text-white"><?php echo __('points_col'); ?></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-zinc-800">
                                    <?php if (empty($league['standings'])): ?>
                                        <tr>
                                            <td colspan="10" class="px-6 py-8 text-center text-zinc-500 italic">
                                                <?php echo __('no_standings_data'); ?>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($league['standings'] as $index => $row): ?>
                                            <tr class="hover:bg-zinc-800/30 transition-colors">
                                                <td class="px-6 py-4 text-center text-zinc-500 font-mono">
                                                    <?php echo $index + 1; ?>
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <?php if (!empty($row['escudo_url'])): ?>
                                                            <img src="<?php echo htmlspecialchars($row['escudo_url']); ?>" alt="Logo" class="w-8 h-8 rounded object-cover bg-zinc-800">
                                                        <?php else: ?>
                                                            <div class="w-8 h-8 rounded bg-zinc-800 flex items-center justify-center text-xs">🛡️</div>
                                                        <?php endif; ?>
                                                        <span class="font-bold text-white"><?php echo htmlspecialchars($row['nombre']); ?></span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 text-center text-zinc-300"><?php echo $row['partidos_jugados']; ?></td>
                                                <td class="px-6 py-4 text-center text-green-400"><?php echo $row['victorias']; ?></td>
                                                <td class="px-6 py-4 text-center text-zinc-400"><?php echo $row['empates']; ?></td>
                                                <td class="px-6 py-4 text-center text-red-400"><?php echo $row['derrotas']; ?></td>
                                                <td class="px-6 py-4 text-center text-zinc-300"><?php echo $row['goles_favor']; ?></td>
                                                <td class="px-6 py-4 text-center text-zinc-300"><?php echo $row['goles_contra']; ?></td>
                                                <td class="px-6 py-4 text-center text-zinc-500 font-mono">
                                                    <?php 
                                                        $diff = $row['goles_favor'] - $row['goles_contra']; 
                                                        echo ($diff > 0 ? '+' : '') . $diff; 
                                                    ?>
                                                </td>
                                                <td class="px-6 py-4 text-center font-black text-indigo-400 text-lg">
                                                    <?php echo $row['puntos']; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>
