<?php

// Jens


if (isset($_GET["site"])) {
    $xml->addChild("site", $_GET["site"]);

    if(file_exists($_GET["site"])){
        require $_GET["site"] . "php";
    }

}
