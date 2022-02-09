<?php

require_once "disallowed/backend/user.php";

function login(string $email, string $clearpassword) {
    if (is_loggedin()) {
        return "already logged in";
    }

    if (User::verify_password($email, $clearpassword)) {
        $user = new User($email);

        $_SESSION['user'] = $user;

        return "logged in";
    }

    return "invalid credentials";
}

function logout(bool $force = false) {
    if (!is_loggedin() || $force) {
        return "not logged in";
    }

    setcookie(session_name(), '');
    session_reset();
    session_unset();

    session_destroy();
    session_start();

    return "logged out";
}

function is_loggedin() {
    return isset($_SESSION['user']);
}
