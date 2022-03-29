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

        header("Location: /moderation/overview?view=f&id=" . $_GET["id"]);
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

        header("Location: /moderation/overview?view=b&id=" . $_POST["newshort"]);
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

        if (!isset($station)) {
            $station = Station::by_name($_GET["id"]);
        }

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
                    Station::create($_POST["new-connection"], "Bitte sofort ändern", 1000);
                    $new_connectionstation = Station::by_id($_POST["new-connection"]);
                    $send = true;
                }

                $sql->sql_request("INSERT INTO Verbindungen VALUES ('$station->short', '$new_connectionstation->short', 1)");
                $sql->sql_request("INSERT INTO Verbindungen VALUES ('$new_connectionstation->short', '$station->short', 1)");

                if ($send) {
                    header("Location: /moderation/overview?view=b&id=" . $_POST["new-connection"]);
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
function load_r($xml) {
    $xml->addChild("title", "Routen bearbeiten | BD");

    $routes = Route::get_routes();

    foreach ($routes as $route_i) {
        $xmlroute = $xml->addChild("route");
        $xmlroute->addChild("id", $route_i->id);

        $startend = $route_i->get_start_finish();
        $xmlroute->addChild("start", $startend[0]);
        $xmlroute->addChild("end", $startend[1]);

    }

    if (isset($_POST["newfrom"])) {
        $id = Route::new_route($_POST["newfrom"], $_POST["newto"]);
        header("Location: /moderation/overview?view=r&id=$id");
    }

    if (isset($_GET["id"])) {
        $route = Route::by_id($_GET["id"]);

        if (!isset($route)) {
            return;
        }

        if (isset($_GET["delete"])) {
            $route->delete();
            header("Location: /moderation/overview?view=r");
            exit;
        }

        if (isset($_GET["reverse"])) {
            $rev = new Route(Route::next_free(), false);

            $data = $route->data;
            $data[sizeof($data) + 1] = "";

            $data = array_reverse($data);

            unset($data[0]);

            foreach ($data as $index => $values) {
                $a_store = $values["a"];
                $data[$index]["a"] = $values["b"];
                $data[$index]["b"] = $a_store;

                $data[$index]["stand_time"] = "1";
            }

            $rev->data = $data;

            $rev->save();
            header("Location: /moderation/overview?view=r&id=$rev->id");
            exit;
        }

        if (isset($_POST["save"])) {
            $route_new = Route::by_id($_GET["id"]);
            $route_new->data = array();
            $route_new->data[0] = ""; # VerbindungsIndex 0 isnt set but otherwise array_push wont work

            $con_before = Station::ensure_short($_POST["short-1"]);

            $work = true;
            $i = 2;

            while (isset($_POST["short-$i"])) {
                $con_name = Station::ensure_short($_POST["short-$i"]);
                $con = Connection::by_id($con_before, $con_name);

                if ($con == null) {
                    echo ("The connection $con_before and $con_name doesn't exist! Please fix.");
                    $work = false;
                    break;
                }

                if (isset($_POST["duration-$i"])) {
                    array_push($route_new->data, ["a" => $con_before, "b" => $con_name, "stand_time" => $_POST["duration-$i"]]);
                } else {
                    array_push($route_new->data, ["a" => $con_before, "b" => $con_name, "stand_time" => "NULL"]);
                }

                $con_before = $con_name;
                $i++;
            }

            if ($work) {
                unset($route_new->data[0]);
                $route = $route_new;
                $route->save();
                header("Location: /moderation/overview?view=r&id=$route->id");
                exit;
            }
        }

        if (isset($route)) {
            $xml->addChild("id", $_GET["id"]);

            $selection = $xml->addChild("selection");
            $xmldata = $selection->addChild("data");
            $xmldata->addChild("station", $route->data[1]["a"]);
            $xmldata->addChild("station_full", Station::by_id($route->data[1]["a"])->name);
            $xmldata->addChild("duration", 0);
            $xmldata->addChild("stand_time", "null");

            foreach ($route->data as $data) {
                $xmldata = $selection->addChild("data");
                $xmldata->addChild("station", $data["b"]);
                $xmldata->addChild("station_full", Station::by_id($data["b"])->name);

                $con = Connection::by_id($data["a"], $data["b"]);
                $xmldata->addChild("duration", $con->duration);

                if (isset($data["stand_time"])) {
                    $xmldata->addChild("stand_time", $data["stand_time"]);
                } else {
                    $xmldata->addChild("stand_time", "null");
                }
            }
        }
    }

    $stations = Station::get_stations();

    foreach ($stations as $station) {
        $xmlstation = $xml->addChild("station");
        $xmlstation->addChild("id", $station->short);
        $xmlstation->addChild("name", $station->name);
        $xmlstation->addChild("platforms", $station->platforms);
    }
}
function load_l($xml) {
    $xml->addChild("title", "Linien bearbeiten | BD");

    if (isset($_GET["create"])) {
        $id = Line::create_inc();

        header("Location: /moderation/overview?view=l&id=$id");
        exit;
    }

    if (isset($_GET["id"])) {
        $line = Line::by_id($_GET["id"]);

        if (!isset($line)) {
            return;
        }

        if (isset($_GET["delete"])) {
            $line->delete();
            header("Location: /moderation/overview?view=l");
            exit;
        }

        if (isset($_POST["route"])) {
            $line->routeid = $_POST["route"];
            $line->start_time = $_POST["start"];
            $line->category = $_POST["category"];
            $line->save();
            header("Location: /moderation/overview?view=l&id=$line->id");
            exit;
        }

        $xml->addChild("id", $_GET["id"]);

        $selection = $xml->addChild("selection");
        $selection->addChild("route", $line->routeid);
        $selection->addChild("start", $line->start_time);
        $selection->addChild("category", $line->category);
    }

    $lines = Line::get_lines();
    foreach ($lines as $line) {
        $xmlline = $xml->addChild("lines");
        $xmlline->addChild("id", $line->id);
    }

    $routes = Route::get_routes();
    foreach ($routes as $route) {
        $xmlroutes = $xml->addChild("routes");
        $xmlroutes->addChild("id", $route->id);
    }

    $categories = Train::get_categories();
    foreach ($categories as $category) {
        $xmlroutes = $xml->addChild("categories");
        $xmlroutes->addChild("name", $category);
    }
}
