<?php
# Paul

function load($xml) {
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
        exit;
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

            $list = array();
            array_push($list, [$con_before => "NULL"]);

            $i = 2;

            while (isset($_POST["short-$i"])) {
                $con_name = Station::ensure_short($_POST["short-$i"]);

                if (isset($_POST["duration-$i"])) {
                    array_push($list, [$con_name => $_POST["duration-$i"]]);
                } else {
                    array_push($list, [$con_name => "NULL"]);
                }
                $i++;
            }

            $i = 0;
            while ($i < sizeof($list) - 1) {
                $station_a = array_key_first($list[$i]);
                $station_b = array_key_first($list[$i + 1]);

                $con = Connection::by_id($station_a, $station_b);

                if (!isset($con)) {
                    $sql = new SQL();
                    $result = $sql->request("SELECT BahnhofA, BahnhofB, Dauer FROM Verbindungen")->result;

                    $json_string = json_encode(array(array($station_a, $station_b), $result));

                    $uuid = uniqid();
                    $file_path_php = "disallowed/external/datatransfer/";
                    $file_name = "php-" . $uuid . ".json";

                    file_put_contents($file_path_php . $file_name, $json_string);

                    shell_exec("java -jar disallowed/external/out/artifacts/pathFinderAlgo_jar/pathFinderAlgo.jar $file_path_php $file_name $uuid");

                    $connections = file_get_contents($file_path_php . "kotlin-" . $uuid . ".json");

                    unlink($file_path_php . "php-" . $uuid . ".json");
                    unlink($file_path_php . "kotlin-" . $uuid . ".json");

                    $missing_connections = json_decode($connections, true);

                    $refined = array_slice($missing_connections, 1, sizeof($missing_connections) - 2);

                    foreach ($refined as $newCon) {
                        array_splice($list, ++$i, 0, [$i => [$newCon["data"] => "NULL"]]);
                    }
                }
                $i++;
            }

            $work = true;
            $i = 1;

            while ($i < sizeof($list)) {
                $con_name = Station::ensure_short(array_key_first($list[$i]));
                $con = Connection::by_id($con_before, $con_name);

                if ($con == null) {
                    echo ("The connection $con_before and $con_name doesn't exist! Please fix.");
                    $work = false;
                    break;
                }

                $duration = $list[$i][array_key_first($list[$i])];
                if (empty($duration)) {
                    $duration = "NULL";
                }
                array_push($route_new->data, ["a" => $con_before, "b" => $con_name, "stand_time" => $duration]);

                $con_before = $con_name;
                $i++;
            }
            unset($route_new->data[0]);

            if ($work) {
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
                    $xmldata->addChild("stand_time", "NULL");
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
