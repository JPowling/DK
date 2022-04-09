<?php
// Jens

if (!is_loggedin()) {
    header("Location: /notloggedin");
    exit;
}

$sql = new SQL();

$user_id = $sql->request("SELECT BenutzerID FROM Benutzer WHERE EMail = :email", array('email' => $_SESSION['email']))->result[0]['BenutzerID'];
$sql_str = "
SELECT Fahrtdatum, COUNT(*) AS 'anzahl'
FROM Reservierungen AS R
WHERE R.BenutzerID = :user
GROUP BY R.Fahrtdatum";

$sql_var = array('user' => $user_id);


$result = $sql->request($sql_str, $sql_var)->result;

$res_node = $xml->addChild('reservierungen');

foreach ($result as $row) {
    $day_node = $res_node->addChild('day');
    $day_node->addChild('date', $row['Fahrtdatum']);
    $day_node->addChild('sum', $row['anzahl']);
}



$sql_str_details =
"
SELECT LinienID, R.Bestelldatum, R.Fahrtdatum,  BA.Name AS BahnhofA, BB.Name AS BahnhofB,
	(SELECT 	 CASE 
						WHEN (SELECT VerbindungsIndex FROM Routen WHERE BahnhofA = R.Startbahnhof AND RoutenID = Ro.RoutenID) = 1
							THEN Li.Startzeit
						ELSE 
							DATE_ADD(Li.Startzeit, INTERVAL SUM(Ve.Dauer) + SUM(Ro.Standzeit) MINUTE)
							
					END 
				AS 'ArrivalTime'
		FROM Linien AS Li
		INNER JOIN Routen AS Ro ON Ro.RoutenID = Li.RoutenID
		INNER JOIN Verbindungen AS Ve ON Ve.BahnhofA = Ro.BahnhofA AND Ve.BahnhofB = Ro.BahnhofB
		WHERE Li.LinienID = R.LinienID AND Ro.VerbindungsIndex <= (SELECT VerbindungsIndex FROM Routen WHERE BahnhofA = R.Startbahnhof AND RoutenID = Ro.RoutenID)
	) AS 'Abfahrtszeit',

	(SELECT  DATE_ADD(Lin.Startzeit, INTERVAL (
					SUM(V.Dauer) 
					+ SUM(R1.Standzeit) 
					- (	SELECT Dauer 
							FROM Routen AS Ro
							INNER JOIN Verbindungen AS Ve ON Ve.BahnhofA = Ro.BahnhofA AND Ve.BahnhofB = Ro.BahnhofB
							WHERE Ro.RoutenID = R1.RoutenID AND Ro.BahnhofA = R.Zielbahnhof
						)
					) MINUTE
				) AS 'ArrivalTime'
		
		FROM Linien AS Lin
		INNER JOIN Routen AS R1 ON R1.RoutenID = Lin.RoutenID
		INNER JOIN Verbindungen AS V ON V.BahnhofA = R1.BahnhofA AND V.BahnhofB = R1.BahnhofB
		
		WHERE Lin.LinienID = R.LinienID AND R1.VerbindungsIndex <= (SELECT VerbindungsIndex FROM Routen WHERE BahnhofA = R.Zielbahnhof AND RoutenID = R1.RoutenID)
	) AS 'Ankunftszeit'



FROM Reservierungen AS R

INNER JOIN Bahnhofe AS BA ON BA.Kennzeichnung = R.Startbahnhof
INNER JOIN Bahnhofe AS BB ON BB.Kennzeichnung = R.Zielbahnhof

WHERE R.BenutzerID = :user AND R.Fahrtdatum = :date
";

if (isset($_GET['day'])) {
    $sql_var_details = array(
        'user' => $user_id,
        'date' => $_GET['day']
    );


    $result = $sql->request($sql_str_details, $sql_var_details)->result;

    $sql_string_trainType =
		"SELECT Z.ZuggattungsID, Z.Bezeichnung FROM Linien AS L
	 	 INNER JOIN Zuggattungen AS Z ON Z.ZuggattungsID = L.ZuggattungsID
	 	 WHERE LinienID = :Linie";

    $detailes_node = $res_node->addChild('detailes');

    foreach ($result as $row) {
        $detailed_node = $detailes_node->addChild('detailed');
        $line_info = $sql->request($sql_string_trainType, array('Linie' => $row['LinienID']))->result[0];
        $detailed_node->addChild("line", ($line_info['ZuggattungsID'] . $row['LinienID']));

        $detailed_node->addChild('orderdate', $row['Bestelldatum']);
        $detailed_node->addChild('traveldate', $row['Fahrtdatum']);
        $detailed_node->addChild('stationA', $row['BahnhofA']);
        $detailed_node->addChild('stationB', $row['BahnhofB']);
        $detailed_node->addChild('timeA', $row['Abfahrtszeit']);
        $detailed_node->addChild('timeB', $row['Ankunftszeit']);
    }

}
