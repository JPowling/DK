<?php
# Paul

function load($xml) {
    $xml->addChild("title", "Routen bearbeiten | BD");

    Connection::refresh();
    Station::refresh();
    Route::refresh();

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
            $xmldata->addChild("station_full", Station::ensure_long($route->data[1]["a"]));
            $xmldata->addChild("duration", 0);
            $xmldata->addChild("stand_time", "null");

            foreach ($route->data as $data) {
                $xmldata = $selection->addChild("data");
                $xmldata->addChild("station", $data["b"]);
                $xmldata->addChild("station_full", Station::ensure_long($data["b"]));

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
