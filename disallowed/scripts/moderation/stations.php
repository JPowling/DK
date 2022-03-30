<?php
# Paul

function load($xml) {
    if (isset($_POST["newshort"]) and !empty($_POST["newshort"])) {
        $_POST["newshort"] = strtoupper($_POST["newshort"]);

        $tocheck = Station::by_id($_POST["newshort"]);
        if (isset($tocheck)) {
            // Bahnhofkürzel existiert schon
            header("Location: /moderation/overview?view=b");
            exit;
        }

        Station::create($_POST["newshort"], "Unbekannt");

        header("Location: /moderation/overview?view=b&id=" . $_POST["newshort"]);
        exit;
    }

    $stations = Station::get_stations();

    foreach ($stations as $station) {
        $xmlstation = $xml->addChild("station");
        $xmlstation->addChild("id", $station->short);
        $xmlstation->addChild("name", $station->name);
        $xmlstation->addChild("platforms", $station->platforms);
    }

    if (isset($_GET["id"])) {
        Station::refresh();
        $station = Station::by_id($_GET["id"]);

        if (!isset($station)) {
            $station = Station::by_name($_GET["id"]);
        }

        if (isset($station)) {
            if (isset($_POST["fullname"], $_POST["platforms"]) && is_numeric($_POST["platforms"])) {
                $station->name = $_POST["fullname"];
                $station->platforms = $_POST["platforms"];
                $station->save();

                header("Location: /moderation/overview?view=b&id=" . $_GET["id"]);
                exit;
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
                    $sql->sql_request("UPDATE Verbindungen SET Dauer=$duration WHERE BahnhofA='$station->short' AND BahnhofB='$connected'");
                    $sql->sql_request("UPDATE Verbindungen SET Dauer=$duration_rev WHERE BahnhofB='$station->short' AND BahnhofA='$connected'");
                }

                $count++;
            }

            if (isset($_POST["new-connection"]) && !empty($_POST["new-connection"])) {
                $new_connectionstation = Station::by_id($_POST["new-connection"]);
                $sql = new SQL(true);

                $send = false;

                if (!isset($new_connectionstation)) {
                    Station::create($_POST["new-connection"], "Unbekannt", 1000);
                    $new_connectionstation = Station::by_id($_POST["new-connection"]);
                    $send = true;
                }

                $sql->sql_request("INSERT INTO Verbindungen VALUES ('$station->short', '$new_connectionstation->short', 1)");
                $sql->sql_request("INSERT INTO Verbindungen VALUES ('$new_connectionstation->short', '$station->short', 1)");

                if ($send) {
                    header("Location: /moderation/overview?view=b&id=" . $_POST["new-connection"]);
                    exit;
                }
            }

            if (isset($_GET["delete"])) {
                $station->delete();
                header("Location: /moderation/overview?view=b");
                exit;
            }

            $xml->addChild("id", $_GET["id"]);
            $selection = $xml->addChild("selection");
            $selection->addChild("id", $station->short);
            $selection->addChild("fullname", $station->name);
            $selection->addChild("platforms", $station->platforms);

            $connections = $station->get_connections();

            foreach ($connections as $connection) {
                $connectionxml = $selection->addChild("connection");
                $connected_station = Station::by_id($connection->b);
                $connectionxml->addChild("other", $connected_station->name);
                $connectionxml->addChild("other_short", $connected_station->short);
                $connectionxml->addChild("duration", $connection->duration);
                $connectionxml->addChild("duration_rev", $connection->duration_rev);
            }
        }
    }
}
