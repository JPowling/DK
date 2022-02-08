<?php


require_once "disallowed/model/database/SQL.php";
require_once "disallowed/model/database/result.php";


$sql = new SQL(true);

$result = $sql->sql_request("SELECT a.Name AS BahnhofA, b.Name AS BahnhofB, v.Dauer 
FROM Verbindungen as v 
Inner Join Bahnhofe as a on v.BahnhofA = a.Kennzeichnung
Inner Join Bahnhofe as b on v.BahnhofB = b.Kennzeichnung;");


SQL::get_column($result, "peter");
SQL::echo_all($result);
