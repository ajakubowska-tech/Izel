
<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['user_id'])){
    die('Brak dostępu');
}
$item_id = $_POST['id'] ?? null;

if($item_id === null) {
    die('Brak id');
}
$stmt = $pdo->prepare('SELECT is_completed FROM bucket_list WHERE id = ? AND couple_id = ?');
$stmt->execute([$item_id, $_SESSION['couple_id']]);
$item = $stmt->fetch();

if(!$item) {
    die('Nie znaleziono punktu');
}

if($item['is_completed']) {
    $stmt = $pdo->prepare('UPDATE bucket_list SET is_completed = 0, completed_by = NULL, completed_at = NULL WHERE id = ?');
    $stmt->execute([$item_id]);
    echo 'uncompleted';
} else {
    $stmt = $pdo->prepare('UPDATE bucket_list SET is_completed = 1, completed_by = ?, completed_at = NOW() WHERE id = ?');
    $stmt->execute([$_SESSION['user_id'], $item_id]);
    echo 'completed';
}
?>