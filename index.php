<?php
// Инициализация сессии 
session_start();

// Если не залогинен, отправляем на вход
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Подключение к БД через наш единый файл
require_once 'dbconnect.php';

// Определение роли (для удобства создадим переменную)
$isAdmin = ($_SESSION['username'] === 'admin');
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Главная — Форум</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .forum-icon { width: 50px; height: 50px; object-fit: cover; border-radius: 8px; }
        .navbar-custom { background-color: #212529; }
    </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark navbar-custom mb-4 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">MyForum</a>
        <div class="navbar-text text-white d-flex align-items-center">
            <span class="me-3">
                Вы вошли как: <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong>
                <?php if ($isAdmin): ?>
                    <span class="badge bg-danger ms-1">Admin</span>
                <?php else: ?>
                    <span class="badge bg-info ms-1 text-dark">User</span>
                <?php endif; ?>
            </span>
            <a href="logout.php" class="btn btn-outline-danger btn-sm">Выход</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="row">
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">Список разделов форума</h2>
                
                <?php if ($isAdmin): ?>
                    <a href="add_forum.php" class="btn btn-success shadow-sm">
                        <i class="bi bi-plus-lg"></i> + Создать раздел
                    </a>
                <?php endif; ?>
            </div>
            
            <div class="list-group shadow-sm border-0">
                <?php
                try {
                    // Просмотр данных из основной таблицы
                    $stmt = $pdo->query("SELECT * FROM forums ORDER BY name ASC");
                    $forums = $stmt->fetchAll();

                    if ($forums) {
                        foreach ($forums as $forum) {
                            $img = (!empty($forum['image_url'])) ? htmlspecialchars($forum['image_url']) : 'default_forum.png';
                            ?>
                            <div class="list-group-item list-group-item-action d-flex align-items-center p-3 border-start-0 border-end-0">
                                
                                <img src="img/<?php echo $img; ?>" 
                                     class="forum-icon me-3 shadow-sm border" 
                                     alt="icon">
                                
                                <div class="flex-grow-1">
                                    <h5 class="mb-1">
                                        <a href="forum_view.php?id=<?php echo (int)$forum['id']; ?>" class="text-decoration-none text-dark fw-bold">
                                            <?php echo htmlspecialchars($forum['name']); ?>
                                        </a>
                                    </h5>
                                    <p class="mb-0 text-muted small"><?php echo htmlspecialchars($forum['description']); ?></p>
                                </div>

                                <div class="text-end ms-3">
                                    <span class="badge bg-primary rounded-pill mb-2">
                                        <?php echo (int)$forum['topic_count']; ?> тем
                                    </span>
                                    
                                    <?php if ($isAdmin): ?>
                                        <div class="mt-1">
                                            <a href="delete_forum.php?id=<?php echo (int)$forum['id']; ?>" 
                                               class="btn btn-sm btn-outline-danger py-0" 
                                               style="font-size: 0.75rem;"
                                               onclick="return confirm('Вы уверены? Все данные раздела будут проверены перед удалением.')">
                                               Удалить
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<div class='p-5 text-center bg-white rounded shadow-sm text-muted'>Разделы еще не созданы.</div>";
                    }
                } catch (PDOException $e) {
                    echo "<div class='alert alert-danger m-3'>Ошибка БД: " . htmlspecialchars($e->getMessage()) . "</div>";
                }
                ?>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Информация</div>
                <div class="card-body text-center">
                    <p class="small text-muted mb-2">Текущая дата и время:</p>
                    <p class="fw-bold mb-3"><?php echo date('d.m.Y H:i'); ?></p>
                    <hr>
                    <p class="small text-muted">Ваш статус доступа:</p>
                    <?php if ($isAdmin): ?>
                        <div class="alert alert-warning py-1 small">Полный доступ (Админ)</div>
                    <?php else: ?>
                        <div class="alert alert-light border py-1 small text-muted">Только чтение разделов</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="text-center py-4 text-muted mt-5 border-top">
    <small>&copy; 2026 Форум — Байрамгулов Ф.Ф. 609-31з</small>
</footer>

</body>
</html>