<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?php echo __('your_teams'); ?> | League Factory</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-zinc-950 text-white font-sans">


    <main class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-indigo-400"><?php echo __('your_teams'); ?></h1>

        <?php if (empty($myTeams)): ?>
            <div class="bg-zinc-900 rounded-xl p-8 text-center border border-zinc-800">
                <p class="text-zinc-500 mb-4"><?php echo __('no_teams_yet'); ?></p>
                <a href="index.php?action=create_team" class="inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-500 transition"><?php echo __('create_team'); ?></a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($myTeams as $team): ?>
                    <div class="bg-zinc-900 rounded-xl overflow-hidden border border-zinc-800 hover:border-indigo-500/50 transition duration-300">
                        <div class="p-6 flex items-center space-x-4">
                            <?php if ($team['escudo_url']): ?>
                                <img src="<?php echo htmlspecialchars($team['escudo_url']); ?>" alt="Escudo" class="w-16 h-16 object-cover rounded-full bg-zinc-800">
                            <?php else: ?>
                                <div class="w-16 h-16 rounded-full bg-zinc-800 flex items-center justify-center text-zinc-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            <?php endif; ?>
                            <div>
                                <h3 class="text-xl font-bold text-white"><?php echo htmlspecialchars($team['nombre']); ?></h3>
                                <?php if ($team['capitan_id'] == $_SESSION['user_id']): ?>
                                    <p class="text-sm text-zinc-500"><?php echo __('captain_role'); ?>: Tú</p>
                                <?php else: ?>
                                    <p class="text-sm text-zinc-500"><?php echo __('member_role'); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="bg-zinc-900/50 p-4 border-t border-zinc-800 flex justify-end">
                            <?php if ($team['capitan_id'] == $_SESSION['user_id']): ?>
                                <a href="index.php?action=manage_team&id=<?php echo $team['id']; ?>" class="text-indigo-400 hover:text-indigo-300 text-sm font-semibold flex items-center gap-1">
                                    <?php echo __('manage'); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            <?php else: ?>
                                <span class="text-zinc-500 text-sm font-semibold flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-green-500"></span>
                                    <?php echo __('in_roster'); ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>
