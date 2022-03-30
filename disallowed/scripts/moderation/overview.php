<?php
# Paul

require_once "disallowed/backend/database_wrappers/train.php";
require_once "disallowed/backend/database_wrappers/station.php";
require_once "disallowed/backend/database_wrappers/route.php";
require_once "disallowed/backend/database_wrappers/line.php";
require_once "disallowed/backend/database_wrappers/connection.php";

if (isset($_GET["view"])) {
    $xml->addChild("view", $_GET["view"]);

    switch ($_GET["view"]) {
        case "f":
            require_once "disallowed/scripts/moderation/trains.php";
            load($xml);
            break;
        case "b":
            require_once "disallowed/scripts/moderation/stations.php";
            load($xml);
            break;
        case "r":
            require_once "disallowed/scripts/moderation/routes.php";
            load($xml);
            break;
        case "l":
            require_once "disallowed/scripts/moderation/lines.php";
            load($xml);
            break;
        default:
            $xml->addChild("title", "Keine Seite gefunden | BD");
            break;
    }
}
