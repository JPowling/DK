<?php
# Paul

if ($_POST) {
    $reload = false;

    foreach ($_POST as $key => $value) {
        if (strlen($value) == 0) {
            $reload = true;
        }
    }

    // User input sanitization
    if ($reload) {
        $reload = true;
        $xml->addChild("message", "Es sind nicht alle Felder ausgefüllt!");
    }
    if (strlen($_POST["forename"]) > 30) {
        $reload = true;
        $xml->addChild("message", "Der Vorname ist zu lang!");
        $xml->addChild("forename_red", "red");
    }
    if (strlen($_POST["surname"]) > 30) {
        $reload = true;
        $xml->addChild("message", "Der Nachname ist zu lang!");
        $xml->addChild("surname_red", "red");
    }
    if (strlen($_POST["email"]) > 50) {
        $reload = true;
        $xml->addChild("message", "Die E-Mail Adresse ist zu lang!");
        $xml->addChild("email_red", "red");
    }
    if ($_POST["email"] !== $_POST["email2"]) {
        $reload = true;
        $xml->addChild("message", "Die E-Mail Adressen stimmen nicht überein!");
        $xml->addChild("email_red", "red");

    } else if (User::email_exists($_POST["email"])) {
        $reload = true;
        $xml->addChild("message", "Die E-Mail ist bereits vergeben!");
        $xml->addChild("email_red", "red");
    }
    if ($_POST["password"] !== $_POST["password2"]) {
        $reload = true;
        $xml->addChild("message", "Passwörter stimmen nicht überein!");
        $xml->addChild("password_red", "red");

    } else if (strlen($_POST["password"]) < 6 
            || !preg_match("/[\d]/", $_POST["password"])    // contains numbers
            || !preg_match("/[\w]/", $_POST["password"])) { // contains letters 
            
        $reload = true;
        $xml->addChild("message", "Das Passwort muss mindestens einen Buchstaben, eine Zahl und 6 Zeichen Lang sein!");
        $xml->addChild("password_red", "red");
    }
    if (strlen($_POST["phone"]) > 30 || !is_numeric($_POST["phone"])) {
        $reload = true;
        $xml->addChild("message", "Die Telefonnummer ist ungültig!");
        $xml->addChild("phone_red", "red");
    }
    if (strlen($_POST["postal"]) !== 5 || !is_numeric($_POST["postal"])) {
        $reload = true;
        $xml->addChild("message", "Die Postleitzahl hat einen ungültigen Wert!");
        $xml->addChild("postal_red", "red");
    }
    if (strlen($_POST["residence"]) > 30) {
        $reload = true;
        $xml->addChild("message", "Der Wohnort hat einen ungültigen Wert!");
        $xml->addChild("residence_red", "red");
    }
    if (strlen($_POST["street"]) > 50) {
        $reload = true;
        $xml->addChild("message", "Die Straße hat einen ungültigen Wert!");
        $xml->addChild("street_red", "red");
    }
    if (strlen($_POST["house"]) > 30 || !is_numeric($_POST["house"])) {
        $reload = true;
        $xml->addChild("message", "Die Hausnummer hat einen ungültigen Wert!");
        $xml->addChild("house_red", "red");
    }

    if ($reload) {
        foreach ($_POST as $key => $value) {
            $xml->addChild($key, $value);
        }
    } else {
        // Valid data, create accound and login

        if (is_loggedin()) {
            logout();
        }

        User::create_account($_POST["email"], $_POST["password"]);
        login($_POST["email"], $_POST["password"]);

        if (is_loggedin()) {
            $user = new User($_SESSION["email"]);

            $user->forename = strval($_POST["forename"]);
            $user->surname = strval($_POST["surname"]);
            $user->phone = strval($_POST["phone"]);
            $user->residence = strval($_POST["residence"]);
            $user->postal = strval($_POST["postal"]);
            $user->street = strval($_POST["street"]);
            $user->house = strval($_POST["house"]);

            $user->store_data();
            header("Location: /");
        }
    }
}
