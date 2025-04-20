
<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function jwt_get_secret() {
    return defined('JWT_AUTH_SECRET_KEY') ? JWT_AUTH_SECRET_KEY : 'changeme';
}

function jwt_generate_token($user_id) {
    $issuedAt = time();
    $expiration = $issuedAt + HOUR_IN_SECONDS;

    $user = get_user_by('id', $user_id);

    $payload = [
        'sub' => $user_id,
        'iat' => $issuedAt,
        'exp' => $expiration,
        'role' => implode(',', $user->roles)
    ];

    return JWT::encode($payload, jwt_get_secret(), 'HS256');
}

function jwt_decode_token($token) {
    try {
        return JWT::decode($token, new Key(jwt_get_secret(), 'HS256'));
    } catch (Exception $e) {
        return new WP_Error('invalid_token', $e->getMessage(), ['status' => 403]);
    }
}

function jwt_get_token_from_header() {
    $auth = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
    if (preg_match('/Bearer\s(.*)/', $auth, $matches)) {
        return $matches[1];
    }
    return null;
}
