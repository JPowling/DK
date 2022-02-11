<?php
# Paul
ensure_loggedin();

if ($_POST) {
    $reload = false;

    if (!is_loggedin()) { // double check
        $reload = true;
        $xml->addChild("message", "Aus irgendeinem Grund bist du nicht angemeldet!");

    } else if (!User::verify_password($_SESSION["email"], $_POST["password"])) {
        $reload = true;
        $xml->addChild("message", "Das Passwort ist falsch!");

    }

    if ($reload) {
        foreach ($_POST as $key => $value) {
            $xml->addChild($key, $value);
        }
        return;
    } else {
        $email = $_SESSION["email"];
        logout();
        User::delete_account($email);

        header("Location: /");
    }
}
