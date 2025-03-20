<?php
session_start();

// Подключение к базе данных
include 'includes/db.php';

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Проверка, является ли пользователь админом
$stmt = $db->prepare('SELECT username FROM users WHERE id = :user_id');
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user['username'] === 'admin') {
    // Если пользователь - админ, перенаправляем его на страницу администратора
    header('Location: admin.php');
    exit;
}

// Обработка формы создания обращения
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $car_number = $_POST['car_number'];
    $description = $_POST['description'];
    $date = date('Y-m-d H:i:s');

    // Подготовка и выполнение SQL-запроса
    $stmt = $db->prepare('INSERT INTO violations (user_id, car_number, description, date) VALUES (:user_id, :car_number, :description, :date)');
    $stmt->bindParam(':user_id', $_SESSION['user_id']);
    $stmt->bindParam(':car_number', $car_number);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date', $date);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Обращение успешно отправлено</div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка при отправке обращения</div>";
    }
}

// Получение списка обращений юзера
$stmt = $db->prepare('SELECT * FROM violations WHERE user_id = :user_id');
$stmt->bindParam(':user_id', $_SESSION['user_id']);
$stmt->execute();
$violations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Кнопка выхода -->
        <div class="text-end mb-4">
            <a href="logout.php" class="btn btn-danger">Выйти</a>
        </div>

        <h1 class="text-center mb-4">Личный кабинет</h1>

        <!-- Форма создания нового обращения -->
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title">Создать новое обращение</h5>
                <form method="post" action="dashboard.php">
                    <div class="mb-3">
                        <label for="car_number" class="form-label">Регистрационный номер автомобиля</label>
                        <input type="text" class="form-control" id="car_number" name="car_number" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Описание нарушения</label>
                        <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Отправить</button>
                    <button type="button" class="btn btn-secondary" onclick="window.location.href='dashboard.php'">Отменить</button>
                </form>
            </div>
        </div>

        <!-- Список обращений пользователя -->
        <h2 class="mb-3">Мои обращения</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Номер автомобиля</th>
                    <th>Описание</th>
                    <th>Статус</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($violations as $violation): ?>
                <tr>
                    <td><?= htmlspecialchars($violation['date']) ?></td>
                    <td><?= htmlspecialchars($violation['car_number']) ?></td>
                    <td><?= htmlspecialchars($violation['description']) ?></td>
                    <td><?= htmlspecialchars($violation['status']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>