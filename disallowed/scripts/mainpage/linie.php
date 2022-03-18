<?php

$linie_node = $xml->addChild("linie");

$linie_node->addChild("id", $_GET['id']);

$sql = new SQL();

$sql_query =
    "(SELECT L.LinienID, B.Name AS Bahnhof, R.VerbindungsIndex AS Nummer, 
    DATE_ADD(L.Startzeit, INTERVAL (SUM(TN.Standzeit) + SUM(TN.Dauer)) MINUTE) AS Ankunftszeit,

    CASE
        WHEN R.VerbindungsIndex = 1
            THEN DATE_ADD(L.Startzeit, INTERVAL R.Standzeit MINUTE)
        WHEN R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID) + 1
                OR DATE_ADD(TN.Startzeit, INTERVAL (SUM(TN.Standzeit) + SUM(TN.Dauer) + R.Standzeit) MINUTE) IS NULL
            THEN NULL
        ELSE 
            DATE_ADD(TN.Startzeit, INTERVAL (SUM(TN.Standzeit) + SUM(TN.Dauer) + R.Standzeit) MINUTE)
    END AS Abfahrtszeit

FROM Linien AS L

INNER JOIN Routen AS R ON R.RoutenID = L.RoutenID
INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
INNER JOIN Bahnhofe AS B ON B.Kennzeichnung = R.BahnhofA

LEFT JOIN (
            SELECT R.VerbindungsIndex AS I, L.Startzeit AS Startzeit, R.Standzeit, VA.Dauer FROM Linien AS L
            INNER JOIN Routen AS R ON R.RoutenID = L.RoutenID
            INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
            WHERE L.LinienID = :Liniea 
                AND  ((R.Standzeit IS NULL AND R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID)) XOR R.Standzeit IS NOT NULL)
            ) AS TN ON TN.I  < R.VerbindungsIndex
				
				
WHERE L.LinienID = :Linieb 
	AND  ((R.Standzeit IS NULL AND R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID) + 1) XOR R.Standzeit IS NOT NULL)
GROUP BY R.VerbindungsIndex
ORDER BY R.VerbindungsIndex
)
UNION
(
SELECT L.LinienID, B.Name AS Bahnhof, R.VerbindungsIndex + 1 AS Nummer, 
DATE_ADD(L.Startzeit, INTERVAL (SUM(TN.Standzeit) + SUM(TN.Dauer)) MINUTE) AS Ankunftszeit,
NULL AS Abfahrtszeit

FROM Linien AS L

INNER JOIN Routen AS R ON R.RoutenID = L.RoutenID
INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
INNER JOIN Bahnhofe AS B ON B.Kennzeichnung = R.BahnhofB

LEFT JOIN (
            SELECT R.VerbindungsIndex AS I, L.Startzeit AS Startzeit, R.Standzeit, VA.Dauer FROM Linien AS L
            INNER JOIN Routen AS R ON R.RoutenID = L.RoutenID
            INNER JOIN Verbindungen AS VA ON VA.BahnhofA = R.BahnhofA AND VA.BahnhofB = R.BahnhofB
            WHERE L.LinienID = :Liniec 
                AND  ((R.Standzeit IS NULL AND R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID)) XOR R.Standzeit IS NOT NULL)
            ) AS TN ON TN.I  < R.VerbindungsIndex
				
				
WHERE L.LinienID = :Linied 
	AND  ((R.Standzeit IS NULL AND R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID)) XOR R.Standzeit IS NOT NULL)
	AND R.VerbindungsIndex = (SELECT MAX(VerbindungsIndex) FROM Routen AS R2 WHERE R2.RoutenID = R.RoutenID)
	GROUP BY R.VerbindungsIndex
	ORDER BY R.VerbindungsIndex
)";

$sql_data = array(':Liniea' => $_GET['id'], ':Linieb' => $_GET['id'], ':Liniec' => $_GET['id'], ':Linied' => $_GET['id']);

$result = $sql->request($sql_query, $sql_data);

if ($result->get_num_rows() > 1) {
    $array = $result->result;

    if (key_exists("LinienID", $array[0])) {
        foreach ($array as $key => $row) {
            if ($row["Ankunftszeit"] == null) {
                $row["Ankunftszeit"] = "--";
            }
            if ($row["Abfahrtszeit"] == null) {
                $row["Abfahrtszeit"] = "--";
            }

            $linie_nod = $xml->xpath("//xml/linien[LinienID=" . $row["LinienID"] . "]")[0]->addChild("haltestelle");
            $linie_nod->addChild("Nummer", $row["Nummer"]);
            $linie_nod->addChild("Bahnhof", $row["Bahnhof"]);
            $linie_nod->addChild("Ankunftszeit", $row["Ankunftszeit"]);
            $linie_nod->addChild("Abfahrtszeit", $row["Abfahrtszeit"]);
        }
    }
}
