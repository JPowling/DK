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

$suche_node = $xml->addChild('suche');

if (isset($_GET['sucheBahnhofA'])) {
	$suche_node->addChild('sucheBahnhofA', $_GET['sucheBahnhofA']);
}
if (isset($_GET['sucheBahnhofB'])) {
	$suche_node->addChild('sucheBahnhofB', $_GET['sucheBahnhofB']);
}
if (isset($_GET['timeBahnhofA'])) {
	$suche_node->addChild('timeBahnhofA', $_GET['timeBahnhofA']);
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

if (isset($_GET['sucheBahnhofA']) && isset($_GET['sucheBahnhofB']) && isset($_GET['timeBahnhofA'])) {

	$sA = $xml->xpath("//xml/suche/sucheBahnhofA")[0];
	$tA = $xml->xpath("//xml/suche/timeBahnhofA")[0];
	$sB = $xml->xpath("//xml/suche/sucheBahnhofB")[0];
	$tB = "23:59";

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
	
	echo shell_exec("java -jar disallowed/external/out/artifacts/searchAlgo_jar/searchAlgo.jar $file_path_php $file_name $uuid");

	$routs = file_get_contents($file_path_php . "kotlin-" . $uuid . ".json");


	$json_routs = json_decode($routs, true);
	$routes_node = $xml->addChild("routes");


	$sql_string_trainType =
		"SELECT Z.ZuggattungsID, Z.Bezeichnung FROM Linien AS L
	 	 INNER JOIN Zuggattungen AS Z ON Z.ZuggattungsID = L.ZuggattungsID
	 	 WHERE LinienID = :Linie";

	$route_node = $routes_node->addChild("route");

	if ($json_routs) {
		foreach ($json_routs as $key => $node) {
			if ($node['data']['lineID'] < 0) continue;

			$node_node = $route_node->addChild("node");
			$node_node->addChild("station", $node['data']['trainStation']['name']);

			$node_node->addChild("time", $node['data']['time']);

			$line_info = $sql->request($sql_string_trainType, array('Linie' => $node['data']['lineID']))->result[0];
			$node_node->addChild("linie", ($line_info['ZuggattungsID'] . $node['data']['lineID']));

			$node_node->addChild("stoptype", $node['data']['stopType']);
		}
	}
	unlink($file_path_php . "php-" . $uuid . ".json");
	unlink($file_path_php . "kotlin-" . $uuid . ".json");
}

if (isset($_GET['reservieren'])) {
	if (!is_loggedin()) {
		header("Location: /notloggedin");
		exit;
	}

	$arriving_arr = array();
	$departing_arr = array();

	foreach ($xml->xpath("//xml/routes/route/node") as $key => $value) {
		if ($value->stoptype == "ARRIVING") {
			array_push($arriving_arr, $value);
		}
		if ($value->stoptype == "DEPARTING") {
			array_push($departing_arr, $value);
		}
	}

	$sql_str_arr = array();
	$sql_var_arr = array();

	$user_id = $sql->request("SELECT BenutzerID FROM Benutzer WHERE EMail = :email", array('email' => $_SESSION['email']))->result[0]['BenutzerID'];
	$date = $_GET['datum'];

	for ($i = 0; $i < sizeof($arriving_arr); $i++) {
		$line_id = preg_replace('/[^0-9]/', '', $arriving_arr[$i]->linie);
		$stationA_ID = $sql->request("SELECT Kennzeichnung FROM Bahnhofe WHERE Name = :name", array('name' => $departing_arr[$i]->station))->result[0]['Kennzeichnung'];
		$stationB_ID = $sql->request("SELECT Kennzeichnung FROM Bahnhofe WHERE Name = :name", array('name' => $arriving_arr[$i]->station))->result[0]['Kennzeichnung'];

		array_push(
			$sql_var_arr,
			array(
				'benutzerID' => $user_id,
				'linienID' => $line_id,
				'fahrtdatum' => $date,
				'startbahnhof' => $stationA_ID,
				'zielbahnhof' => $stationB_ID,
				'bestelldatum' => date('Y-m-d', time())
			)
		);
		array_push(
			$sql_str_arr,
			"INSERT INTO Reservierungen 
				VALUES (:benutzerID, :linienID, :fahrtdatum, :startbahnhof, :zielbahnhof, :bestelldatum);"
		);
	}

	$sql = new SQL(true);

	if ($sql->transaction($sql_str_arr, $sql_var_arr)) {
		header("Location: /?site=reservierungen&day=$date");
		exit;
	} else {
		header("Location: /somethingwrong");
		exit;
	}
}
