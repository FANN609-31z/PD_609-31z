<?php
// Работа с сессией
session_start();
if (!isset($_SESSION['user_id'])) { 
    header("Location: login.php"); 
    exit; 
}

// Подключение к БД
require_once 'dbconnect.php';

// Получаем ID раздела из URL
$forum_id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$forum_id) {
    die("Раздел не указан. <a href='index.php'>Вернуться на главную</a>");
}

try {
    // 1. Получаем информацию о самом разделе
    $stmt = $pdo->prepare("SELECT * FROM forums WHERE id = ?");
    $stmt->execute([$forum_id]);
    $forum = $stmt->fetch();

    if (!$forum) { 
        die("Такого раздела не существует. <a href='index.php'>Вернуться на главную</a>"); 
    }

    // ПУНКТ 4: Получаем темы этого раздела из связанной таблицы topics
    // Используем правильное имя колонки: creation_date
    $stmt = $pdo->prepare("SELECT * FROM topics WHERE forum_id = ? ORDER BY creation_date DESC");
    $stmt->execute([$forum_id]);
    $topics = $stmt->fetchAll();

} catch (PDOException $e) {
    die("Ошибка базы данных: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($forum['name']); ?> — Просмотр раздела</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb p-3 bg-white shadow-sm rounded">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Главная</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($forum['name']); ?></li>
            </ol>
        </nav>

        <div class="card shadow-sm border-0">
            <div class="card-header bg-primary text-white p-4">
                <h1 class="h3 mb-1"><?php echo htmlspecialchars($forum['name']); ?></h1>
                <p class="mb-0 opacity-75"><?php echo htmlspecialchars($forum['description']); ?></p>
            </div>

            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0">Список тем:</h5>
                    <a href="add_topic.php?forum_id=<?php echo $forum_id; ?>" class="btn btn-success btn-sm">
                        + Создать новую тему
                    </a>
                </div>
                
                <hr>
                
                <?php if ($topics): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($topics as $topic): ?>
                            <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                                <div>
                                    <h6 class="mb-1">
                                        <a href="topic.php?id=<?php echo $topic['id']; ?>" class="text-decoration-none text-dark fw-bold">
                                            <?php echo htmlspecialchars($topic['title']); ?>
                                        </a>
                                    </h6>
                                    <small class="text-muted">Постов: <?php echo (int)$topic['post_count']; ?></small>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-light text-dark border fw-normal">
                                        <?php echo date('d.m.Y H:i', strtotime($topic['creation_date'])); ?>
                                    </span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <p class="text-muted italic">В этом разделе еще никто не создал ни одной темы.</p>
                    </div>
                <?php endif; ?>
                
                <div class="mt-4 pt-3 border-top">
                    <a href="index.php" class="btn btn-outline-secondary">
                        &larr; Назад к списку разделов
                    </a>
                </div>
            </div>
        </div>
    </div>

    <footer class="text-center py-4 text-muted small">
        &copy; 2026 Лабораторная работа — Форум
    </footer>

</body>
</html>