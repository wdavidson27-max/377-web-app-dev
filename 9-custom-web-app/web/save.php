<?php

include("library.php");

$connection = get_connection();

$playername = $connection->real_escape_string($playername);
$team = $connection->real_escape_string($team);
$points = $connection->real_escape_string($points);
$rebounds = $connection->real_escape_string($rebounds);
$assists = $connection->real_escape_string($assists);
$blocks = $connection->real_escape_string($blocks);
$steals = $connection->real_escape_string($steals);
$fieldgoal = $connection->real_escape_string($fieldgoal);
$threepoint = $connection->real_escape_string($threepoint);
$freethrow = $connection->real_escape_string($freethrow);

$sql = "";

if ($id == "")
{
    $sql =<<<SQL
    INSERT INTO nbastats (stats_playername, stats_team, stats_points, stats_rebounds, stats_assists, stats_blocks, stats_steals, stats_fieldgoal, stats_threepoint, stats_freethrow)
    VALUES('$playername', '$team', $points, $rebounds, $assists, $blocks, $steals, $fieldgoal, $threepoint, $freethrow)
    SQL;
}
else
{
    $sql =<<<SQL
    UPDATE nbastats
        SET stats_playername = '$playername',
            stats_team = '$team',
            stats_points = $points,
            stats_rebounds = $rebounds,
            stats_assists = $assists,
            stats_blocks = $blocks,
            stats_steals = $steals,
            stats_fieldgoal = $fieldgoal,
            stats_threepoint = $threepoint,
            stats_freethrow = $freethrow
    WHERE stats_id = $id
    SQL;
}


$connection->query($sql);

header('Location: index.php?content=list');