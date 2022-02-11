<?php
# Paul

if ($_POST) {
    $reload = false;
    $deleteaccount = $xml->addChild("deleteaccount");

    if (!is_loggedin()) {
        $reload = true;
        $deleteaccount->addChild("message", "Aus irgendeinem Grund bist du nicht angemeldet!");

    } else if (!User::verify_password($_SESSION["email"], $_POST["password"])) {
        $reload = true;
        $deleteaccount->addChild("message", "Das Passwort ist falsch!");

    }

    if ($reload) {
        foreach ($_POST as $key => $value) {
            $changepassword->addChild($key, $value);
        }
        return;
    } else {
        $email = $_SESSION["email"];
        logout();
        User::delete_account($email);

        header("Location: /");
    }
}
