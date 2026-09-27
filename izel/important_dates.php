<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['user_id'])){
    header('Location: login.php' );
    exit;
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $title = trim($_POST['title']);
    $event_date = $_POST['event_date'];
    $is_recurring = $_POST['is_recurring'] ? 1 : 0;

     if ($title === '') {
        $errors[] = 'Podaj nazwę wydarzenia.';
    }
     if ($event_date === '') {
        $errors[] = 'Podaj datę.';
    }
        if(empty($errors)){
            $stmt = $pdo->prepare('INSERT INTO important_dates (couple_id, author_id, title, event_date, is_recurring) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$_SESSION['couple_id'], $_SESSION['user_id'], $title, $event_date, $is_recurring]);
            header('Location: important_dates.php');
            exit;
        }
}
$stmt = $pdo->prepare('SELECT * FROM important_dates WHERE couple_id = ?');
$stmt->execute([$_SESSION['couple_id']]);
$important_dates = $stmt->fetchAll();
$today = new DateTime('today');
foreach ($important_dates as &$date){

    $event = new DateTime($date['event_date']);

    if($date['is_recurring']){
        $event->setDate($today->format('Y'), $event->format('m'), $event->format('d'));
        if($event < $today){
            $event->modify('+1 year');
        }
    }
    $diff = $today->diff($event);
    $date['days_left'] = $diff->days;
    $date['is_future'] = $event >= $today;

}
unset($date);
?>
<!DOCTYPE html>
    <head>
        <html lang="pl">
        <meta charset="UTF-8">
        <title>Odliczania</title>
        <link rel="stylesheet" href="style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="beige-gradient">
    <header class="mood-hero">Wasze odliczania</header>
    <div class="date-auth">
        <form method="POST" action="important_dates.php" class="form-grid">
            <label class="date-label"><h3>Nazwa wydarzenia:</h3></label><input type="text" name="title" class="date-input">
            <label class="date-label"><h3>Data wydarzenia:</h3></label><input type="date" name="event_date" class="date-input">
            <label class="date-label"><h3>Czy się powtarza?</h3></label><input type="checkbox" name="is_reccuring" class="date-input is-reccuring"><br>
            <button type="submit" class=" dates-submit-button">Dodaj</button><br>
</form>
<div class="date-wrapper">
<?php foreach($important_dates AS &$date): ?>
    <div class="date-card">
        <h1 class="date-title"><?=htmlspecialchars($date['title']) ?></h1>
        <p>
            <?php if($date['is_future']): ?>
                Za <?= $date['days_left'] ?> dni
                <?php else: ?>
                <?= $date['days_left'] ?> dni temu
               <?php endif; ?>
                </p>
                </div>
                <?php endforeach;?> 
                </div>