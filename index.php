<?php
// Подключаем наш единый файл БД
require_once 'dbconnect.php';

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Панель управления форумом</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; padding: 20px; }
        .item { border-bottom: 1px solid #eee; padding: 10px 0; }
        h1 { color: #333; }
    </style>
</head>
<body>

    <h1>Список форумов</h1>

    <?php
    try {
        // Выполняем запрос к таблице forums (из твоей схемы)
        $stmt = $pdo->query("SELECT name, description, topic_count FROM forums ORDER BY name");
        
        while ($row = $stmt->fetch()) {
            echo "<div class='item'>";
            echo "<strong>" . htmlspecialchars($row['name']) . "</strong><br>";
            echo "<i>" . htmlspecialchars($row['description']) . "</i><br>";
            echo "Тем в разделе: " . $row['topic_count'];
            echo "</div>";
        }
    } catch (PDOException $e) {
        echo "Ошибка запроса: " . $e->getMessage();
    }
    ?>

</body>
</html>