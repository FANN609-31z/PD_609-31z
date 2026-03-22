<?php
session_start();
// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
require_once 'dbconnect.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $image_url = trim($_POST['image_url']);

    if (empty($name) || empty($description)) {
        $error = "Пожалуйста, заполните название и описание.";
    } else {
        try {
            // SQL запрос на добавление записи
            $sql = "INSERT INTO forums (name, description, image_url, topic_count) VALUES (?, ?, ?, 0)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$name, $description, $image_url]);
            
            $success = "Раздел успешно создан! <a href='index.php'>Вернуться на главную</a>";
        } catch (PDOException $e) {
            $error = "Ошибка при добавлении: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Создать новый раздел — Форум</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Новый раздел форума</h4>
                </div>
                <div class="card-body">
                    
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>

                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>

                    <form action="add_forum.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Название раздела</label>
                            <input type="text" name="name" class="form-control" placeholder="Например: Игры" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Описание</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="О чем этот раздел?" required></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Иконка (имя файла в папке img)</label>
                            <input type="text" name="image_url" class="form-control" placeholder="example.png (необязательно)">
                            
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="index.php" class="text-secondary text-decoration-none">&larr; Отмена</a>
                            <button type="submit" class="btn btn-success px-4">Создать</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>