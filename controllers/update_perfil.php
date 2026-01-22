<?php
session_start();

// 1. Verificar que se haya enviado un archivo
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['avatar'])) {
    
    $file = $_FILES['avatar'];
    $file_name = $file['name'];
    $file_tmp = $file['tmp_name'];
    $file_size = $file['size'];
    $file_error = $file['error'];

    // 2. Definir extensiones permitidas
    $ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $allowed = array('jpg', 'jpeg', 'png', 'webp');

    if (in_array($ext, $allowed)) {
        if ($file_error === 0) {
            if ($file_size <= 2000000) { // Limitar a 2MB
                
                // 3. Crear un nombre único (ej: 65af23..._foto.jpg)
                $new_file_name = uniqid('', true) . "." . $ext;
                
                // 4. Ruta donde se guardará físicamente
                $destiny = "../public/images/uploads/" . $new_file_name;

                if (move_uploaded_file($file_tmp, $destiny)) {
                    // 5. Actualizar la variable de sesión para que se vea el cambio
                    $_SESSION['user_photo'] = "/public/images/uploads/" . $new_file_name;
                    
                    // 6. Redirigir de vuelta al perfil
                    header("Location: ../index.php?success=profile_updated");
                }
            }
        }
    }
}