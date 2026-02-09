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
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="col-span-full bg-red-500/10 border border-red-500/20 text-red-400 px-4 py-3 rounded-xl flex items-center gap-3 backdrop-blur-sm">
                        <span class="text-xl">⚠️</span>
                        <span class="font-medium"><?php echo $_SESSION['error']; ?></span>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="col-span-full bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl flex items-center gap-3 backdrop-blur-sm">
                        <span class="text-xl">✅</span>
                        <span class="font-medium"><?php echo $_SESSION['success']; ?></span>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (empty($leagues)): ?>
                    <p class="text-center text-zinc-500 col-span-full"><?php echo __('no_leagues'); ?></p>
                <?php else: ?>
                    <?php foreach ($leagues as $league): ?>
                        <?php
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
                                    <span class="text-xl">⚽</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-auto">
                                <div class="text-xs text-zinc-500">
                                    <?php echo $league['deporte']; ?> • <?php echo $league['temporada']; ?>
                                    <span class="ml-2 px-2 py-0.5 rounded-full bg-zinc-800 border border-zinc-700 text-zinc-400">
                                        <?php echo isset($league['participant_count']) ? $league['participant_count'] : 0; ?>/14
                                    </span>
                                </div>
                                
                                <?php if ($isJoinable): ?>
                                    <button onclick="openJoinModal(<?php echo $league['id']; ?>, '<?php echo htmlspecialchars($league['nombre'], ENT_QUOTES); ?>')" 
                                       class="px-4 py-2 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-lg transition-colors shadow-lg shadow-indigo-900/20">
                                        <?php echo __('join_btn'); ?>
                                    </button>
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

    <div id="joinModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" onclick="closeJoinModal()"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-zinc-900 border border-zinc-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    
                    <form action="index.php?action=join_league_submit" method="POST" class="p-6">
                        <input type="hidden" name="league_id" id="modalLeagueId">
                        
                        <div class="sm:flex sm:items-start mb-6">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-500/10 sm:mx-0 sm:h-10 sm:w-10">
                                <span class="text-xl">🏆</span>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                <h3 class="text-lg font-bold leading-6 text-white" id="modal-title"><?php echo __('join_to'); ?> <span id="modalLeagueName" class="text-indigo-400"></span></h3>
                                <div class="mt-2">
                                    <p class="text-sm text-zinc-400"><?php echo __('select_team_desc'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="mb-6 bg-zinc-800/50 p-4 rounded-lg border border-zinc-800">
                            <h4 class="text-sm font-bold text-white mb-2"><?php echo __('registered_teams'); ?> (<span id="participantCount">0</span>/14)</h4>
                            <div id="participantsList" class="flex flex-wrap gap-2 max-h-32 overflow-y-auto">
                                <span class="text-xs text-zinc-500"><?php echo __('loading'); ?></span>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="team_id" class="block text-sm font-medium text-zinc-300 mb-2"><?php echo __('select_your_team'); ?></label>
                            <?php if (empty($myTeams)): ?>
                                <div class="p-4 rounded-lg bg-red-500/10 border border-red-500/20 text-red-400 text-sm">
                                    <?php echo __('no_eligible_teams'); ?> <a href="index.php?action=create_team" class="underline hover:text-red-300"><?php echo __('create_one_first'); ?></a>.
                                </div>
                            <?php else: ?>
                                <select name="team_id" id="team_id" required class="block w-full rounded-lg border-zinc-700 bg-zinc-800 text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm p-2.5">
                                    <option value="" disabled selected><?php echo __('choose_team_placeholder'); ?></option>
                                    <?php foreach ($myTeams as $team): ?>
                                        <option value="<?php echo $team['id']; ?>"><?php echo htmlspecialchars($team['nombre']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            <?php endif; ?>
                        </div>

                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse gap-3">
                            <button type="submit" <?php echo empty($myTeams) ? 'disabled' : ''; ?>
                                class="inline-flex w-full justify-center rounded-lg bg-indigo-600 px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-indigo-500 sm:w-auto disabled:opacity-50 disabled:cursor-not-allowed">
                                <?php echo __('subscribe_btn'); ?>
                            </button>
                            <button type="button" onclick="closeJoinModal()" 
                                class="mt-3 inline-flex w-full justify-center rounded-lg bg-zinc-800 px-3 py-2 text-sm font-bold text-zinc-300 shadow-sm ring-1 ring-inset ring-zinc-700 hover:bg-zinc-700 sm:mt-0 sm:w-auto">
                                <?php echo __('cancel'); ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openJoinModal(leagueId, leagueName) {
            document.getElementById('modalLeagueId').value = leagueId;
            document.getElementById('modalLeagueName').textContent = leagueName;
            document.getElementById('joinModal').classList.remove('hidden');

            const listContainer = document.getElementById('participantsList');
            const countSpan = document.getElementById('participantCount');
            listContainer.innerHTML = '<span class="text-xs text-zinc-500"><?php echo __('loading'); ?></span>';
            
            fetch('index.php?action=get_league_participants&id=' + leagueId)
                .then(response => response.json())
                .then(data => {
                    countSpan.textContent = data.length;
                    listContainer.innerHTML = '';
                    if (data.length === 0) {
                        listContainer.innerHTML = '<span class="text-xs text-zinc-500 italic"><?php echo __('no_teams_joined_yet'); ?></span>';
                    } else {
                        data.forEach(team => {
                            const badge = document.createElement('div');
                            badge.className = 'flex items-center gap-2 px-3 py-1.5 rounded-md bg-zinc-800 border border-zinc-700';
                            
                            const name = document.createElement('span');
                            name.className = 'text-xs text-zinc-300 font-medium';
                            name.textContent = team.nombre;
                            
                            badge.appendChild(name);
                            listContainer.appendChild(badge);
                        });
                    }
                })
                .catch(err => {
                    console.error(err);
                    listContainer.innerHTML = '<span class="text-xs text-red-500"><?php echo __('error_loading_teams'); ?></span>';
                });
        }

        function closeJoinModal() {
            document.getElementById('joinModal').classList.add('hidden');
        }
    </script>
