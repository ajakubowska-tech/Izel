<?php 
include "config.php";
session_start();
require_once 'config.php';
require_once 'auth_help.php';

if(isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $mode = $_POST['mode'] ??'';
    $name = trim($_POST['name'] ??'');
    $email = trim($_POST['email'] ??'');
    $password = $_POST['password'] ??'';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $pairing_code = strtoupper(trim($_POST['pairing_code'] ??''));
    $start_date = $_POST['start_date'] ??'';

    if ($name === '') {
        $errors[] = 'Podaj imię.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Podaj poprawny email.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Hasło musi mieć minimum 6 znaków';
    }
    if ($password_confirm !== $password) {
        $errors[] = 'Hasła nie są takie same';
    }
    if ($pairing_code === '' || strlen($pairing_code) > 10) {
        $errors[] = 'Kod parowania musi mieć od  1 do 10 znaków.';
    }
    if (!in_array($mode, ['new' , 'join'])) {
        $errors[] = 'Wybierz sposób rejestracji.';
    }
    if ($mode === 'new' && $start_date === '') {
        $errors[] = 'Podaj datę rozpoczęcia związku.';
    }
    if (empty($errors)) {  
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
    $errors[] = "Ten adres e-mail jest juz zarejestrowany.";}}


    if (empty ($errors) && $mode === 'new'){
    $pairing_hash = hash('sha256' , $pairing_code);

    $stmt = $pdo->prepare('SELECT id FROM couples WHERE pairing_code_hash = ?');
    $stmt->execute([$pairing_hash]);
    if ($stmt->fetch()) {
    $errors[] = 'Ten kod parowania jest już zajęty, wybierzcie inny.'; 
    } 

    if (empty($errors)) {
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare(
            'INSERT INTO couples (pairing_code_hash, relationship_start_date) VALUES (?, ?)'
        );
        $stmt->execute([$pairing_hash, $start_date]);
        $couple_id = $pdo->lastInsertId();

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare(
            'INSERT INTO users (couple_id, name, email, password_hash) VALUES (?, ?, ?, ?)'
        );
        $stmt->execute([$couple_id ,$name ,$email , $password_hash]);
        $user_id = $pdo->lastInsertId();
        
        $pdo->commit();

        $_SESSION['user_id'] = $user_id;
        $_SESSION['couple_id'] = $couple_id;
        create_remember_token($pdo,$user_id);
        header('Location: index.php');
        exit;

}catch (Exeption $e) {
    $pdo->rollBack();
    $errors[] = 'Coś poszło nie tak :(';
}
}
if (empty($errors)&& $mode === 'join') {
    $pairing_hash = hash('sha256', $pairing_code);

    $stmt = $pdo->prepare('SELECT id FROM couples WHERE pairing_code_hash = ?');
    $couple = $stmt->fetch();

    if(!$couple){
        $errors[] = 'Nie znaleziono pary o takim kodzie';
        } else {
        $couple_id = $couple['id'];

        $stmt = $pdo->prepare('SELECT COUNT(*) AS ile FROM users WHERE couple_id = ?');
        $stmt->execute([$couple_id]);
        $count = $stmt->fetch()['ile'];

        if ($count >=2){
            $errors[] = 'Do tej pary sa już przypisane dwie osoby.';

        }
               }
        if(empty($errors)) {
            $password_hash = password_hash($password , PASSWORD_DEFAULT);
            $stmt = $pdo->prepare(
                `INSERT INTO users (couple_id, name, email, password_hash) VALUES (?, ?, ?, ?)`
            );
            stmt->execute([$couple_id, $name ,$email ,$password_hash  ]);
            $user_id = $pdo->lastInsertId();

            $_SESSION['user_id'] = $user_id;
            $_SESSION['couple_id'] = $couple_id;
            header('Location : index.php');
            exit;
        }
}
}
}
?>
<!DOCTYPE html>
    <head>
        <html lang="pl">
        <meta charset="UTF-8">
        <title>Rejestracja</title>
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

<!--pierwszy formularz na nowa pare-->
<form method="POST" action="register.php" class="auth-card">
<button type="button" id="mode-toggle" data-mode="new">
  <h1>Zakładam nową parę</h1>
</button>

<input type="hidden" name="mode" id="mode-input" value="<?= htmlspecialchars($mode ?? 'new') ?>">

<div class="form-group">
    <label><h3>Imię</h3></label>
    <input type="text" name="name" value="<?= htmlspecialchars($name ?? '') ?>">
</div>

<div class="form-group">
    <label><h3>E-mail</h3></label>
    <input type="email" name="email" value="<?= htmlspecialchars($email ?? '') ?>">
</div>

<div class="form-group">
    <label><h3>Hasło</h3></label>
    <input type="password" name="password">
</div>

<div class="form-group">
    <label><h3>Powtórz hasło</h3></label>
    <input type="password" name="password_confirm">
</div>

<div class="form-group">
    <label id='pairing-label'><h3>Kod parowania</h3></label>
    <input type="text" name="pairing_code" maxlength="10" value="<?= htmlspecialchars($pairing_code ?? '') ?>">
</div>

<div class="form-group only-new" id="start-date-group">
    <label><h3>Data rozpoczęcia związku</h3></label>
    <input type="date" name="start_date" value="<?= htmlspecialchars($start_date ?? '') ?>">
</div><br><br>
<button type="submit" class="submit-button"><label><h3>Zarejestruj się</h3><label></button>
 <?php if (!empty($errors)): ?>
    <div class="auth-errors">
        <h3><ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
            </ul>
            </h3>
            </div>
            <?php endif; ?>
 </form>

<script>
    const przycisk = document.getElementById('mode-toggle');
    const modeInput = document.getElementById('mode-input');
    const pairingLabel = document.getElementById('pairing-label');
    const startDateGroup = document.getElementById('start-date-group');


    przycisk.addEventListener('click', function(){

        if(przycisk.dataset.mode === 'new') {

        przycisk.dataset.mode = 'join';
        przycisk.innerHTML = '<h1>Dołączam do partnera</h1>';
        pairingLabel.innerHTML = '<h3>Wpisz kod od partnera</h3>';
        modeInput.value = 'join';
        startDateGroup.style.display  = 'none';
        } else {

        przycisk.dataset.mode = 'new';
        przycisk.innerHTML= '<h1>Zakładam nową parę</h1>';
        pairingLabel.innerHTML = '<h3>Wpisz kod parowania</h3>';
        modeInput.value = 'new';
        startDateGroup.style.display = 'block';
        }

    }


);
//zmiana ciemny/jasny motyw do zrobienia kolorystyka
const themeToggle = document.getElementById('theme-toggle');
themeToggle.addEventListener('click', function() {

    if (document.body.dataset.theme === 'dark') {
        document.body.dataset.theme = 'light';
    } else {
        document.body.dataset.theme = 'dark';
    }

});

</script>
            </body>