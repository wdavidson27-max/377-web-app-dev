<?php

$playername = "";
$dob = "";
$team = "";
$points = "";
$rebounds = "";
$assists = "";
$blocks = "";
$steals = "";
$fieldgoal = "";
$threepoint = "";
$freethrow = "";


if (isset($id))
{
    $sql =<<<SQL
    SELECT *
    FROM nbastats
    WHERE stats_id = $id
    SQL;

    $connection = get_connection();

    // Run the query on the database
    $result = $connection->query($sql);

    // Store the ONE result in an associative array
    $row = $result->fetch_assoc();

    $id = $row["stats_id"];
    $playername = $row["stats_playername"];
    $dob = $row["stats_dob"];
    $team = $row["stats_team"];
    $points = $row["stats_points"];
    $rebounds = $row["stats_rebounds"];
    $assists = $row["stats_assists"];
    $blocks = $row["stats_blocks"];
    $steals = $row["stats_steals"];
    $fieldgoal = $row["stats_fieldgoal"];
    $threepoint = $row["stats_threepoint"];
    $freethrow = $row["stats_freethrow"];

} else {
    $id = "";
}

?>

<h2 id="header"><?php echo isset($playername) && $playername != "" ? $playername : "*** NEW PLAYER ***"; ?></h2>




<form action="save.php" method="POST">
    <input type="hidden" class="form-control" name="id" id="id" value="<?php echo $id; ?>">


    <div class="mb-3">
        <label for="playername" class="form-label">Player</label>
        <input type="text" class="form-control" name="playername" id="playername" value="<?php echo $playername; ?>">
    </div>


    <div class="mb-3">
        <label for="dob" class="form-label">Date of Birth</label>
        <input type="text" class="form-control" name="dob" id="dob" value="<?php echo $dob; ?>">
    </div>

    <div class="mb-3">
        <label for="team" class="form-label">Team</label>
        <input type="text" class="form-control" name="team" id="team" value="<?php echo $team; ?>">
    </div>

    <div class="mb-3">
        <label for="points" class="form-label">PPG</label>
        <input type="text" class="form-control" name="points" id="points" value="<?php echo $points; ?>">
    </div>

    <div class="mb-3">
        <label for="rebounds" class="form-label">RPG</label>
        <input type="text" class="form-control" name="rebounds" id="rebounds" value="<?php echo $rebounds; ?>">
    </div>

    <div class="mb-3">
        <label for="assists" class="form-label">APG</label>
        <input type="text" class="form-control" name="assists" id="assists" value="<?php echo $assists; ?>">
    </div>

    <div class="mb-3">
        <label for="blocks" class="form-label">BPG</label>
        <input type="text" class="form-control" name="blocks" id="blocks" value="<?php echo $blocks; ?>">
    </div>

    <div class="mb-3">
        <label for="steals" class="form-label">SPG</label>
        <input type="text" class="form-control" name="steals" id="steals" value="<?php echo $steals; ?>">
    </div>

    <div class="mb-3">
        <label for="fieldgoal" class="form-label">FG%</label>
        <input type="text" class="form-control" name="fieldgoal" id="fieldgoal" value="<?php echo $fieldgoal; ?>">
    </div>

    <div class="mb-3">
        <label for="threepoint" class="form-label">3PT%</label>
        <input type="text" class="form-control" name="threepoint" id="threepoint" value="<?php echo $threepoint; ?>">
    </div>

    <div class="mb-3">
        <label for="freethrow" class="form-label">FT%</label>
        <input type="text" class="form-control" name="freethrow" id="freethrow" value="<?php echo $freethrow; ?>">
    </div>

    <button type="button" class="btn btn-primary" onclick="save()">Save</button>
    
    <!-- Delete button made with copilot -->
    <button type="submit"formaction="delete.php" formmethod="POST" class="btn btn-danger"
            onclick="return confirm('Are you sure you want to delete this player?');">
        Delete
    </button>


    <a href="index.php?content=list" class="btn btn-secondary" role="button">Cancel</a>

</form>

<script>

function save() {
    var settings = {
        'async': true,
        'url': 'save.php' +
                '?id=' + $('#id').val() +
                '&playername=' + $('#playername').val() +
                '&dob=' + $('#dob').val() +
                '&team=' + $('#team').val() +
                '&points=' + $('#points').val() +
                '&rebounds=' + $('#rebounds').val() +
                '&assists=' + $('#assists').val() +
                '&blocks=' + $('#blocks').val() +
                '&steals=' + $('#steals').val() +
                '&fieldgoal=' + $('#fieldgoal').val() +
                '&threepoint=' + $('#threepoint').val() +
                '&freethrow=' + $('#freethrow').val(),
        'method': 'POST',
        'headers': {
            'Cache-Control': 'no-cache'
        }
    };

        
    $.ajax(settings).done(function(response) {
        // The response is the id for the newly created player
        console.log(response);
        if ($('#id').val() == "") {
            $('#id').val(response);
        }

        $('#header').html($('#playername').val());
        showAlert('success', 'Success!', 'Player saved successfully!');
    }).fail(function() {
        showAlert('danger', 'Error!', 'Error saving player.');
    });
}

</script>