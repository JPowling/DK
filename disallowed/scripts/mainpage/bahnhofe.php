<?php
// Jens

$sql = new SQL(false);

$sql_query = "SELECT Name, Gleise FROM Bahnhofe ORDER BY Name";


$result = $sql->request($sql_query);

if ($result->get_num_rows() > 1) {
    $array = $result->result;

    if (key_exists("Name", $array[0])) {
        foreach ($array as $key => $row) {
            $bahnhofe_node = $xml->addChild("bahnhofe");
            $bahnhofe_node->addChild("Name", $row["Name"]);
            $bahnhofe_node->addChild("Gleise", $row["Gleise"]);
        }
    }
}
