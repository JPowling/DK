<?php
// Jens

$suche_node = $xml->addChild('suche');

if (isset($_GET['sucheBahnhofA'])){
    $suche_node->addChild('sucheBahnhofA', $_GET['sucheBahnhofA']);
}
if (isset($_GET['sucheBahnhofB'])){
    $suche_node->addChild('sucheBahnhofB', $_GET['sucheBahnhofB']);
}