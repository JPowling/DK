<?php

// Jens

$xml->addChild("title", "mainpage");

if (isset($_GET["site"])) {
    $xml->addChild("site", $_GET["site"]);
    if (file_exists("disallowed/scripts/mainpage/" . $_GET["site"] . ".php")) {
        require "disallowed/scripts/mainpage/" . $_GET["site"] . ".php";
    }
}
