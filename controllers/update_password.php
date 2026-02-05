<?php
session_start();
require_once __DIR__ . '/../config/Database.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_pass = $_POST['current_password'];
    $new_pass = $_POST['new_password'];
    $confirm_pass = $_POST['confirm_password'];
    $user_id = $_SESSION['user_id'];

    // 1. Validar que los campos no estén vacíos
    if (empty($current_pass) || empty($new_pass) || empty($confirm_pass)) {
        $_SESSION['error_msg'] = "Todos los campos son obligatorios.";
        header("Location: ../views/user/profileUser.php");
        exit();
    }

    // 2. Verificar que la nueva contraseña y la confirmación coincidan
    if ($new_pass !== $confirm_pass) {
        $_SESSION['error_msg'] = "La nueva contraseña no coincide con la confirmación.";
        header("Location: ../views/user/profileUser.php");
        exit();
    }

    // 3. Obtener la contraseña actual de la base de datos
    $stmt = $pdo->prepare("SELECT password_hash FROM usuarios WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    // 4. Verificar si la contraseña actual es correcta
    if ($user && password_verify($current_pass, $user['password_hash'])) {
        
        // 5. Hashear la nueva contraseña y actualizar
        $new_password_hashed = password_hash($new_pass, PASSWORD_BCRYPT);
        
        $update = $pdo->prepare("UPDATE usuarios SET password_hash = ? WHERE id = ?");
        if ($update->execute([$new_password_hashed, $user_id])) {
            $_SESSION['success_msg'] = "Contraseña actualizada correctamente.";
        } else {
            $_SESSION['error_msg'] = "Error al actualizar la base de datos.";
        }

    } else {
        $_SESSION['error_msg'] = "La contraseña actual es incorrecta.";
    }

    header("Location: ../views/user/profileUser.php");
    exit();
}