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
            load_f($xml);
            break;
        case "b":
            load_b($xml);
            break;
        case "r":
            load_r($xml);
            break;
        case "l":
            load_l($xml);
            break;
        default:
            $xml->addChild("title", "Keine Seite gefunden | BD");
            break;
    }
}

function load_f($xml) {
    $xml->addChild("title", "Fahrzeuge bearbeiten | BD");

    if (isset($_GET["create"])) {
        $_GET["id"] = Train::create(1);

        header("Location: /moderation/overview?view=f&id=".$_GET["id"]);
        return;
    }

    $trains = Train::get_trains();

    foreach ($trains as $train) {
        $xmltrain = $xml->addChild("train");
        $xmltrain->addChild("id", $train->number);
        $xmltrain->addChild("seats", $train->seats);
    }

    if (isset($_GET["id"])) {
        $train = Train::by_id($_GET["id"]);

        if (isset($train)) {
            // Save seats to DB
            if (isset($_POST["seats"]) && is_numeric($_POST["seats"])) {
                $train->seats = $_POST["seats"];
                $train->save();
            }

            if (isset($_GET["delete"])) {
                $train->delete();
                header("Location: /moderation/overview?view=f");
            }

            $xml->addChild("id", $_GET["id"]);
            $selection = $xml->addChild("selection");
            $selection->addChild("id", $train->number);
            $selection->addChild("seats", $train->seats);
        }
    }
}
function load_b($xml) {
    $xml->addChild("title", "Bahnhöfe bearbeiten | BD");

    if (isset($_POST["newshort"]) and !empty($_POST["newshort"])) {
        $_POST["newshort"] = strtoupper($_POST["newshort"]);

        $tocheck = Station::by_id($_POST["newshort"]);
        if (isset($tocheck)) {
            // Bahnhofkürzel existiert schon
            header("Location: /moderation/overview?view=b");
        }

        Station::create($_POST["newshort"], "Bitte sofort ändern");

        header("Location: /moderation/overview?view=b&id=".$_POST["newshort"]);
        return;
    }

    $stations = Station::get_stations();

    foreach ($stations as $station) {
        $xmlstation = $xml->addChild("station");
        $xmlstation->addChild("id", $station->short);
        $xmlstation->addChild("name", $station->name);
        $xmlstation->addChild("platforms", $station->platforms);
    }

    if (isset($_GET["id"])) {
        $station = Station::by_id($_GET["id"]);

        if (isset($station)) {
            if (isset($_POST["name"], $_POST["platforms"]) && is_numeric($_POST["platforms"])) {
                $station->name = $_POST["name"];
                $station->platforms = $_POST["platforms"];
                $station->save();
            }

            if (isset($_GET["delete"])) {
                $station->delete();
                header("Location: /moderation/overview?view=b");
            }

            $xml->addChild("id", $_GET["id"]);
            $selection = $xml->addChild("selection");
            $selection->addChild("id", $station->short);
            $selection->addChild("name", $station->name);
            $selection->addChild("platforms", $station->platforms);
        }
    }
}
function load_r($xml) {
    $xml->addChild("title", "Routen bearbeiten | BD");
}
function load_l($xml) {
    $xml->addChild("title", "Linien bearbeiten | BD");
}
