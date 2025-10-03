<?php

session_start();
// Загружаем конфиг
$config = require __DIR__ . '/config.php';
$ADMIN_USER = $config['admin_user'];
$ADMIN_PASS = $config['admin_pass'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'] ?? '';
    $pass = $_POST['password'] ?? '';
    // Проверка логина и пароля
    if ($user === $ADMIN_USER && $pass === $ADMIN_PASS) {
        $_SESSION['is_admin'] = true;
        header("Location: /backend/admin.html");
        exit;
    } else {
        $error = "Неверный логин или пароль";
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <title>Вход в админку</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container py-4">
  <h1>Авторизация администратора</h1>
  <?php if (!empty($error)): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>
  <form method="post" class="mt-4">
    <div class="mb-3">
      <label class="form-label">Логин</label>
      <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Пароль</label>
      <input type="password" name="password" class="form-control" required>
    </div>
    <button class="btn btn-primary">Войти</button>
  </form>
</body>

</html>
