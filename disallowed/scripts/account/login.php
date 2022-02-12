<?php
# Paul
$xml->addChild("title", "Einloggen | BD");

if ($_POST) {
    if (login($_POST["email"], $_POST["password"])) {
        header("Location: /");
    } else {
        foreach ($_POST as $key => $value) {
            $xml->addChild($key, $value);
        }
        $xml->addChild("message", "Die eingegebenen Daten sind ungültig!");
    }
}
