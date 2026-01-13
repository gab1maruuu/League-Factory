<?php
session_start();

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: index.php?action=home");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

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

    </section>
    <section id="equipos">

    </section>

    <section id="ligas">

    </section>
    <? include __DIR__ . "/views/layout/footer.php"; ?>
</body>

</html>