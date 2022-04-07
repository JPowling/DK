<?php

$linie_node = $xml->addChild("linie");

$linie_node->addChild("id", $_GET['id']);

$sql = new SQL();

$sql_query ="
(SELECT L.LinienID, B.Name, R.VerbindungsIndex AS Haltestelle,

DATE_ADD(L.Startzeit, INTERVAL (SUM(TN.Standzeit) - TN2.Standzeit + SUM(TN.Dauer)) MINUTE) AS Ankunftszeit,

CASE
	WHEN R.VerbindungsIndex = 1
		THEN L.Startzeit
	ELSE 
		DATE_ADD(TN.Startzeit, INTERVAL (SUM(TN.Standzeit) + SUM(TN.Dauer)) MINUTE)
END AS Abfahrtszeit

FROM Linien AS L

INNER JOIN Routen AS R ON R.RoutenID = L.RoutenID
INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
INNER JOIN Bahnhofe AS B ON B.Kennzeichnung = R.BahnhofA

LEFT JOIN (
				SELECT R.VerbindungsIndex AS I, L.Startzeit AS Startzeit, 
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
				WHERE L.LinienID = :LinieA 
				ORDER BY I ASC 
				) AS TN ON TN.I  < R.VerbindungsIndex
				
LEFT JOIN (
				SELECT R.VerbindungsIndex AS I, L.Startzeit AS Startzeit,  
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
				WHERE L.LinienID = :LinieB 
				AND  ((R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID)) XOR R.Standzeit IS NOT NULL)
				) AS TN2 ON TN2.I  = R.VerbindungsIndex - 1
				
				
WHERE L.LinienID = :LinieC 
	AND  ((R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID) + 1) XOR TN2.Standzeit IS NOT NULL XOR R.VerbindungsIndex = 1)
GROUP BY R.VerbindungsIndex
ORDER BY R.VerbindungsIndex
)
UNION
(
SELECT L.LinienID, B.Name, R.VerbindungsIndex + 1 AS Haltestelle,
DATE_ADD(L.Startzeit, INTERVAL (SUM(TN.Standzeit) + SUM(TN.Dauer) + VA.Dauer) MINUTE) AS Ankunftszeit,
NULL AS Abfahrtszeit

FROM Linien AS L

INNER JOIN Routen AS R ON R.RoutenID = L.RoutenID
INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
INNER JOIN Bahnhofe AS B ON B.Kennzeichnung = R.BahnhofB

LEFT JOIN (
				SELECT R.VerbindungsIndex AS I, L.Startzeit AS Startzeit, 
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
				WHERE L.LinienID = :LinieD 
				) AS TN ON TN.I  < R.VerbindungsIndex
LEFT JOIN (
				SELECT R.VerbindungsIndex AS I, L.Startzeit AS Startzeit, 
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
				WHERE L.LinienID = :LinieE 
				) AS TN2 ON TN2.I  = R.VerbindungsIndex - 1
				
				
WHERE L.LinienID = :LinieF 
	AND R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID)
GROUP BY R.VerbindungsIndex
ORDER BY R.VerbindungsIndex
)
";

$sql_data = array(':LinieA' => $_GET['id'], ':LinieB' => $_GET['id'], ':LinieC' => $_GET['id'], ':LinieD' => $_GET['id'], ':LinieE' => $_GET['id'], ':LinieF' => $_GET['id']);

$result = $sql->request($sql_query, $sql_data);

if ($result->get_num_rows() > 1) {
    $array = $result->result;

    if (key_exists("LinienID", $array[0])) {
        foreach ($array as $key => $row) {
            if ($row["Ankunftszeit"] == null) {
                $row["Ankunftszeit"] = "--:--:--";
            }
            if ($row["Abfahrtszeit"] == null) {
                $row["Abfahrtszeit"] = "--:--:--";
            }

            $linie_nod = $xml->xpath("//xml/linien[LinienID=" . $row["LinienID"] . "]")[0]->addChild("haltestelle");
            $linie_nod->addChild("Nummer", $row["Haltestelle"]);
            $linie_nod->addChild("Bahnhof", $row["Name"]);
            $linie_nod->addChild("Ankunftszeit", $row["Ankunftszeit"]);
            $linie_nod->addChild("Abfahrtszeit", $row["Abfahrtszeit"]);
        }
    }
}
