<?php
session_start();
require 'config/Database.php';
require 'controllers/UserController.php';
require 'controllers/PostController.php';

$action = $_GET['action'] ?? 'home'; // Si no hay acción, vamos a 'home'

// 1. Cargamos el Header (siempre visible)
include __DIR__ ."/views/layout/header.php";

// 2. El Switch decide QUÉ mostrar en el centro
switch($action){
    
    // CASO 1: Página de Inicio
    case 'home':
        include __DIR__ ."/views/layout/inicio.php";
        break;

    // CASO 2: Gestión de Usuarios
    case 'login': 
        (new UserController())->login(); 
        break;
        
    case 'register': 
        (new UserController())->register(); 
        break;

    // CASO 3: Posts
    case 'posts': 
        (new PostController())->index(); 
        break;

    // CASO POR DEFECTO: Error 404
    default: 
        echo "<div class='text-white p-10 text-center'>Error 404: Página no encontrada</div>";
        break;
}

// 3. Cargamos el Footer (siempre visible)
include __DIR__ ."/views/layout/footer.php";
?>