<?php
# Paul

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

function ensure_loggedin() {
    if (!is_loggedin()) {
        header("Location: /notloggedin");
    }
}

function ensure_moderator() {
    if (is_loggedin()) {
        $user = new User($_SESSION["email"]);

        $rank = $user->get_privileges();

        if ($rank !== "Moderator" or $rank !== "Admin") {
            header("Location: /");
        }
    }
}

function ensure_admin() {
    if (is_loggedin()) {
        $user = new User($_SESSION["email"]);

        $rank = $user->get_privileges();

        if ($rank != "Admin") {
            header("Location: /");
        }
    }
}
