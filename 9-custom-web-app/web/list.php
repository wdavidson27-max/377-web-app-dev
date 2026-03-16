<?php

?>

<h2>NBA Statistics<span id="player-count"></span></h2>

<a href='index.php?content=list'>All</a>

<?php


for ($i = 0; $i < 26; $i++)
{
    $letter = chr($i + ord("A"));
    echo "<a href='index.php?content=list&filter=$letter'>$letter</a> ";
}

?>
<a href="index.php?content=detail" class="btn btn-primary" role="button">Add</a>
</div>
<table class="table table-bordered table-hover">
    <thead class="thead-dark">
        <tr>
            <th><a href='index.php?content=list&sort=stats_playername'>Player Name</a></th>
            <th><a href='index.php?content=list&sort=stats_team'>Team</a></th>
            <th><a href='index.php?content=list&sort=stats_points'>PPG</a></th>
            <th><a href='index.php?content=list&sort=stats_rebounds'>RPG</a></th>
            <th><a href='index.php?content=list&sort=stats_assists'>APG</a></th>
            <th><a href='index.php?content=list&sort=stats_blocks'>BPG</a></th>
            <th><a href='index.php?content=list&sort=stats_steals'>SPG</a></th>
            <th><a href='index.php?content=list&sort=stats_fieldgoal'>FG%</a></th>
            <th><a href='index.php?content=list&sort=stats_threepoint'>3P%</a></th>
            <th><a href='index.php?content=list&sort=stats_freethrow'>FT%</a></th>
        </tr>
    </thead>
    <tbody> 

<?php

$connection = get_connection();

if (!isset($sort))
{
    $sort = 'stats_playername';
}

if ($sort == 'stats_playername' || $sort == 'stats_team') {
    $sql =<<<SQL
    SELECT *
    FROM nbastats
    ORDER BY $sort 
    SQL;
} else {
    $sql =<<<SQL
    SELECT *
    FROM nbastats
    ORDER BY $sort DESC
    SQL;
}

$playerCount = 0;
$result = $connection->query($sql);
while ($row = $result->fetch_assoc())
{
    echo "<tr>";
    echo "<td><a href='index.php?content=detail&id=". $row["stats_id"] . "'>" . $row["stats_playername"] . "</a></td>";
    echo "<td>" . $row["stats_team"] . "</td>";
    echo "<td>" . $row["stats_points"] . "</td>";
    echo "<td>" . $row["stats_rebounds"] . "</td>";
    echo "<td>" . $row["stats_assists"] . "</td>";
    echo "<td>" . $row["stats_blocks"] . "</td>";
    echo "<td>" . $row["stats_steals"] . "</td>";
    echo "<td>" . $row["stats_fieldgoal"] . "</td>";
    echo "<td>" . $row["stats_threepoint"] . "</td>";
    echo "<td>" . $row["stats_freethrow"] . "</td>";
    echo "</tr>";

    $playerCount++;
}

?>

    </tbody>
</table>

<?php

$code =<<<JS
<script>
document.getElementById('player-count').innerHTML = '(' + $playerCount + ' records)';
</script>
JS;

echo $code;

?>