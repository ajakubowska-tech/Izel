<?php 
session_start();
require_once 'config.php';
require_once 'moods.php';
if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}
$note_date = date('Y-m-d');
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    
    $content = trim($_POST['content'] ?? '');
    $mood = trim($_POST['mood'] ?? '');

    $errors = [];
    if ($content === ''){
        $errors[] = 'Notatka nie może byc pusta.';
    }
    if (!array_key_exists($mood, $moods)) {
    $errors[] = 'Nieprawidłowy nastrój.';
        }

    if (empty($errors)) {
    $stmt = $pdo->prepare(
        'INSERT INTO daily_notes (author_id, couple_id, note_date, content, mood)
         VALUES (?, ?, ?, ?, ?)
         ON DUPLICATE KEY UPDATE content = VALUES(content), mood = VALUES(mood)'
    );
    $stmt->execute([$_SESSION['user_id'], $_SESSION['couple_id'], $note_date, $content, $mood]);
    header('Location: daily_notes.php');
    exit;
}
}
    $stmt = $pdo->prepare(
         'SELECT daily_notes.*, users.id AS user_id
     FROM daily_notes
     JOIN users ON daily_notes.author_id = users.id
     WHERE users.couple_id = ? AND daily_notes.note_date = ?'
    );
    $stmt->execute([$_SESSION['couple_id'], $note_date]);
    $notes = $stmt->fetchAll();

    $my_note = null;
$partner_note = null;

foreach ($notes as $note) {
    if ($note['user_id'] == $_SESSION['user_id']) {
        $my_note = $note;
    } else {
        $partner_note = $note;
    }
}

if ($my_note && $partner_note) {
    $state = 'both_done';
} elseif ($my_note) {
    $state = 'waiting_for_partner';
} else {
    $state = 'not_written';
}
?>
<!DOCTYPE html>
    <head>
        <html lang="pl">
        <meta charset="UTF-8">
        <title>Daily check-up</title>
        <link rel="stylesheet" href="style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="upper-light2">
<?php if($state === 'not_written'): ?>
    <form action="daily_notes.php" method="POST">
        <div class="form-group">
            <label class="bucket-header"><h1>Jak się czujesz?</h1></label><br></div>
        <div class="mood-card"><br>
                <div class="mood-picker">
                    <?php foreach ($moods as $key=> $icon_path): ?>
                        <label class="mood-option">
                            <input type="radio" name="mood" value="<?= $key ?>" required>
                            <span class="mood-icon"><?php include $icon_path; ?></span>
                    </label>
                    <?php endforeach; ?>
                    </div>
                <label class="mood-label"><h3>Co sprawiło że się tak czujesz?</h3></label>
                <input type="text" name="content" autocomplete="off" class="mood-input"><br><br>
                <button type="submit" class="submit-button"><label><h3>Wyślij</h3><label></button>
                    </div>
        
        <?php elseif ($state === 'waiting_for_partner'): ?>
            <div class="mood-card"><br>
              <label class="mood-label">Wysłano, czekam na Partnera...</label>
        </div>
        <?php else: ?>
            <div class="notes-reveal">
                <div class="note-card">
                    <span  class="note-mood">
                        <?php include $moods[$my_note['mood']]; ?>
                    </span>
                <p><?= htmlspecialchars($my_note['content']); ?></p>
        </div>
        <div class="note-card">
            <?php include $mood[$partner_note['mood']]; ?>
        </span>
        <p><?= htmlspecialchars($partner_note['content']) ?></p>
        </div>
        </div>
        </div>
        <?php endif; ?>