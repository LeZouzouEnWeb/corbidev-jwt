
<?php

function jwt_is_valid_user_request() {
    $token = jwt_get_token_from_header();
    $decoded = jwt_decode_token($token);
    return !is_wp_error($decoded);
}

function jwt_has_role($decoded, $role) {
    if (!isset($decoded->role)) return false;
    $roles = explode(',', $decoded->role);
    return in_array($role, $roles);
}
