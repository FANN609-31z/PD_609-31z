<?php
// Используем точный IP из консоли. Так-как у меня по локал хосту ничего не получилось 
$host = '127.0.1.15'; 
$port = '3306'; 
$db   = 'forum_db';
$user = 'root';
$pass = ''; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     echo "Успех!"; 
} catch (\PDOException $e) {
     die("Ошибка подключения: " . $e->getMessage());
}
?>