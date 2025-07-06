<?php
function generateToken($length = 64)
{
    return bin2hex(random_bytes($length / 2));
}

function storeRememberMeToken($koneksi, $userId)
{
    $selector = bin2hex(random_bytes(6));
    $validator = bin2hex(random_bytes(32));
    $validatorHash = hash('sha256', $validator);

    $expiresAt = date('Y-m-d H:i:s', time() + (86400 * 30)); // 30 hari

    // Simpan ke database
    $stmt = $koneksi->prepare("INSERT INTO tb_remember_tokens (user_id, selector, validator_hash, expires_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $userId, $selector, $validatorHash, $expiresAt);
    $stmt->execute();
    $stmt->close();

    // Simpan ke cookie
    setcookie(
        'remember_me',
        base64_encode($selector . ':' . $validator),
        time() + (86400 * 30),
        "/",
        "",
        false,
        true
    );
}

function verifyRememberMe($koneksi)
{
    if (!isset($_COOKIE['remember_me']))
        return false;

    list($selector, $validator) = explode(':', base64_decode($_COOKIE['remember_me']));
    $stmt = $koneksi->prepare("SELECT user_id, validator_hash, expires_at FROM tb_remember_tokens WHERE selector = ? LIMIT 1");
    $stmt->bind_param("s", $selector);
    $stmt->execute();
    $result = $stmt->get_result();
    $token = $result->fetch_assoc();
    $stmt->close();

    if (!$token)
        return false;

    if (hash_equals($token['validator_hash'], hash('sha256', $validator))) {
        if (strtotime($token['expires_at']) >= time()) {
            return $token['user_id'];
        }
    }
    return false;
}
