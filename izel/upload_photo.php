<?php
session_start();
require_once 'config.php';

if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if(isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK ) {
    $allowed_types =['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($_FILES['photo']['type'], $allowed_types)){
        die('Niedozwolony typ pliku');
    }
    $extension = pathinfo($_FILES['photo']['name'],PATHINFO_EXTENSION);
    $new_filename = $_SESSION['couple_id'] . '_' . time() . '.' . $extension;
    $destination = 'uploads/' . $new_filename;

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
        $stmt = $pdo->prepare('UPDATE couples SET background_photo = ? WHERE id = ?');
        $stmt->execute([$destination, $_SESSION['couple_id']]);
    }

    header('Location: index.php');
    exit;

}else{
    header('Location: index.php');
    exit;
}
