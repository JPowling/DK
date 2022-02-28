<?php
# Paul
ensure_admin();

$sql = new SQL();

$result = $sql->sql_request("SELECT CONCAT(B.Vorname, ' ', B.Name) AS Name, BB.Bezeichnung, B.BenutzerID, B.Erstelldatum FROM Benutzer AS B "
    . "INNER JOIN Berechtigungen AS BB ON B.BerechtigungsID = BB.BerechtigungsID ORDER BY B.BerechtigungsID DESC, Name")->result;

    $info = $xml->addChild("user");

$info->addChild("name", "Name");
$info->addChild("rank", "Rang");
$info->addChild("creation_date", "Erstelldatum");

$xml->addChild("title", "Liste aller Benutzer | BD");

foreach ($result as $index => $row) {
    $user = $xml->addChild("user");

    $user->addChild("name", $row["Name"]);
    $user->addChild("rank", $row["Bezeichnung"]);
    $user->addChild("creation_date", $row["Erstelldatum"]);
    $user->addChild("userid", $row["BenutzerID"]);
}
