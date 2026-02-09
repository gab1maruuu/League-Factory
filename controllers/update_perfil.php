<?php
session_start();

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
        $_SESSION['user_photo'] = "/public/images/uploads/" . $new_file_name;
        $_SESSION['upload_success'] = true;
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    } else {
        $_SESSION['upload_error'] = 'move_failed';
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }
}