<?php
session_start();
require_once __DIR__ . '/../config/Database.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_pass = $_POST['current_password'] ?? '';
    $new_pass = $_POST['new_password'] ?? '';
    $confirm_pass = $_POST['confirm_password'] ?? '';
    $user_id = $_SESSION['user_id'] ?? null;

    if (!$user_id) {
        header("Location: ../index.php?action=login");
        exit();
    }

    try {
        $database = new Database();
        $pdo = $database->getPdo();

        $stmt = $pdo->prepare("SELECT password_hash FROM usuarios WHERE id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch();

        if ($user && password_verify($current_pass, $user['password_hash'])) {
            
            if ($new_pass !== $confirm_pass) {
                $_SESSION['error_msg'] = "La nueva contraseña no coincide.";
            } else {
                $new_password_hashed = password_hash($new_pass, PASSWORD_BCRYPT);
                
                $update = $pdo->prepare("UPDATE usuarios SET password_hash = ? WHERE id = ?");
                $update->execute([$new_password_hashed, $user_id]);
                
                $_SESSION['success_msg'] = "Contraseña actualizada correctamente.";
            }
        } else {
            $_SESSION['error_msg'] = "La contraseña actual es incorrecta.";
        }

    } catch (PDOException $e) {
        $_SESSION['error_msg'] = "Error en el servidor.";
    }

    header("Location: ../index.php?action=profile");
    exit();
}