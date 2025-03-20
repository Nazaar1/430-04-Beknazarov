<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $full_name = $_POST['full_name'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];

    $stmt = $db->prepare('INSERT INTO users (username, password, full_name, phone, email) VALUES (:username, :password, :full_name, :phone, :email)');
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':full_name', $full_name);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':email', $email);

    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Вы успешно зарегистрированы</div>";
    } else {
        echo "<div class='alert alert-danger'>Ошибка при регистрации</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center mb-4">Регистрация</h2>
                <form method="post" action="register.php">
                    <div class="mb-3">
                        <label for="username" class="form-label">Логин</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Пароль</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="full_name" class="form-label">ФИО</label>
                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="phone" class="form-label">Телефон</label>
                        <input type="text" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Зарегистрироваться</button>
                </form>
                <div class="mt-3 text-center">
                    <a href="login.php" class="btn btn-link">Уже есть аккаунт? Войдите</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>