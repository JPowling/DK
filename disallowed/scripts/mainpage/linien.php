<?php
// Jens


$sql = new SQL(false);

$sql_string = "SELECT LinienID, Startzeit, ZuggattungsID FROM Linien";


$sql_query = "SELECT L.LinienID, L.Startzeit, L.Fahrzeugnummer, Z.ZuggattungsID, BA.Name AS 'From', BB.Name AS 'TO' "
    . "FROM Linien AS L "
    . "INNER JOIN Routen AS R ON L.RoutenID = R.RoutenID "
    . "INNER JOIN (SELECT RoutenID, B.Name "
    . "FROM Routen AS R "
    . "INNER JOIN Bahnhofe AS B ON R.BahnhofA = B.Kennzeichnung "
    . "WHERE R.VerbindungsIndex = 1 "
    . ") AS BA ON R.RoutenID = BA.RoutenID "
    . "INNER JOIN (SELECT RoutenID, B.Name, R.VerbindungsIndex "
    . "FROM Routen AS R "
    . "INNER JOIN Bahnhofe AS B ON R.BahnhofB = B.Kennzeichnung "
    . "WHERE R.VerbindungsIndex = (Select max(VerbindungsIndex) FROM Routen WHERE R.RoutenID = RoutenID)"
    . ") AS BB ON R.RoutenID = BB.RoutenID "
    . "INNER JOIN Zuggattungen AS Z ON L.ZuggattungsID = Z.ZuggattungsID "
    . "GROUP BY L.LinienID "
    . "ORDER BY L.Startzeit, L.LinienID";


$result = $sql->sql_request($sql_query);

if ($result->get_num_rows() > 1) {
    $array = $result->result;

    if (key_exists("LinienID", $array[0])) {
        foreach ($array as $key => $row) {
            $linien_node = $xml->addChild("linien");
            $linien_node->addChild("LinienID", $row["LinienID"]);
            $linien_node->addChild("Zugnummer", $row["ZuggattungsID"] . $row["LinienID"]);
            $linien_node->addChild("Startzeit", $row["Startzeit"]);
            $linien_node->addChild("From", $row["From"]);
            $linien_node->addChild("TO", $row["TO"]);
        }
    }
}


if (isset($_GET['id'])) {
    require 'disallowed/scripts/mainpage/linie.php';
} else {
    $_GET['id'] = -1;
}
