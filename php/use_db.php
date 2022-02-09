<?php

$sql = 'SELECT a.Name AS BahnhofA, b.Name, v.Dauer 
FROM Verbindungen as v 
Inner Join Bahnhofe as a on v.BahnhofA = a.Kennzeichnung
Inner Join Bahnhofe as b on v.BahnhofA = b.Kennzeichnung';

$cmd = $pdo->prepare($sql);
$cmd->execute();

$row = $cmd->fetch();

do {
    foreach ($row as $value) {
        echo $value . " ";
    }
    echo "<br/>";
    $row = $cmd->fetch();
} while ($row);




