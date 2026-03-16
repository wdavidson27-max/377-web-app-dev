<?php

/*************************************************************************************************
 * detail.php
 *
 * Displays the details for a single movie. This page expects to be included within index.php.
 *************************************************************************************************/

// Define a variable for each movie attribute being displayed
$title = "";
$genre = "";
$rating = "";
$mpaa = "";
$duration = "";
$releaseYear = "";

if (isset($id))
{
    $sql =<<<SQL
    SELECT *
    FROM movie
    WHERE mov_id = $id
    SQL;

    $connection = get_connection();

    // Run the query on the database
    $result = $connection->query($sql);

    // Store the ONE result in an associative array
    $row = $result->fetch_assoc();

    $id = $row["mov_id"];
    $title = $row["mov_title"];
    $genre = $row["mov_genre"];
    $rating = $row["mov_rating"];
    $mpaa = $row["mov_mpaa"];
    $duration = $row["mov_duration"];
    $releaseYear = $row["mov_release_year"];

}    




?>

<h2><?php echo isset($title) ? $title : "*** NEW MOVIE ***"; ?></h2>

<form action="save.php" method="POST">
    <input type="hidden" class="form-control" name="id" value="<?php echo $id; ?>">


    <div class="mb-3">
        <label for="title" class="form-label">Title</label>
        <input type="text" class="form-control" name="title" value="<?php echo $title; ?>">
    </div>

    <div class="mb-3">
        <label for="genre" class="form-label">Genre</label>
        <input type="text" class="form-control" name="genre" value="<?php echo $genre; ?>">
    </div>

    <div class="mb-3">
        <label for="rating" class="form-label">Rating</label>
        <input type="text" class="form-control" name="rating" value="<?php echo $rating; ?>">
    </div>

    <div class="mb-3">
        <label for="mpaa" class="form-label">MPAA</label>
        <input type="text" class="form-control" name="mpaa" value="<?php echo $mpaa; ?>">
    </div>

    <div class="mb-3">
        <label for="duration" class="form-label">Duration</label>
        <input type="text" class="form-control" name="duration" value="<?php echo $duration; ?>">
    </div>

    <div class="mb-3">
        <label for="release_year" class="form-label">Release Year</label>
        <input type="text" class="form-control" name="release_year" value="<?php echo $releaseYear; ?>">
    </div>

    <button type="submit" class="btn btn-primary">Save</button>
    <?php
    if ($id != "")
    {
        echo "<a href='delete.php?id=<?php echo $id; ?>" class='btn btn-danger' role='button'>Delete</a>";
    }
    
    <a href="index.php?content=list" class="btn btn-secondary" role="button">Cancel</a>

</form>