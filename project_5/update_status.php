<?php
session_start();

// Подключение к базе данных
include 'includes/db.php';

// Проверка авторизации администратора
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Проверка, является ли пользователь админом
$stmt = $db->prepare('SELECT username FROM users WHERE id = :user_id');
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user['username'] !== 'admin') {
    // Если пользователь не админ, перенаправляем его в личный кабинет
    header('Location: dashboard.php');
    exit;
}

// Обработка пост-запроса для обновления статуса
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $violation_id = $_POST['violation_id'];
    $status = $_POST['status'];

    // Подготовка и выполнение SQL-запроса для обновления статуса
    $stmt = $db->prepare('UPDATE violations SET status = :status WHERE id = :id');
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':id', $violation_id);

    if ($stmt->execute()) {
        // Успешное обновление статуса
        $_SESSION['message'] = "Статус обращения успешно обновлен.";
    } else {
        // Ошибка при обновлении статуса
        $_SESSION['message'] = "Ошибка при обновлении статуса.";
    }

    // Перенаправление обратно на страницу администратора
    header('Location: admin.php');
    exit;
}
?>