<?php
session_start();
require_once "../config/Database.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['avatar'])) {
    
    $file = $_FILES['avatar'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed = array('jpg', 'jpeg', 'png', 'webp');

    if (!in_array($ext, $allowed)) {
        $_SESSION['upload_error'] = 'invalid_extension';
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if ($file_error !== 0) {
        $_SESSION['upload_error'] = 'upload_error';
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    if ($file_size > 2000000) {
        $_SESSION['upload_error'] = 'file_too_large';
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    $upload_dir = "../public/images/uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $new_file_name = uniqid('', true) . "." . $ext;
    $destiny = $upload_dir . $new_file_name;

    if (move_uploaded_file($file_tmp, $destiny)) {
    $path_for_db = "/public/images/uploads/" . $new_file_name;
    $user_id = $_SESSION['user_id'] ?? null;

    try {
        $database = new Database();        
        $pdo = $database->getPdo(); 
        $stmt = $pdo->prepare("UPDATE usuarios SET foto_perfil = ? WHERE id = ?");
        $stmt->execute([$path_for_db, $user_id]);

        $_SESSION['user_photo'] = $path_for_db;
        $_SESSION['upload_success'] = true;

    } catch (PDOException $e) {
        $_SESSION['upload_error'] = 'db_error';
    }

    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
    }
}