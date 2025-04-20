
<?php

require_once __DIR__ . '/../jwt-helper.php';
require_once __DIR__ . '/../security.php';

add_action('rest_api_init', function () {
    register_rest_route('jwt-auth/v1', '/me', [
        'methods' => 'GET',
        'callback' => function () {
            $token = jwt_get_token_from_header();
            $decoded = jwt_decode_token($token);
            if (is_wp_error($decoded)) return $decoded;

            return ['user_id' => $decoded->sub, 'role' => $decoded->role ?? ''];
        },
        'permission_callback' => 'jwt_is_valid_user_request'
    ]);
});
