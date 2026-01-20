<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <main class="max-w-4xl px-6 py-12">
        <section class="flex items-center mb-10">
                <img src="/public/images/perfil.png" class="h-35 w-40 opacity-80" alt="Foto de perfil"> 
            <div>
                <h1 class="text-3xl font-bold tracking-tight mb-1">
                    <?php echo __("profile") ?>
                </h1>
                <p class="text-sm text-gray-400 font-medium">
                    <?php echo __("user") ?>: <span class="text-white"><?php echo $_SESSION['user_name'] . " " . $_SESSION['user_surname']; ?></span>
                </p>
            </div>
        </section>

        <nav class="flex border-b border-gray-800 mb-8 space-x-8">
            <a href="#" class="pb-4 text-sm font-bold border-b-2 border-indigo-600 text-white"><?php echo __('settings') ?></a>
            <a href="#" class="pb-4 text-sm font-bold text-gray-400 hover:text-white transition duration-300"><?php echo __('account') ?></a>
            <a href="#" class="pb-4 text-sm font-bold text-gray-400 hover:text-white transition duration-300"><?php echo __('estadisticas') ?></a>
        </nav>

        <section>
            <h2 class="text-sm font-bold mb-4 uppercase tracking-wider text-gray-300"><?php echo __('Account setup') ?></h2>
        </section>
    </main>
</body>
</html>