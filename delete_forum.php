<?php
session_start();
// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'dbconnect.php';

// Получаем ID из URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    try {
        // ПУНКТ 6: Контроль целостности
        // Проверяем, есть ли темы в этом разделе
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM topics WHERE forum_id = ?");
        $checkStmt->execute([$id]);
        $topicsCount = $checkStmt->fetchColumn();

        if ($topicsCount > 0) {
            // Если темы есть — запрещаем удаление
            echo "<script>
                    alert('Ошибка: Нельзя удалить раздел, в котором есть темы ($topicsCount шт). Сначала удалите темы!');
                    window.location.href = 'index.php';
                  </script>";
        } else {
            // Если тем нет — удаляем раздел
            $deleteStmt = $pdo->prepare("DELETE FROM forums WHERE id = ?");
            $deleteStmt->execute([$id]);
            
            header("Location: index.php?success=deleted");
            exit;
        }
    } catch (PDOException $e) {
        die("Ошибка при удалении: " . $e->getMessage());
    }
} else {
    header("Location: index.php");
}