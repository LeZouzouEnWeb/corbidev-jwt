
<?php

function jwt_log($message, $context = []) {
    $log_entry = sprintf(
        "[%s] %s %s\n",
        date('Y-m-d H:i:s'),
        $message,
        !empty($context) ? json_encode($context) : ''
    );

    error_log($log_entry, 3, WP_CONTENT_DIR . '/jwt-auth.log');
}
