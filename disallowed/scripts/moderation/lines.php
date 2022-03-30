<?php
# Paul


function load($xml) {
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

    
    Station::refresh();
    Connection::refresh();
    Route::refresh();
    
    $lines = Line::get_lines();

    foreach ($lines as $line) {
        $xmlline = $xml->addChild("lines");
        $xmlline->addChild("id", $line->id);
        $xmlline->addChild("route", $line->routeid);
        $xmlline->addChild("category", $line->category);

        $route = Route::by_id($line->routeid)->get_start_finish();

        $xmlline->addChild("start", Station::ensure_long($route[0]));
        $xmlline->addChild("finish", Station::ensure_long($route[1]));
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
