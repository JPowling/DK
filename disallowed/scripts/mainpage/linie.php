<?php

$linie_node = $xml->addChild("linie");

$linie_node->addChild("id", $_GET['id']);

// $linie_node->addChild("id", $_GET['id'] + 1);
echo "linie " . $_GET['id'];
