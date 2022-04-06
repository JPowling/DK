<?php
// Jens

$suche_node = $xml->addChild('suche');

if (isset($_GET['sucheBahnhofA'])){
    $suche_node->addChild('sucheBahnhofA', $_GET['sucheBahnhofA']);
}
if (isset($_GET['sucheBahnhofB'])){
    $suche_node->addChild('sucheBahnhofB', $_GET['sucheBahnhofB']);
}
echo "shell exec: ";
echo shell_exec("java -jar disallowed/external/out/artifacts/searchAlgo_jar/searchAlgo.jar");
echo "after shell exec";
$json = json_encode("");