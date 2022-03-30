<?php
// Paul
function load($xml) {
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
