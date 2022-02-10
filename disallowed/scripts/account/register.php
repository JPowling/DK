<?php
# Paul

if ($_POST) {
    $reload = false;
    $register = $xml->addChild("register");

    if ($_POST["email"] !== $_POST["email2"]) {
        $reload = true;
        $register->addChild("message", "E-Mail Adressen stimmen nicht überein!");
    } else if (User::email_exists($_POST["email"])) {
        $reload = true;
        $register->addChild("message", "Die E-Mail ist bereits vergeben!");
    } else if ($_POST["password"] !== $_POST["password2"]) {
        $reload = true;
        $register->addChild("message", "Passwörter stimmen nicht überein!");
    }

    if ($reload) {
        foreach ($_POST as $key => $value) {
            $register->addChild($key, $value);
        }
    } else {
        // Registrierung erfolgreich
        if (is_loggedin()) {
            logout();
        }

        User::create_account($_POST["email"], $_POST["password"]);
        login($_POST["email"], $_POST["password"]);

        if (is_loggedin()) {
            $user = new User($_SESSION["email"], false);

            $user->forename = strval($_POST["forename"]);
            $user->surname = strval($_POST["surname"]);
            $user->phone = strval($_POST["phone"]);
            $user->residence = strval($_POST["residence"]);
            $user->postal = strval($_POST["postal"]);
            $user->street = strval($_POST["street"]);
            $user->house = strval($_POST["house"]);

            $user->store_data();
            header("Location: /");
        } else {
            // crash
        }
    }
}

#echo "This is php";
