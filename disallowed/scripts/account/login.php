<?php

if ($_POST) {
    if (login($_POST["email"], $_POST["password"])) {
        header("Location: /");
    } else {
        $login = $xml->addChild("login");
        foreach ($_POST as $key => $value) {
            $login->addChild($key, $value);
        }
        $login->addChild("message", "Die eingegebenen Daten sind ungültig!");
    }
}
