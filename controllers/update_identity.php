<?php
session_start();
require_once __DIR__ . '/../config/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username']);
    $new_nombre   = trim($_POST['nombre']);
    $new_apellido = trim($_POST['apellido']);
    $user_id      = $_SESSION['user_id'];

    if (empty($new_username) || empty($new_nombre) || empty($new_apellido)) {
        $_SESSION['error_msg'] = "Todos los campos son obligatorios.";
        header("Location: ../index.php?action=profile");
        exit;
    }

    try {
        $database = new Database();
        $pdo = $database->getPdo();

        $check = $pdo->prepare("SELECT id FROM usuarios WHERE username = ? AND id != ?");
        $check->execute([$new_username, $user_id]);
        
        if ($check->fetch()) {
            $_SESSION['error_msg'] = "El nombre de usuario ya está en uso.";
            header("Location: ../index.php?action=profile");
            exit;
        }

        // 2. Actualizar identidad
        $stmt = $pdo->prepare("UPDATE usuarios SET username = ?, nombre = ?, apellido = ? WHERE id = ?");
        $stmt->execute([$new_username, $new_nombre, $new_apellido, $user_id]);

        // 3. Actualizar la SESIÓN para que los cambios se vean reflejados inmediatamente
        $_SESSION['user_username'] = $new_username;
        $_SESSION['user_name']     = $new_nombre;
        $_SESSION['user_surname']  = $new_apellido;

        $_SESSION['success_msg'] = "Perfil actualizado correctamente.";

    } catch (PDOException $e) {
        $_SESSION['error_msg'] = "Error al actualizar: " . $e->getMessage();
    }

    header("Location: ../index.php?action=profile");
    exit;
}