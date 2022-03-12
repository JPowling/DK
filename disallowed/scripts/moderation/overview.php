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
            if (isset($_POST["fullname"], $_POST["platforms"]) && is_numeric($_POST["platforms"])) {
                $station->name = $_POST["fullname"];
                $station->platforms = $_POST["platforms"];
                $station->save();
            }

            $count = 1;
            while (isset($_POST["connection-$count"], $_POST["duration-$count"], $_POST["duration_rev-$count"])) {
                $connected = $_POST["connection-$count"];
                $duration = $_POST["duration-$count"];
                $duration_rev = $_POST["duration_rev-$count"];
                $delete = isset($_POST["delete-$count"]);

                $sql = new SQL(true);

                if ($delete) {
                    $sql->sql_request("DELETE FROM Verbindungen WHERE BahnhofA='$station->short' AND BahnhofB='$connected'");
                    $sql->sql_request("DELETE FROM Verbindungen WHERE BahnhofB='$station->short' AND BahnhofA='$connected'");
                } else {
                    print_r($_POST);
                    $sql->sql_request("UPDATE Verbindungen SET Dauer=$duration WHERE BahnhofA='$station->short' AND BahnhofB='$connected'");
                    $sql->sql_request("UPDATE Verbindungen SET Dauer=$duration_rev WHERE BahnhofB='$station->short' AND BahnhofA='$connected'");
                }

                $count++;
            }

            if (isset($_POST["new-connection"]) && !empty($_POST["new-connection"])) {
                $new_connectionstation = Station::by_id($_POST["new-connection"]);

                if (isset($new_connectionstation)) {
                    $sql = new SQL(true);
                    $sql->sql_request("INSERT INTO Verbindungen VALUES ('$station->short', '$new_connectionstation->short', 1)");
                    $sql->sql_request("INSERT INTO Verbindungen VALUES ('$new_connectionstation->short', '$station->short', 1)");
                }
            }

            if (isset($_GET["delete"])) {
                $station->delete();
                header("Location: /moderation/overview?view=b");
            }

            $xml->addChild("id", $_GET["id"]);
            $selection = $xml->addChild("selection");
            $selection->addChild("id", $station->short);
            $selection->addChild("fullname", $station->name);
            $selection->addChild("platforms", $station->platforms);

            $connections = $station->get_connections();

            foreach($connections as $connection) {
                $connectionxml = $selection->addChild("connection");
                $connected_station = Station::by_id($connection->connections["b"]);
                $connectionxml->addChild("other", $connected_station->name);
                $connectionxml->addChild("other_short", $connected_station->short);
                $connectionxml->addChild("duration", $connection->duration);
                $connectionxml->addChild("duration_rev", $connection->duration_rev);
            }
        }
    }
}
function load_r($xml) {
    $xml->addChild("title", "Routen bearbeiten | BD");
}
function load_l($xml) {
    $xml->addChild("title", "Linien bearbeiten | BD");
}
