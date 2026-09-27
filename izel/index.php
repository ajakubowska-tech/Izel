<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
    }

 $stmt = $pdo->prepare('SELECT relationship_start_date,background_photo  FROM couples WHERE id = ?');
 $stmt->execute([$_SESSION['couple_id']]);
 $couple = $stmt->fetch();
 $start_date = $couple['relationship_start_date'];
 $background_photo = $couple['background_photo'];
?>
<!DOCTYPE html>
    <head>
        <html lang="pl">
        <meta charset="UTF-8">
        <title>Strona Główna</title>
        <link rel="stylesheet" href="style.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400..700;1,400..700&display=swap" rel="stylesheet">
       <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@200..800&display=swap" rel="stylesheet">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="central-light">
    <div class="time-hero" id="time-hero" style="background-image: url('<?= htmlspecialchars($background_photo ?? '')?>')"> 
        <div class="together">
        <p class="together-label">Razem od..</p>
        <div id="counter" data-start="<?= htmlspecialchars($start_date)?>"></div>
        </div>
        <form method="POST" action="upload_photo.php" enctype="multipart/form-data" class="photo-upload-form">
            <label for="photo-input" class="photo-upload-label"><img src="icons/photo-change.svg" width="28px" height="28px"></label>
            <input type="file" name="photo" id="photo-input" accept="image/*" onchange="this.form.submit()" class="photo-upload-input">
        </form>   
    </div>

    <div class="widgets">
    <a href="bucket_list.php" class="widgetel">Bucket list(y)</a>
    <a href="daily_notes.php" class="widgetel">Dzienny check-up</a>
    <a href="important_dates.php" class="widgetel">Odliczanie</a>
</div>

<script>
    const counterEl = document.getElementById('counter');
    const startDate = new  Date(counterEl.dataset.start);

    function updateCounter(){
        const now = new Date();
        let diffMs = now - startDate;

        const days = Math.floor(diffMs / (1000 * 60 *  60 * 24));
        diffMs -= days * (1000 * 60 *  60 * 24)

        const hours = Math.floor(diffMs / (1000 * 60 * 60));
        diffMs -= hours * (1000 * 60 * 60);

        const minutes = Math.floor(diffMs / (1000 * 60));

        counterEl.textContent =    `${days} dni, ${hours} godzin, i ${minutes} minut `;

    }

    updateCounter();
    setInterval(updateCounter, 60000);
    </script>
    </body>