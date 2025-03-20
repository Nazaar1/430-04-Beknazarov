<?php
session_start();

// Подключение к базе данных
include 'includes/db.php';

// Проверка авторизации юзера
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
    // Если пользователь не админ, перенаправляем его в лк
    header('Location: dashboard.php');
    exit;
}

// Получение списка всех обращений
$stmt = $db->query('SELECT * FROM violations');
$violations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет администратора</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <!-- Кнопка выхода -->
        <div class="text-end mb-4">
            <a href="logout.php" class="btn btn-danger">Выйти</a>
        </div>

        <h1 class="text-center mb-4">Личный кабинет администратора</h1>

        <!-- Сообщения об успехе или ошибке -->
        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='alert alert-info'>" . $_SESSION['message'] . "</div>";
            unset($_SESSION['message']); // Удаляем сообщение после отображения
        }
        ?>

        <!-- Список всех обращений -->
        <h2 class="mb-3">Все обращения</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>Номер автомобиля</th>
                    <th>Описание</th>
                    <th>Статус</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($violations as $violation): ?>
                <tr>
                    <td><?= htmlspecialchars($violation['date']) ?></td>
                    <td><?= htmlspecialchars($violation['car_number']) ?></td>
                    <td><?= htmlspecialchars($violation['description']) ?></td>
                    <td><?= htmlspecialchars($violation['status']) ?></td>
                    <td>
                        <form method="post" action="update_status.php">
                            <input type="hidden" name="violation_id" value="<?= $violation['id'] ?>">
                            <select name="status" class="form-select">
                                <option value="в работе" <?= $violation['status'] === 'в работе' ? 'selected' : '' ?>>В работе</option>
                                <option value="выполнено" <?= $violation['status'] === 'выполнено' ? 'selected' : '' ?>>Выполнено</option>
                                <option value="отклонено" <?= $violation['status'] === 'отклонено' ? 'selected' : '' ?>>Отклонено</option>
                            </select>
                            <button type="submit" class="btn btn-primary mt-2">Сохранить</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>