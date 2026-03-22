<?php
// Подключаем автозагрузчик Composer (библиотека phpdotenv)
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Загружаем переменные из .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Берем данные из окружения
$host = $_ENV['DB_HOST'];
$port = $_ENV['DB_PORT'];
$db   = $_ENV['DB_NAME'];
$user = $_ENV['DB_USER'];
$pass = $_ENV['DB_PASS'];

try {
    // Формируем строку подключения (DSN)
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8mb4";
    
    // Создаем объект PDO
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // Ошибки в виде исключений
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // Данные как ассоциативные массивы
        PDO::ATTR_EMULATE_PREPARES   => false,                  // Реальная защита от SQL-инъекций
    ]);
} catch (PDOException $e) {
    // Если что-то пошло не так, выводим ошибку и останавливаем скрипт
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}