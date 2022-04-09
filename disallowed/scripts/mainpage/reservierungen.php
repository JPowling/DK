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
WHERE R.BenutzerID = :user AND R.Fahrtdatum >= CURDATE()
GROUP BY R.Fahrtdatum
ORDER BY R.Fahrtdatum
";

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
SELECT L.LinienID, R.Bestelldatum, R.Fahrtdatum, BA.Name AS BahnhofA, BB.Name AS BahnhofB, SelLinienA.Abfahrtszeit AS AbfahrtszeitA, SelLinienB.Ankunftszeit AS AnkunftszeitB

FROM Reservierungen AS R

INNER JOIN Bahnhofe AS BA ON BA.Kennzeichnung = R.Startbahnhof
INNER JOIN Bahnhofe AS BB ON BB.Kennzeichnung = R.Zielbahnhof
INNER JOIN Linien AS L ON L.LinienID = R.LinienID



INNER JOIN ((SELECT Lin.LinienID, B.Kennzeichnung, R.VerbindungsIndex AS Haltestelle,

DATE_ADD(Lin.Startzeit, INTERVAL (SUM(TN.Standzeit) - TN2.Standzeit + SUM(TN.Dauer)) MINUTE) AS Ankunftszeit,

CASE
	WHEN R.VerbindungsIndex = 1
		THEN Lin.Startzeit
	ELSE 
		DATE_ADD(TN.Startzeit, INTERVAL (SUM(TN.Standzeit) + SUM(TN.Dauer)) MINUTE)
END AS Abfahrtszeit

FROM Linien AS Lin

INNER JOIN Routen AS R ON R.RoutenID = Lin.RoutenID
INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
INNER JOIN Bahnhofe AS B ON B.Kennzeichnung = R.BahnhofA

LEFT JOIN (
				SELECT R.VerbindungsIndex AS I, L.Startzeit AS Startzeit, L.LinienID, 
					CASE 
						WHEN R.Standzeit IS NULL 
							THEN 0
						ELSE
							R.Standzeit
					END AS Standzeit, 
					VA.Dauer 
				FROM Linien AS L
				INNER JOIN Routen AS R ON R.RoutenID = L.RoutenID
				INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
				ORDER BY I ASC 
				) AS TN ON TN.I  < R.VerbindungsIndex AND TN.LinienID = Lin.LinienID
				
LEFT JOIN (
				SELECT R.VerbindungsIndex AS I, L.Startzeit AS Startzeit, L.LinienID,
					CASE 
						WHEN R.Standzeit IS NULL 
							THEN 0
						ELSE
							R.Standzeit
					END AS Standzeit, 
					VA.Dauer 
				FROM Linien AS L
				INNER JOIN Routen AS R ON R.RoutenID = L.RoutenID
				INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
				WHERE (R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID)) XOR R.Standzeit IS NOT NULL
				) AS TN2 ON (TN2.I  = R.VerbindungsIndex - 1) AND TN2.LinienID = Lin.LinienID
				
				
WHERE ((R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID) + 1) XOR TN2.Standzeit IS NOT NULL XOR R.VerbindungsIndex = 1)
GROUP BY Lin.LinienID, R.VerbindungsIndex
ORDER BY R.VerbindungsIndex
)
UNION
(
SELECT Lin.LinienID, B.Kennzeichnung, R.VerbindungsIndex + 1 AS Haltestelle,
DATE_ADD(Lin.Startzeit, INTERVAL (SUM(TN.Standzeit) + SUM(TN.Dauer) + VA.Dauer) MINUTE) AS Ankunftszeit,
NULL AS Abfahrtszeit

FROM Linien AS Lin

INNER JOIN Routen AS R ON R.RoutenID = Lin.RoutenID
INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
INNER JOIN Bahnhofe AS B ON B.Kennzeichnung = R.BahnhofB

LEFT JOIN (
				SELECT R.VerbindungsIndex AS I, L.Startzeit AS Startzeit, L.LinienID, 
					CASE 
						WHEN R.Standzeit IS NULL 
							THEN 0
						ELSE
							R.Standzeit
					END AS Standzeit, 
					VA.Dauer 
				FROM Linien AS L
				INNER JOIN Routen AS R ON R.RoutenID = L.RoutenID
				INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
				) AS TN ON TN.I  < R.VerbindungsIndex AND TN.LinienID = Lin.LinienID
LEFT JOIN (
				SELECT R.VerbindungsIndex AS I, L.Startzeit AS Startzeit, L.LinienID, 
					CASE 
						WHEN R.Standzeit IS NULL 
							THEN 0
						ELSE
							R.Standzeit
					END AS Standzeit, 
					VA.Dauer 
				FROM Linien AS L
				INNER JOIN Routen AS R ON R.RoutenID = L.RoutenID
				INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
				) AS TN2 ON (TN2.I  = R.VerbindungsIndex - 1) AND TN2.LinienID = Lin.LinienID
				
				
WHERE R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID)
GROUP BY Lin.LinienID, R.VerbindungsIndex
ORDER BY R.VerbindungsIndex
)) AS SelLinienA ON SelLinienA.LinienID = L.LinienID AND SelLinienA.Kennzeichnung = BA.Kennzeichnung AND SelLinienA.Abfahrtszeit IS NOT NULL




INNER JOIN ((SELECT Lin.LinienID, B.Kennzeichnung, R.VerbindungsIndex AS Haltestelle,

DATE_ADD(Lin.Startzeit, INTERVAL (SUM(TN.Standzeit) - TN2.Standzeit + SUM(TN.Dauer)) MINUTE) AS Ankunftszeit,

CASE
	WHEN R.VerbindungsIndex = 1
		THEN Lin.Startzeit
	ELSE 
		DATE_ADD(TN.Startzeit, INTERVAL (SUM(TN.Standzeit) + SUM(TN.Dauer)) MINUTE)
END AS Abfahrtszeit

FROM Linien AS Lin

INNER JOIN Routen AS R ON R.RoutenID = Lin.RoutenID
INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
INNER JOIN Bahnhofe AS B ON B.Kennzeichnung = R.BahnhofA

LEFT JOIN (
				SELECT R.VerbindungsIndex AS I, L.Startzeit AS Startzeit, L.LinienID, 
					CASE 
						WHEN R.Standzeit IS NULL 
							THEN 0
						ELSE
							R.Standzeit
					END AS Standzeit, 
					VA.Dauer 
				FROM Linien AS L
				INNER JOIN Routen AS R ON R.RoutenID = L.RoutenID
				INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
				ORDER BY I ASC 
				) AS TN ON TN.I  < R.VerbindungsIndex AND TN.LinienID = Lin.LinienID
				
LEFT JOIN (
				SELECT R.VerbindungsIndex AS I, L.Startzeit AS Startzeit, L.LinienID,
					CASE 
						WHEN R.Standzeit IS NULL 
							THEN 0
						ELSE
							R.Standzeit
					END AS Standzeit, 
					VA.Dauer 
				FROM Linien AS L
				INNER JOIN Routen AS R ON R.RoutenID = L.RoutenID
				INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
				WHERE (R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID)) XOR R.Standzeit IS NOT NULL
				) AS TN2 ON (TN2.I  = R.VerbindungsIndex - 1) AND TN2.LinienID = Lin.LinienID
				
				
WHERE ((R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID) + 1) XOR TN2.Standzeit IS NOT NULL XOR R.VerbindungsIndex = 1)
GROUP BY Lin.LinienID, R.VerbindungsIndex
ORDER BY R.VerbindungsIndex
)
UNION
(
SELECT Lin.LinienID, B.Kennzeichnung, R.VerbindungsIndex + 1 AS Haltestelle,
DATE_ADD(Lin.Startzeit, INTERVAL (SUM(TN.Standzeit) + SUM(TN.Dauer) + VA.Dauer) MINUTE) AS Ankunftszeit,
NULL AS Abfahrtszeit

FROM Linien AS Lin

INNER JOIN Routen AS R ON R.RoutenID = Lin.RoutenID
INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
INNER JOIN Bahnhofe AS B ON B.Kennzeichnung = R.BahnhofB

LEFT JOIN (
				SELECT R.VerbindungsIndex AS I, L.Startzeit AS Startzeit, L.LinienID, 
					CASE 
						WHEN R.Standzeit IS NULL 
							THEN 0
						ELSE
							R.Standzeit
					END AS Standzeit, 
					VA.Dauer 
				FROM Linien AS L
				INNER JOIN Routen AS R ON R.RoutenID = L.RoutenID
				INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
				) AS TN ON TN.I  < R.VerbindungsIndex AND TN.LinienID = Lin.LinienID
LEFT JOIN (
				SELECT R.VerbindungsIndex AS I, L.Startzeit AS Startzeit, L.LinienID, 
					CASE 
						WHEN R.Standzeit IS NULL 
							THEN 0
						ELSE
							R.Standzeit
					END AS Standzeit, 
					VA.Dauer 
				FROM Linien AS L
				INNER JOIN Routen AS R ON R.RoutenID = L.RoutenID
				INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
				) AS TN2 ON (TN2.I  = R.VerbindungsIndex - 1) AND TN2.LinienID = Lin.LinienID
				
				
WHERE R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID)
GROUP BY Lin.LinienID, R.VerbindungsIndex
ORDER BY R.VerbindungsIndex
)) AS SelLinienB ON SelLinienB.LinienID = L.LinienID AND SelLinienB.Kennzeichnung = BB.Kennzeichnung AND SelLinienB.Ankunftszeit IS NOT NULL


WHERE R.BenutzerID = :user AND R.Fahrtdatum = :date
ORDER BY AbfahrtszeitA
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
        $detailed_node->addChild("lineID", $row['LinienID']);

        $detailed_node->addChild('orderdate', $row['Bestelldatum']);
        $detailed_node->addChild('traveldate', $row['Fahrtdatum']);
        $detailed_node->addChild('stationA', $row['BahnhofA']);
        $detailed_node->addChild('stationB', $row['BahnhofB']);
        $detailed_node->addChild('timeA', $row['AbfahrtszeitA']);
        $detailed_node->addChild('timeB', $row['AnkunftszeitB']);
    }

}
