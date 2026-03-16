<?php


include("library.php");

$connection = get_connection();

$delete =<<<SQL
DELETE FROM nbastats
 WHERE stats_id = $id
SQL;


$connection->query($delete);
header('Location: index.php?content=list');