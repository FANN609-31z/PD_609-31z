<?php
session_start();      // Запускаем сессию, чтобы иметь доступ к ней
session_destroy();    // Полностью удаляем данные сессии
header("Location: login.php"); // Перенаправляем на страницу входа
exit();