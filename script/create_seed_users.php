<?php
require_once __DIR__ . '/../config/Database.php';
$pdo = (new Database())->getPdo();
$password = password_hash("Admin123!", PASSWORD_DEFAULT);
$stmt = $pdo->prepare("INSERT INTO users (username,email,password_hash,role) VALUES (?,?,?,?)");
$stmt->execute(['admin','admin@example.com',$password,'admin']);
echo "seed users done\n";