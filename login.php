<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Вход на форум</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; display: flex; align-items: center; height: 100vh; }
        .login-form { width: 100%; max-width: 400px; margin: auto; padding: 15px; background: #fff; border-radius: 10px; shadow: 0 0 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

<div class="login-form shadow-sm">
    <h2 class="text-center mb-4">Вход</h2>
    <form action="auth.php" method="POST">
        <div class="mb-3">
            <label class="form-label">Логин</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Пароль</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Войти</button>
    </form>
</div>

</body>
</html>