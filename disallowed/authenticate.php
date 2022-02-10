<?php
# Owner: Paul

require_once "disallowed/backend/user.php";

function login(string $email, string $clearpassword) {
    if (is_loggedin()) {
        return false;
    }

    if (User::verify_password($email, $clearpassword)) {
        $user = new User($email);

        $user->fetch();

        $_SESSION['email'] = $email;

        return true;
    }

    return false;
}

function logout() {
    setcookie(session_name(), '');
    $_SESSION = array();
    session_unset();

    session_destroy();
    session_start();

    return "logged out";
}

function is_loggedin() {
    return $_SESSION && isset($_SESSION['email']);
}
