<?php


require_once "/disallowed/model/database/SQL.php";

$sql = new SQL(true);

$sql->sql_request("SELECT a.Name AS BahnhofA, b.Name, v.Dauer 
FROM Verbindungen as v 
Inner Join Bahnhofe as a on v.BahnhofA = a.Kennzeichnung
Inner Join Bahnhofe as b on v.BahnhofB = b.Kennzeichnung;");


