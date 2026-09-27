<?php
session_start();
require_once 'config.php';
require_once 'auth_help.php';

if(isset($_SESSION['user_id'])){
    header('Location: index.php');
    exit;
}
$errors = [];
$email = '';

if($_SERVER['REQUEST_METHOD'] === 'POST') {

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$remember_me = isset($_POST['remember_me']);

    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    $errors[] = 'Nieprawidłowy e-mail lub hasło.';
}

    if(empty($errors)){

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['couple_id'] = $user['couple_id'];

    if ($remember_me) {
        create_remember_token($pdo, $user['id']);

    }

    header('Location: index.php');
    exit;
    }
}
?>
<!DOCTYPE html>
    <head>
        <html lang="pl">
        <meta charset="UTF-8">
        <title>Logowanie</title>
        <link rel="stylesheet" href="style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<form method="POST" action="login.php" class="auth-card login-layout">
    
  <h1 class="login-header">Zaloguj się</h1>

<div class="form-group-log form-group">
    <label><h3>E-mail</h3></label>
    <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
</div>
<div class="form-group-log form-group">
    <label><h3>Hasło</h3></label>
    <input type="password" name="password">
</div>
<div class="form-group-log form-group">
    <input type="checkbox" name="remember_me" id="remember-me">
    <label for="remember-me"><p class="check-item">Zapamiętaj mnie</li></label>
</div>
<button type="submit" class="submit-button"><label><h3>Zaloguj się</h3><label></button>
</form>