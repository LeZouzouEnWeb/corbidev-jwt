
<?php

function jwt_generate_csrf_token() {
    return bin2hex(random_bytes(32));
}
