<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil - League Factory</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-[#121212]">
    <main class="w-full max-w-6xl px-6 py-12 mx-auto">

        <?php if (isset($_SESSION['upload_error'])): ?>
            <div class="mb-6 p-4 bg-red-900/20 border border-red-800 rounded-lg text-red-400 text-sm">
                <?php
                $errors = [
                    'invalid_extension' => 'El formato de imagen no es válido. Usa JPG, PNG o WEBP.',
                    'upload_error' => 'Error al subir el archivo.',
                    'file_too_large' => 'El archivo es demasiado grande. Máximo 2MB.',
                    'move_failed' => 'No se pudo guardar la imagen. Intenta de nuevo.'
                ];
                echo $errors[$_SESSION['upload_error']] ?? 'Error desconocido.';
                unset($_SESSION['upload_error']);
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['upload_success'])): ?>
            <div class="mb-6 p-4 bg-green-900/20 border border-green-800 rounded-lg text-green-400 text-sm">
                Imagen de perfil actualizada correctamente.
            </div>
            <?php unset($_SESSION['upload_success']); ?>
        <?php endif; ?>

        <section class="flex items-center mb-10">
            <form action="/controllers/update_perfil.php" method="POST" enctype="multipart/form-data" id="form-avatar">
                <label for="input-file" class="cursor-pointer relative group block w-40 h-40 overflow-hidden rounded-full border-4 border-zinc-800 shadow-2xl">
                    <img src="<?php echo (!empty($_SESSION['user_photo'])) ? $_SESSION['user_photo'] : '/public/images/perfil.png'; ?>"
                        class="h-full w-full object-cover opacity-90 group-hover:opacity-40 transition-all duration-500"
                        alt="Foto de perfil">
                    <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-indigo-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                        </svg>
                    </div>
                    <input type="file" name="avatar" id="input-file" class="hidden" accept="image/*" onchange="document.getElementById('form-avatar').submit()">
                </label>
            </form>

            <div class="ml-1">
                <h1 class="text-3xl font-bold tracking-tight mb-1"> Bienvenido
                    <span class="text-white"><?php echo htmlspecialchars($_SESSION['user_username']) ?>
                </h1>
                <p class="text-sm text-gray-400 font-medium">
                    <?php echo $_SESSION['user_email'] ?>
                </p>
            </div>
        </section>


        <section class="space-y-12 mb-10">
            <h2 class="w-fit flex text-lg font-bold uppercase tracking-wider text-gray-200 mb-8 space-x-8 border-b-2 border-gray-700">
                <?php echo __('Account setup') ?>
            </h2>
            <div>
                <h3 class="text-gray-300 uppercase tracking-wider font-bold mb-5">Tu identidad</h3>
                <div class="space-y-2 mb-5">
                        <label class="text-xs text-gray-400 uppercase font-semibold ml-1">Nombre de usuario</label>
                        <input type="text" value="<?php echo htmlspecialchars($_SESSION['user_username']) ?>" class="w-full bg-[#121212] border border-gray-800 rounded-lg p-3 text-white hover:border-indigo-600 hover:outline-none transition">
                    </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <div class="space-y-2">
                        <label class="text-xs text-gray-400 uppercase font-semibold ml-1">Nombre</label>
                        <input type="text" value="<?php echo htmlspecialchars($_SESSION['user_name']) ?>" class="w-full bg-[#121212] border border-gray-800 rounded-lg p-3 text-white hover:border-indigo-600 hover:outline-none transition">
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs text-gray-400 uppercase font-semibold ml-1">Apellido</label>
                        <input type="text" value="<?php echo htmlspecialchars($_SESSION['user_surname']) ?>" class="w-full bg-[#121212] border border-gray-800 rounded-lg p-3 text-white hover:border-indigo-600 hover:outline-none transition">

                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-xs text-gray-400 uppercase font-semibold ml-1">Correo Electrónico</label>
                        <input type="email" value="<?php echo htmlspecialchars($_SESSION['user_email']) ?>" readonly class="w-full bg-[#0a0a0a] border border-gray-800 rounded-lg p-3 text-gray-500 cursor-not-allowed">
                        <p class="text-[14px] text-gray-600 ml-1 italic">El correo no se puede cambiar por seguridad.</p>
                    </div>
                </div>
                <button class=" w-full bg-gray-800 hover:bg-gray-900 p-2 rounded-xl mt-5">Guardar cambios</button>
        </section>
        <section class="space-y-12">
            <h2 class="w-fit flex text-lg font-bold uppercase tracking-wider text-gray-200 mb-8 space-x-8 border-b-2 border-gray-700">
                Seguridad y acceso
            </h2>
            <div>
                <div class="mt-12">
                    <h3 class="text-xs font-bold mb-6 uppercase tracking-wider text-gray-500">Protección de la cuenta</h3>
                    <div class="bg-[#121212] border border-gray-800 rounded-lg overflow-hidden">
                        <button onclick="panelPassword()" class="w-full flex items-center justify-between p-4 hover:bg-[#1a1a1a] transition duration-300 group">
                            <div class="flex items-center">
                                <div class="p-2 bg-gray-900 rounded-lg mr-4">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <div class="text-left">
                                    <p class="text-sm font-bold text-white">Cambiar Contraseña</p>
                                    <p class="text-xs text-gray-500">Actualiza tu clave para mantener la cuenta segura</p>
                                </div>
                            </div>
                            <svg id="arrow-icon" class="w-5 h-5 text-gray-600 group-hover:text-white transition transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div id="password-form" class="hidden border-t border-gray-800 p-6 bg-[#0d0d0d] transition-all">
                            <form action="/controllers/update_password.php" method="POST" class="space-y-4 max-w-md mx-auto">
                                <div>
                                    <label class="text-xs text-gray-500 uppercase font-semibold mb-2 block">Contraseña actual</label>
                                    <input type="password" name="current_password" class="w-full bg-[#1a1a1a] border border-gray-800 rounded-lg p-3 text-white hover:border-indigo-600 hover:outline-none">
                                </div>
                                <div class="space-y-4">
                                    <div>
                                        <label class="text-xs text-gray-500 uppercase font-semibold mb-2 block">Nueva contraseña</label>
                                        <div class="relative">
                                            <input type="password" name="new_password" id="new_password"
                                                class="w-full bg-[#1a1a1a] border border-gray-800 rounded-lg p-3 pr-10 text-white focus:border-indigo-600 focus:outline-none transition">

                                            <button type="button" onclick="verPassword('new_password', 'eye-1')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-indigo-500">
                                                <svg id="eye-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="text-xs text-gray-500 uppercase font-semibold mb-2 block">Confirma contraseña</label>
                                        <div class="relative">
                                            <input type="password" name="confirm_password" id="confirm_password"
                                                class="w-full bg-[#1a1a1a] border border-gray-800 rounded-lg p-3 pr-10 text-white focus:border-indigo-600 focus:outline-none transition">

                                            <button type="button" onclick="verPassword('confirm_password', 'eye-2')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-indigo-500">
                                                <svg id="eye-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="pt-2 flex space-x-3">
                                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-bold text-xs uppercase transition">Actualizar</button>
                                    <button type="button" onclick="panelPassword()" class="text-gray-500 hover:text-white text-xs font-bold uppercase transition">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function panelPassword() {
                    const form = document.getElementById('password-form');
                    const arrow = document.getElementById('arrow-icon');
                    form.classList.toggle('hidden');
                    arrow.classList.toggle('rotate-180');
                }

                function verPassword(inputId, iconId) {
                    const input = document.getElementById(inputId);
                    const icon = document.getElementById(iconId);

                    if (input.type === "password") {
                        input.type = "text";
                        // Cambiamos el icono a uno tachado cuando se ve la contraseña
                        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />';
                    } else {
                        input.type = "password";
                        // Volvemos al icono del ojo normal
                        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />';
                    }
                }
            </script>
        </section>
    </main>
</body>

</html>