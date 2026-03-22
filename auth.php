<?php
session_start();
require_once 'dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_input = $_POST['username'];
    $pass_input = $_POST['password'];

    // Ищем пользователя по username 
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$user_input]);
    $user = $stmt->fetch();

   
    // сравнение
    if ($user && $pass_input == $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        
        header("Location: index.php"); // Переходим на главную
        exit;
    } else {
        die("Ошибка: Неверный логин или пароль. <a href='login.php'>Назад</a>");
    }
}