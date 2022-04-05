<?php
// Jens

$suche_node = $xml->addChild('suche');

if (isset($_GET['sucheBahnhofA'])){
    $suche_node->addChild('sucheBahnhofA', $_GET['sucheBahnhofA']);
}
if (isset($_GET['sucheBahnhofB'])){
    $suche_node->addChild('sucheBahnhofB', $_GET['sucheBahnhofB']);
}




$sql_string = "
-- Departing:
(
SELECT L.LinienID, B.Name, R.VerbindungsIndex * 2 - 1 AS Stoporder,

CASE
	WHEN R.VerbindungsIndex = 1
		THEN L.Startzeit
	ELSE 
		DATE_ADD(TN.Startzeit, INTERVAL (SUM(TN.Standzeit) + SUM(TN.Dauer)) MINUTE)
END AS StopTime,
'DEPARTING' AS StopType,

BB.Name AS NextStop,
CASE
	WHEN R.VerbindungsIndex = 1
		THEN DATE_ADD(L.Startzeit, INTERVAL VA.Dauer MINUTE)
	ELSE 
		DATE_ADD(TN.Startzeit, INTERVAL (SUM(TN.Standzeit) + SUM(TN.Dauer) + VA.Dauer) MINUTE)
END AS NextStopTime

FROM Linien AS L

INNER JOIN Routen AS R ON R.RoutenID = L.RoutenID
INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
INNER JOIN Bahnhofe AS B ON B.Kennzeichnung = R.BahnhofA
INNER JOIN Bahnhofe AS BB ON BB.Kennzeichnung = R.BahnhofB

LEFT JOIN (
				SELECT L.LinienID, R.VerbindungsIndex AS I, L.Startzeit AS Startzeit, 
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
				) AS TN ON TN.I  < R.VerbindungsIndex AND L.LinienID = TN.LinienID
				
LEFT JOIN (
				SELECT L.LinienID, R.VerbindungsIndex AS I, L.Startzeit AS Startzeit,  
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
				WHERE ((R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID)) XOR R.Standzeit IS NOT NULL)
				) AS TN2 ON TN2.I  = R.VerbindungsIndex - 1 AND L.LinienID = TN2.LinienID
				
				
WHERE ((R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID) + 1) XOR TN2.Standzeit IS NOT NULL XOR R.VerbindungsIndex = 1)
GROUP BY LinienID, R.VerbindungsIndex
ORDER BY R.VerbindungsIndex
)

UNION

-- Arriving:

(SELECT L.LinienID, B.Name, R.VerbindungsIndex * 2 - 2 AS Stoporder,

DATE_ADD(L.Startzeit, INTERVAL (SUM(TN.Standzeit) - TN2.Standzeit + SUM(TN.Dauer)) MINUTE) AS StopTime,
'ARRIVING' AS StopType,
NULL AS NextStop,
NULL AS NextStopTime


FROM Linien AS L

INNER JOIN Routen AS R ON R.RoutenID = L.RoutenID
INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
INNER JOIN Bahnhofe AS B ON B.Kennzeichnung = R.BahnhofA

LEFT JOIN (
				SELECT L.LinienID, R.VerbindungsIndex AS I, L.Startzeit AS Startzeit, 
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
				) AS TN ON TN.I  < R.VerbindungsIndex AND L.LinienID = TN.LinienID
				
LEFT JOIN (
				SELECT L.LinienID, R.VerbindungsIndex AS I, L.Startzeit AS Startzeit,  
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
				WHERE ((R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID)) XOR R.Standzeit IS NOT NULL)
				) AS TN2 ON TN2.I  = R.VerbindungsIndex - 1 AND L.LinienID = TN2.LinienID
				
				
WHERE ((R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID) + 1) XOR TN2.Standzeit IS NOT NULL XOR R.VerbindungsIndex < 1)
GROUP BY LinienID, R.VerbindungsIndex
ORDER BY R.VerbindungsIndex
)
UNION
(
SELECT L.LinienID, B.Name, (R.VerbindungsIndex + 1) * 2 - 2 AS Stoporder,
DATE_ADD(L.Startzeit, INTERVAL (SUM(TN.Standzeit) + SUM(TN.Dauer) + VA.Dauer) MINUTE) AS Ankunftszeit,
'ARRIVING' AS StopType,
NULL AS NextStop,
NULL AS NextStopTime

FROM Linien AS L

INNER JOIN Routen AS R ON R.RoutenID = L.RoutenID
INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
INNER JOIN Bahnhofe AS B ON B.Kennzeichnung = R.BahnhofB

LEFT JOIN (
				SELECT L.LinienID, R.VerbindungsIndex AS I, L.Startzeit AS Startzeit, 
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
				) AS TN ON TN.I  < R.VerbindungsIndex AND L.LinienID = TN.LinienID
LEFT JOIN (
				SELECT L.LinienID, R.VerbindungsIndex AS I, L.Startzeit AS Startzeit, 
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
				) AS TN2 ON TN2.I  = R.VerbindungsIndex - 1 AND L.LinienID = TN2.LinienID
				
				
WHERE R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID)
GROUP BY LinienID, R.VerbindungsIndex
ORDER BY R.VerbindungsIndex
)

ORDER BY LinienID, StopTime, Stoporder
";


$sql = new SQL();

$result = $sql->sql_request($sql_string)->result;

$startStation = array(
	"LinienID" => -1,
	"Name" => "Bruchsal",
	"Stoporder" => "0",
	"StopTime" => "05:00:00",
	"StopType" => "ARRIVING",
	"NextStop" => "",
	"NextStopTime" => "",
);

$endStation = array(
	"LinienID" => -2,
	"Name" => "Gondelsheim",
	"Stoporder" => "0",
	"StopTime" => "10:00:00",
	"StopType" => "DEPARTING",
	"NextStop" => "",
	"NextStopTime" => "",
);

array_push($result, $startStation, $endStation);

print_r($result);

$json_string = json_encode($result);

// print_r($json_string);
// echo strlen($json_string);
// echo shell_exec("java -jar disallowed/external/out/artifacts/searchAlgo_jar/searchAlgo.jar $json_string");


$uuid = uniqid();
$file_path = "disallowed/external/datatransfer/";
$file_name = "php-" . $uuid . ".json";

// echo "from php: $file_name;";

file_put_contents($file_path . $file_name, $json_string);

shell_exec("java -jar disallowed/external/out/artifacts/searchAlgo_jar/searchAlgo.jar $file_path $file_name $uuid");

sleep(1);

// echo file_get_contents($file_path . "kotlin-" . $uuid . ".json");

