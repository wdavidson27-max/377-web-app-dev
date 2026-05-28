<?php

function start_app_session()
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
// codeX
    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}
