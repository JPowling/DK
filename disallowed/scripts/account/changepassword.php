<?php
# Paul
ensure_loggedin();

if ($_POST) {
    $reload = false;
    $changepassword = $xml->addChild("changepassword");

    if ($_POST["password"] !== $_POST["password2"]) {
        $reload = true;
        $changepassword->addChild("message", "Die Passwörter stimmen nicht überein!");

    } else if (!is_loggedin()) { // double check
        $reload = true;
        $changepassword->addChild("message", "Aus irgendeinem Grund bist du nicht angemeldet!");

    } else if (!User::verify_password($_SESSION["email"], $_POST["password_old"])) {
        $reload = true;
        $changepassword->addChild("message", "Das alte Passwort ist falsch!");

    } else if (strlen($_POST["password"]) < 6 
            || !preg_match("/[\d]/", $_POST["password"]) // contains numbers
            || !preg_match("/[\w]/", $_POST["password"]))// contains letters 
            {
        $reload = true;
        $changepassword->addChild("message", "Das neue Passwort ist zu schlecht!");
    }

    if ($reload) {
        foreach ($_POST as $key => $value) {
            $changepassword->addChild($key, $value);
        }
        return;
    } else {
        // SQL injection protection
        foreach ($columns as $key => $value){
            $value = str_replace("'", "\\'", $value);
            $columns[$key]  = str_replace("\"", "\\\"", $value);
        }

        $user = new User($_SESSION["email"]);
        $user->set_password($_POST["password"]);

        header("Location: /account/profile");
    }
}
