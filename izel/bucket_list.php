<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['user_id'])){
    header('Location: login.php');
    exit;
}
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title']??'');
        $category = $_POST['category'] ??'';

         if($title !== ''){
            $stmt = $pdo->prepare('INSERT INTO bucket_list (couple_id, author_id, title, category) VALUES (?, ?, ?, ?)');
            $stmt->execute([$_SESSION['couple_id'], $_SESSION['user_id'], $title , $category]);
            header('Location: bucket_list.php');
            exit;         }
    }
$stmt = $pdo->prepare('SELECT * FROM `bucket_list` WHERE `couple_id`=?');
$stmt->execute([$_SESSION['couple_id']] );
$bucket_items = $stmt->fetchAll();
?>
<!DOCTYPE html>
    <head>
        <html lang="pl">
        <meta charset="UTF-8">
        <title>Bucket Listy</title>
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
    <div class="auth-card">
        <h1 class="login-header">Dodaj punkt listy</h1>
        <form method="POST" action="bucket_list.php">
           <div class="form-group">
            <label><h2>Tytuł</h2></label>
                <input type="text" name="title" class="bucket-form">
        </div>
        <div class="form-group">
            <label><h2>Kategoria</h2></label>
            <select class="input" name="category">
                <option value="podroze">Podróże</option>
                <option value="w-domu">W domu</option>
                <option value="aktywnosci">Aktywności</option>
                <option value="randki">Randki</option>
                <option value="jedzonko">Jedzonko</option>
                <option value="marzenia">Marzenia</option>
                <option value="inne">Inne</option>
</select><br>
<button type="submit" class="submit-button"><label><h2>Dodaj</h2><label></button><BR><BR><BR>
</div>
        <ul class="bucket-list-card">
            <h1 class="bucket-header">Do zrobienia:</h1>
            <div class="category-filters">
            <button type="button" data-category="wszystkie" class="category-button">Wszystkie</button>
            <button type="button" data-category="podroze" class="category-button">Podróże</button>
            <button type="button" data-category="w-domu" class="category-button">W domu</button>
            <button type="button" data-category="aktywnosci" class="category-button">Aktywności</button>
            <button type="button" data-category="randki" class="category-button">Randki</button>
            <button type="button" data-category="jedzonko" class="category-button">Jedzonko</button>
            <button type="button" data-category="marzenia" class="category-button">Marzenia</button>
            <button type="button" data-category="inne" class="category-button">Inne</button>
        </div><br>
            <?php foreach ($bucket_items as $item):?>
                    <li class="bucket-item <?= $item['is_completed'] ? 'completed' : '' ?>" data-id="<?= $item['id'] ?>" data-category="<?= htmlspecialchars($item['category']) ?>">
        <input type="checkbox" class="bucket-checkbox" <?= $item['is_completed'] ? 'checked' : '' ?>>
        <?= htmlspecialchars($item['title']) ?>
                </li>
           <?php endforeach; ?>
        </ul>
    </div>
    <script>
   document.querySelectorAll('.bucket-checkbox').forEach(function(checkbox) {

    checkbox.addEventListener('change', function() {

        const li = checkbox.closest('.bucket-item');
        const itemId = li.dataset.id;

        fetch('toggle_bucket_item.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: 'id=' + itemId
        })
        .then(response => response.text())
        .then(result => {
            li.classList.toggle('completed');
        });

    });

});

document.querySelectorAll('.category-filters button').forEach(function(button) {
    button.addEventListener('click', function() {
       const selected = button.dataset.category;
    
        document.querySelectorAll('.bucket-item').forEach(function(item) {
    console.log('kategoria punktu:', item.dataset.category, '| wybrany filtr:', selected);

        if (selected === 'wszystkie' || item.dataset.category === selected){
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
       });
    });
});

</script>
</body>