
<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

function jwt_generate_refresh_token($user_id, $remember = false) {
    $issuedAt = time();
    $expiration = $remember ? $issuedAt + (30 * DAY_IN_SECONDS) : $issuedAt + WEEK_IN_SECONDS;

    $payload = [
        'sub' => $user_id,
        'iat' => $issuedAt,
        'exp' => $expiration,
        'type' => 'refresh'
    ];

    return JWT::encode($payload, jwt_get_secret(), 'HS256');
}

function jwt_validate_refresh_token($token) {
    try {
        $decoded = JWT::decode($token, new Key(jwt_get_secret(), 'HS256'));
        if ($decoded->type !== 'refresh') {
            return new WP_Error('invalid_token', 'Token incorrect', ['status' => 403]);
        }
        return $decoded;
    } catch (Exception $e) {
        return new WP_Error('invalid_token', $e->getMessage(), ['status' => 403]);
    }
}
