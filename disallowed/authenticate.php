<?php

require "model/user.php";

function login(string $email, string $clearpassword) {
    if (is_loggedin()) {
        return "already logged in";
    }

    if (User::verify_password($email, $clearpassword)) {
        $user = new User($email);

        $_SESSION['loggedin'] = TRUE;
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
    $_SESSION = array();
    session_unset();

    session_destroy();
    session_start();

    return "logged out";
}

function create_account($forename, $surname, $email, $phone, $clearpassword, $residence, $postal, $street, $house) {
    $user = new User($email, false);

    $user->forename = $forename;
    $user->surname = $surname;
    $user->phone = $phone;
    $user->forename = $forename;
    $user->residence = $residence;
    $user->postal = $postal;
    $user->street = $street;
    $user->house = $house;

    $user->store_data(password_hash($clearpassword, PASSWORD_DEFAULT));
}

function is_loggedin() {
    return isset($_SESSION['loggedin'], $_SESSION['user']);
}
