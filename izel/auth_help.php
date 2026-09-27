<?php
function create_remember_token($pdo, $user_id) {
    $token = bin2hex(random_bytes(32));
    $token_hash = hash('sha256', $token);
    $expires_at = date('Y-m-d H:i:s', time() + 30 * 24 * 60 * 60);

    $stmt = $pdo->prepare(
        'INSERT INTO remember_tokens (user_id, token_hash, expires_at) VALUES (?, ?, ?)'
    );
    $stmt->execute([$user_id, $token_hash, $expires_at]);

    setcookie(
        'remember_token',
        $token,
        time() + 30 * 24 * 60 * 60,
        '/',
        '',
        false,
        true
    );

}