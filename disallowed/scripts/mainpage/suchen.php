<?php
// Jens

require_once 'disallowed/backend/jsonxmlparser.php';

$suche_node = $xml->addChild('suche');

if (isset($_GET['sucheBahnhofA'])){
    $suche_node->addChild('sucheBahnhofA', $_GET['sucheBahnhofA']);
}
if (isset($_GET['sucheBahnhofB'])){
    $suche_node->addChild('sucheBahnhofB', $_GET['sucheBahnhofB']);
}
if (isset($_GET['timeBahnhofA'])){
    $suche_node->addChild('timeBahnhofA', $_GET['timeBahnhofA']);
}
if (isset($_GET['timeBahnhofB'])){
    $suche_node->addChild('timeBahnhofB', $_GET['timeBahnhofB']);
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
'DEPARTING' AS StopType

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
'ARRIVING' AS StopType


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
'ARRIVING' AS StopType

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
$sA = $xml->xpath("//xml/suche/sucheBahnhofA")[0];
$tA = $xml->xpath("//xml/suche/timeBahnhofA")[0];
$sB = $xml->xpath("//xml/suche/sucheBahnhofB")[0];
$tB = $xml->xpath("//xml/suche/timeBahnhofB")[0];

$startStation = array(
	"LinienID" => -1,
	"Name" => "$sA",
	"Stoporder" => "0",
	"StopTime" => "$tA" . ":00",
	"StopType" => "ARRIVING",
	"NextStop" => "",
	"NextStopTime" => "",
);

$endStation = array(
	"LinienID" => -2,
	"Name" => "$sB",
	"Stoporder" => "0",
	"StopTime" => "$tB" . ":00",
	"StopType" => "DEPARTING",
	"NextStop" => "",
	"NextStopTime" => "",
);

array_push($result, $startStation, $endStation);


$json_string = json_encode($result);

$uuid = uniqid();
$file_path_php = "disallowed/external/datatransfer/";
$file_name = "php-" . $uuid . ".json";
file_put_contents($file_path_php . $file_name, $json_string);

shell_exec("java -jar disallowed/external/out/artifacts/searchAlgo_jar/searchAlgo.jar $file_path_php $file_name $uuid");

sleep(1);
$routs = file_get_contents($file_path_php . "kotlin-" . $uuid . ".json");
$json_routs = json_decode($routs, true);
print_r($json_routs);
$routes_node = $xml->addChild("routes", " ");

$route_node = $routes_node->addChild("route");
$start_node = $route_node->addChild("start");
$end_node = $route_node->addChild("end");

// JSONXMLParser::json_to_xml($json_routs, $routes_node);





// 			   $linie_nod = $xml->xpath("//xml/linien[LinienID=" . $row["LinienID"] . "]")[0]->addChild("haltestelle");
//             $linie_nod->addChild("Nummer", $row["Haltestelle"]);
//             $linie_nod->addChild("Bahnhof", $row["Name"]);
//             $linie_nod->addChild("Ankunftszeit", $row["Ankunftszeit"]);
//             $linie_nod->addChild("Abfahrtszeit", $row["Abfahrtszeit"]);

