<?php
session_start();
require_once 'utils/i18n.php';

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php?action=home");
    exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo get_locale(); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
</head>

<body>
    <? include __DIR__ . "/views/layout/headerAdmin.php"; ?>

    <div class="flex justify-center py-8">
        <h1 class="text-center text-6xl md:text-6xl font-black text-white tracking-tighter drop-shadow-xl">
            PANEL <br>
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-teal-300">
                ADMIN
            </span>
        </h1>
    </div>
    <section id="usuarios">
        <h2 class="ml-52 text-4xl font-black text-white tracking-tighter drop-shadow-xl mb-8">Administración de Usuarios
        </h2>
        <?php
        require_once 'config/Database.php';
        require_once 'models/User.php';
        $db = (new Database())->getPdo();
        $userModel = new User($db);
        $users = $userModel->getAll();
        ?>
        <div class="container mx-auto px-4">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    <?= $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    <?= $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <div class="overflow-x-auto bg-zinc-900 rounded-lg shadow-xl border border-zinc-800">
                <table class="w-full text-left text-zinc-300">
                    <thead class="bg-zinc-800 text-indigo-400 uppercase text-xs font-bold">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Nombre</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Rol</th>
                            <th class="px-6 py-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        <?php foreach ($users as $u): ?>
                            <tr class="hover:bg-zinc-800 transition-colors">
                                <td class="px-6 py-4 font-mono text-sm text-zinc-500"><?= $u['id'] ?></td>
                                <td class="px-6 py-4 font-semibold text-white">
                                    <?= htmlspecialchars($u['nombre'] . ' ' . $u['apellido']) ?>
                                </td>
                                <td class="px-6 py-4"><?= htmlspecialchars($u['email']) ?></td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-block px-2 py-1 text-xs font-bold rounded 
                                        <?= $u['rol'] === 'admin' ? 'bg-indigo-900 text-indigo-300' : 'bg-zinc-700 text-zinc-300' ?>">
                                        <?= strtoupper($u['rol']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick='openEditModal(<?= json_encode($u) ?>)'
                                        class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1 rounded text-sm font-bold transition-colors">
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="editModal"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm">
            <div class="bg-zinc-900 border border-zinc-700 p-8 rounded-xl shadow-2xl w-full max-w-md relative">
                <button onclick="closeEditModal()" class="absolute top-4 right-4 text-zinc-500 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <h3 class="text-2xl font-bold text-white mb-6">Editar Usuario</h3>

                <form action="index.php?action=update_user" method="POST" class="space-y-4">
                    <input type="hidden" name="id" id="edit_id">

                    <div>
                        <label class="block text-zinc-400 text-sm font-bold mb-2">Nombre</label>
                        <input type="text" name="nombre" id="edit_nombre"
                            class="w-full bg-zinc-800 border border-zinc-700 rounded p-2 text-white focus:outline-none focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-zinc-400 text-sm font-bold mb-2">Apellido</label>
                        <input type="text" name="apellido" id="edit_apellido"
                            class="w-full bg-zinc-800 border border-zinc-700 rounded p-2 text-white focus:outline-none focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-zinc-400 text-sm font-bold mb-2">Email</label>
                        <input type="email" name="email" id="edit_email"
                            class="w-full bg-zinc-800 border border-zinc-700 rounded p-2 text-white focus:outline-none focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-zinc-400 text-sm font-bold mb-2">Rol</label>
                        <select name="rol" id="edit_rol"
                            class="w-full bg-zinc-800 border border-zinc-700 rounded p-2 text-white focus:outline-none focus:border-indigo-500">
                            <option value="usuario">Usuario</option>
                            <option value="organizador">Organizador</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2 px-4 rounded transition-colors mt-4">
                        Guardar Cambios
                    </button>
                </form>
            </div>
        </div>

        <script>
            function openEditModal(user) {
                document.getElementById('edit_id').value = user.id;
                document.getElementById('edit_nombre').value = user.nombre;
                document.getElementById('edit_apellido').value = user.apellido;
                document.getElementById('edit_email').value = user.email;
                document.getElementById('edit_rol').value = user.rol;

                document.getElementById('editModal').classList.remove('hidden');
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
            }
        </script>
    </section>
    <section id="equipos">
        <h2 class="mt-8 ml-52 text-4xl font-black text-white tracking-tighter drop-shadow-xl mb-8">Modificar Equipos
        </h2>
        <?php
        require_once 'models/Team.php';
        $teamModel = new Team($db);
        $equipos = $teamModel->getAll();
        ?>
        <div class="container mx-auto px-4">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    <?= $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    <?= $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <div class="overflow-x-auto bg-zinc-900 rounded-lg shadow-xl border border-zinc-800">
                <table class="w-full text-left text-zinc-300">
                    <thead class="bg-zinc-800 text-indigo-400 uppercase text-xs font-bold">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Nombre</th>
                            <th class="px-6 py-4">Escudo</th>
                            <th class="px-6 py-4">Capitan ID</th>
                            <th class="px-6 py-4 text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        <?php foreach ($equipos as $t): ?>
                            <tr class="hover:bg-zinc-800 transition-colors">
                                <td class="px-6 py-4 font-mono text-sm text-zinc-500"><?= $t['id'] ?></td>
                                <td class="px-6 py-4 font-semibold text-white">
                                    <?= htmlspecialchars($t['nombre']) ?>
                                </td>
                                <td class="px-6 py-4">
                                    <img src="<?= htmlspecialchars($t['escudo_url'] ?? '') ?>" alt="Escudo"
                                        class="h-8 w-8 object-contain bg-white rounded-full">
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-zinc-400"><?= $t['capitan_id'] ?? 'N/A' ?></span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick='openEditTeamModal(<?= json_encode($t) ?>)'
                                        class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1 rounded text-sm font-bold transition-colors">
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="editTeamModal"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm">
            <div class="bg-zinc-900 border border-zinc-700 p-8 rounded-xl shadow-2xl w-full max-w-md relative">
                <button onclick="closeEditTeamModal()" class="absolute top-4 right-4 text-zinc-500 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <h3 class="text-2xl font-bold text-white mb-6">Editar Equipo</h3>

                <form action="index.php?action=update_team" method="POST" class="space-y-4">
                    <input type="hidden" name="id" id="edit_team_id">

                    <div>
                        <label class="block text-zinc-400 text-sm font-bold mb-2">Nombre</label>
                        <input type="text" name="nombre" id="edit_team_nombre"
                            class="w-full bg-zinc-800 border border-zinc-700 rounded p-2 text-white focus:outline-none focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-zinc-400 text-sm font-bold mb-2">Escudo URL</label>
                        <input type="text" name="escudo" id="edit_team_escudo"
                            class="w-full bg-zinc-800 border border-zinc-700 rounded p-2 text-white focus:outline-none focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-zinc-400 text-sm font-bold mb-2">Capitán ID</label>
                        <input type="number" name="capitan_id" id="edit_team_capitan"
                            class="w-full bg-zinc-800 border border-zinc-700 rounded p-2 text-white focus:outline-none focus:border-indigo-500">
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2 px-4 rounded transition-colors mt-4">
                        Guardar Cambios
                    </button>
                </form>
            </div>
        </div>

        <script>
            function openEditTeamModal(team) {
                document.getElementById('edit_team_id').value = team.id;
                document.getElementById('edit_team_nombre').value = team.nombre;
                document.getElementById('edit_team_escudo').value = team.escudo_url;
                document.getElementById('edit_team_capitan').value = team.capitan_id;

                document.getElementById('editTeamModal').classList.remove('hidden');
            }

            function closeEditTeamModal() {
                document.getElementById('editTeamModal').classList.add('hidden');
            }
        </script>
    </section>
    <section id="ligas">
        <h2 class="mt-8 ml-52 text-4xl font-black text-white tracking-tighter drop-shadow-xl mb-8">Modificar Ligas</h2>
        <?php
        require_once 'config/Database.php';
        require_once 'models/League.php';
        $db = (new Database())->getPdo();
        $leagueModel = new League($db);
        $leagues = $leagueModel->getAll();
        ?>
        <div class="container mx-auto px-4">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="bg-green-500 text-white p-4 rounded mb-4">
                    <?= $_SESSION['success'];
                    unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-500 text-white p-4 rounded mb-4">
                    <?= $_SESSION['error'];
                    unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <div class="overflow-x-auto bg-zinc-900 rounded-lg shadow-xl border border-zinc-800">
                <table class="w-full text-left text-zinc-300">
                    <thead class="bg-zinc-800 text-indigo-400 uppercase text-xs font-bold">
                        <tr>
                            <th class="px-6 py-4">ID</th>
                            <th class="px-6 py-4">Nombre</th>
                            <th class="px-6 py-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-800">
                        <?php foreach ($leagues as $l): ?>
                            <tr class="hover:bg-zinc-800 transition-colors">
                                <td class="px-6 py-4 font-mono text-sm text-zinc-500">
                                    <?= $l['id'] ?>
                                </td>
                                <td class="px-6 py-4 font-semibold text-white">
                                    <?= htmlspecialchars($l['nombre']) ?>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick='openEditLeagueModal(<?= json_encode($l) ?>)'
                                        class="bg-indigo-600 hover:bg-indigo-500 text-white px-3 py-1 rounded text-sm font-bold transition-colors">
                                        Editar
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="editLeagueModal"
            class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-sm">
            <div class="bg-zinc-900 border border-zinc-700 p-8 rounded-xl shadow-2xl w-full max-w-md relative">
                <button onclick="closeEditLeagueModal()" class="absolute top-4 right-4 text-zinc-500 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <h3 class="text-2xl font-bold text-white mb-6">Editar Liga</h3>

                <form action="index.php?action=update_league" method="POST" class="space-y-4">
                    <input type="hidden" name="id" id="edit_league_id">

                    <div>
                        <label class="block text-zinc-400 text-sm font-bold mb-2">Nombre</label>
                        <input type="text" name="nombre" id="edit_league_nombre"
                            class="w-full bg-zinc-800 border border-zinc-700 rounded p-2 text-white focus:outline-none focus:border-indigo-500">
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-500 text-white font-bold py-2 px-4 rounded transition-colors mt-4">
                        Guardar Cambios
                    </button>
                </form>
            </div>
        </div>

        <script>
            function openEditLeagueModal(league) {
                document.getElementById('edit_league_id').value = league.id;
                document.getElementById('edit_league_nombre').value = league.nombre;

                document.getElementById('editLeagueModal').classList.remove('hidden');
            }

            function closeEditLeagueModal() {
                document.getElementById('editLeagueModal').classList.add('hidden');
            }
        </script>
    </section>
    <? include __DIR__ . "/views/layout/footer.php"; ?>
</body>

</html>