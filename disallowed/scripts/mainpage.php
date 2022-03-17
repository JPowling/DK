<?php

// Jens

$xml->addChild("title", "Beutsche Dahn");

if (!isset($_GET['site'])) {
    $_GET["site"] = "linien";
}

$xml->addChild("site", $_GET["site"]);

if (file_exists("disallowed/scripts/mainpage/" . $_GET["site"] . ".php")) {
    require "disallowed/scripts/mainpage/" . $_GET["site"] . ".php";
}
