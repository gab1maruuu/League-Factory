<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title><?php echo __('manage_team_title'); ?> | League Factory</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-zinc-950 text-white font-sans">


    <main class="container mx-auto px-4 py-8">
        <a href="index.php?action=my_teams" class="text-zinc-400 hover:text-white mb-4 inline-block"><?php echo __('back_to_my_teams'); ?></a>

        <div class="bg-zinc-900 rounded-xl p-8 border border-zinc-800 shadow-xl">
            <div class="flex flex-col md:flex-row items-start gap-8">
                <!-- Team Info & Photo -->
                <div class="w-full md:w-1/3 text-center">
                    <?php if ($teamData['escudo_url']): ?>
                        <img src="<?php echo htmlspecialchars($teamData['escudo_url']); ?>" alt="Escudo"
                            class="w-40 h-40 mx-auto object-cover rounded-full bg-zinc-800 mb-4 border-4 border-zinc-800">
                    <?php else: ?>
                        <div
                            class="w-40 h-40 mx-auto rounded-full bg-zinc-800 flex items-center justify-center text-zinc-500 mb-4 border-4 border-zinc-800">
                            <span class="text-4xl font-bold">?</span>
                        </div>
                    <?php endif; ?>

                    <h1 class="text-3xl font-bold mb-2"><?php echo htmlspecialchars($teamData['nombre']); ?></h1>

                    <form action="index.php?action=update_team_photo" method="POST" enctype="multipart/form-data"
                        class="mt-4">
                        <input type="hidden" name="id" value="<?php echo $teamData['id']; ?>">
                        <label class="block mb-2 text-sm text-zinc-400"><?php echo __('change_photo'); ?></label>
                        <input type="file" name="escudo" accept="image/png"
                            class="text-sm text-zinc-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-500 mb-2">
                        <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-500 transition w-full text-sm"><?php echo __('save_photo'); ?></button>
                    </form>
                </div>

                <!-- Members Management -->
                <div class="w-full md:w-2/3">
                    <h2 class="text-2xl font-bold mb-6 text-indigo-400 border-b border-zinc-800 pb-2"><?php echo __('team_members'); ?></h2>

                    <div class="mb-4 relative">
                        <label class="block text-sm font-bold text-zinc-400 mb-2"><?php echo __('add_member_label'); ?></label>
                        <div class="flex gap-2">
                             <input type="text" id="user-search" placeholder="<?php echo __('search_user_placeholder'); ?>"
                            class="flex-1 bg-zinc-950 border border-zinc-800 text-white rounded-lg px-4 py-2 focus:outline-none focus:border-indigo-500 transition">
                             <button type="button" id="btn-add-username" class="bg-indigo-600 hover:bg-indigo-500 text-white px-4 py-2 rounded-lg font-bold transition">
                                 <?php echo __('add_btn'); ?>
                             </button>
                        </div>
                       
                        <div id="search-results"
                            class="absolute z-10 w-full bg-zinc-800 border border-zinc-700 rounded-lg mt-1 hidden max-h-60 overflow-y-auto shadow-xl">
                        </div>
                    </div>

                    <!-- Members List -->
                    <div class="space-y-4">
                        <!-- Captain (Always Show) -->
                        <div class="flex items-center justify-between p-4 bg-zinc-950/30 rounded-lg border border-zinc-800 border-dashed">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 font-bold">
                                    <?php echo strtoupper(substr($captain['username'], 0, 1)); ?>
                                </div>
                                <div>
                                    <p class="font-bold text-white"><?php echo htmlspecialchars($captain['username']); ?></p>
                                    <p class="text-xs text-zinc-500">
                                        <?php echo htmlspecialchars($captain['nombre'] . ' ' . $captain['apellido']); ?>
                                    </p>
                                </div>
                            </div>
                            <span class="text-xs text-indigo-500 font-bold border border-indigo-500/30 px-2 py-1 rounded"><?php echo __('captain_creator'); ?></span>
                        </div>

                        <?php 
                        // Filter out captain from members list to avoid duplication if they are in the query
                        $otherMembers = array_filter($members, function($m) use ($captain) {
                            return $m['id'] != $captain['id'];
                        });
                        ?>

                        <?php if (empty($otherMembers)): ?>
                            <p class="text-zinc-500 italic text-sm ml-2"><?php echo __('no_other_members'); ?></p>
                        <?php else: ?>
                            <?php foreach ($otherMembers as $member): ?>
                                <div class="flex items-center justify-between p-4 bg-zinc-950/50 rounded-lg border border-zinc-800">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-400 font-bold">
                                            <?php echo strtoupper(substr($member['username'], 0, 1)); ?>
                                        </div>
                                        <div>
                                            <p class="font-bold text-white"><?php echo htmlspecialchars($member['username']); ?>
                                            </p>
                                            <p class="text-xs text-zinc-500">
                                                <?php echo htmlspecialchars($member['nombre'] . ' ' . $member['apellido']); ?>
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <form action="index.php?action=remove_member" method="POST"
                                        onsubmit="return confirm('<?php echo __('remove_member_confirm'); ?>');">
                                        <input type="hidden" name="team_id" value="<?php echo $teamData['id']; ?>">
                                        <input type="hidden" name="user_id" value="<?php echo $member['id']; ?>">
                                        <button type="submit" class="text-red-400 hover:text-red-300 transition"
                                            title="Eliminar miembro">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 000-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const searchInput = document.getElementById('user-search');
        const resultsContainer = document.getElementById('search-results');

        searchInput.addEventListener('input', async function () {
            const query = this.value;
            if (query.length < 3) {
                resultsContainer.classList.add('hidden');
                resultsContainer.innerHTML = '';
                return;
            }

            try {
                const response = await fetch(`index.php?action=search_user&q=${encodeURIComponent(query)}`);
                const users = await response.json();

                resultsContainer.innerHTML = '';
                if (users.length > 0) {
                    users.forEach(user => {
                        const div = document.createElement('div');
                        div.className = 'p-3 hover:bg-zinc-700 cursor-pointer flex justify-between items-center border-b border-zinc-700 last:border-0';
                        div.innerHTML = `
                            <span class="font-medium">${user.username} <span class="text-xs text-zinc-500">(${user.nombre} ${user.apellido})</span></span>
                            <button class="bg-indigo-600 text-xs px-3 py-1 rounded hover:bg-indigo-500 text-white" onclick="addMember(${user.id})"><?php echo __('add_btn'); ?></button>
                        `;
                        resultsContainer.appendChild(div);
                    });
                    resultsContainer.classList.remove('hidden');
                } else {
                    resultsContainer.innerHTML = '<div class="p-3 text-zinc-400 text-sm"><?php echo __('user_not_found'); ?></div>';
                    resultsContainer.classList.remove('hidden');
                }
            } catch (e) {
                console.error("Error searching", e);
            }
        });

        function addMember(userId, username = null) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'index.php?action=add_member';

            if (userId) {
                const uInput = document.createElement('input');
                uInput.type = 'hidden';
                uInput.name = 'user_id';
                uInput.value = userId;
                form.appendChild(uInput);
            }
            
            if (username) {
                 const nInput = document.createElement('input');
                nInput.type = 'hidden';
                nInput.name = 'username';
                nInput.value = username;
                form.appendChild(nInput);
            }

            const tInput = document.createElement('input');
            tInput.type = 'hidden';
            tInput.name = 'team_id';
            tInput.value = '<?php echo $teamData['id']; ?>';

            form.appendChild(tInput);
            document.body.appendChild(form);
            form.submit();
        }

        document.getElementById('btn-add-username').addEventListener('click', function() {
            const username = document.getElementById('user-search').value.trim();
            if(username) {
                if(confirm('<?php echo str_replace("'", "\'", sprintf(__('add_member_confirm'), "'+username+'")); ?>')) {
                    addMember(null, username);
                }
            }
        });

        // Hide results when clicking outside
        document.addEventListener('click', function (e) {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.classList.add('hidden');
            }
        });
    </script>
</body>

</html>