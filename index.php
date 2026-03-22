<?php
require_once 'db.php'; 

try {
    // Получаем все категории, отсортированные по порядку
    $query = "SELECT * FROM categories ORDER BY sort_order ASC";
    $stmt = $pdo->query($query);
    $categories = $stmt->fetchAll();

    foreach ($categories as $category) {
        echo "<div style='border: 1px solid #ccc; margin-bottom: 20px; padding: 10px; border-radius: 5px;'>";
        echo "<h1>" . htmlspecialchars($category['name']) . "</h1>";
        echo "<p><i>" . htmlspecialchars($category['description']) . "</i></p>";

        // Вложенный запрос: получаем форумы для этой категории
        $forumStmt = $pdo->prepare("SELECT * FROM forums WHERE category_id = ? ORDER BY sort_order ASC");
        $forumStmt->execute([$category['id']]);
        $forums = $forumStmt->fetchAll();

        if ($forums) {
            echo "<ul>";
            foreach ($forums as $forum) {
                echo "<li>";
                echo "<strong>" . htmlspecialchars($forum['name']) . "</strong> — ";
                echo "Тем: " . $forum['topic_count'] . " | Сообщений: " . $forum['post_count'];
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>В этой категории пока нет разделов.</p>";
        }
        
        echo "</div>";
    }
} catch (PDOException $e) {
    echo "Ошибка при выборке данных: " . $e->getMessage();
}
?>