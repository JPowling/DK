<?php
// Jens

$sql = new SQL(false);

$sql_string = "SELECT LinienID, Startzeit, ZuggattungsID FROM Linien";

$result = $sql->sql_request($sql_string);

if ($result->get_num_rows() > 1) {
    $array = $result->result;

    if (key_exists("LinienID", $array[0]) && key_exists("Startzeit", $array[0]) && key_exists("ZuggattungsID", $array[0])) {
        foreach ($array as $key => $row) {
            $linien_node = $xml->addChild("linien");
            $linien_node->addChild("LinienID", $row["LinienID"]);
            $linien_node->addChild("Startzeit", $row["Startzeit"]);
            $linien_node->addChild("ZuggattungsID", $row["ZuggattungsID"]);
        }
    }
}
