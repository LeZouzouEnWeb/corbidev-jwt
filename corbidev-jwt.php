
<?php
/**
 * Plugin Name: Mon MU Plugin JWT Auth
 * Description: Authentification JWT + CSRF avec rôles et refresh token longue durée.
 */

require_once __DIR__ . '/mon-plugin-jwt/includes/jwt-helper.php';
require_once __DIR__ . '/mon-plugin-jwt/includes/csrf-helper.php';
require_once __DIR__ . '/mon-plugin-jwt/includes/logger.php';
require_once __DIR__ . '/mon-plugin-jwt/includes/security.php';
require_once __DIR__ . '/mon-plugin-jwt/includes/jwt-refresh-helper.php';
require_once __DIR__ . '/mon-plugin-jwt/includes/routes/auth-routes.php';
require_once __DIR__ . '/mon-plugin-jwt/includes/routes/protected-routes.php';
