
<?php

require_once __DIR__ . '/../jwt-helper.php';
require_once __DIR__ . '/../jwt-refresh-helper.php';
require_once __DIR__ . '/../csrf-helper.php';
require_once __DIR__ . '/../logger.php';

add_action('rest_api_init', function () {
    register_rest_route('jwt-auth/v1', '/login', [
        'methods' => 'POST',
        'callback' => 'jwt_auth_login',
        'permission_callback' => '__return_true'
    ]);

    register_rest_route('jwt-auth/v1', '/refresh', [
        'methods' => 'POST',
        'callback' => 'jwt_refresh_token',
        'permission_callback' => '__return_true',
    ]);
});

function jwt_auth_login($request) {
    $params = $request->get_json_params();
    $user = wp_authenticate($params['username'], $params['password']);

    if (is_wp_error($user)) {
        jwt_log('Échec de connexion', ['username' => $params['username']]);
        return new WP_Error('unauthorized', 'Identifiants invalides', ['status' => 403]);
    }

    jwt_log('Connexion réussie', ['user_id' => $user->ID]);
    return [
        'token' => jwt_generate_token($user->ID),
        'csrf' => jwt_generate_csrf_token(),
        'refresh_token' => jwt_generate_refresh_token($user->ID, !empty($params['remember_me'])),
        'user_id' => $user->ID
    ];
}

function jwt_refresh_token($request) {
    $params = $request->get_json_params();
    $refresh_token = $params['refresh_token'] ?? null;

    if (!$refresh_token) {
        return new WP_Error('missing_token', 'Refresh token manquant', ['status' => 403]);
    }

    $decoded = jwt_validate_refresh_token($refresh_token);

    if (is_wp_error($decoded)) {
        return $decoded;
    }

    $user_id = $decoded->sub ?? null;
    if (!$user_id) {
        return new WP_Error('invalid_token', 'Token invalide', ['status' => 403]);
    }

    jwt_log('Refresh token accepté', ['user_id' => $user_id]);

    return [
        'token' => jwt_generate_token($user_id),
        'csrf' => jwt_generate_csrf_token(),
        'refresh_token' => jwt_generate_refresh_token($user_id, true)
    ];
}
