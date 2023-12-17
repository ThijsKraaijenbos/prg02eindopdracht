<?php
/** @var array[] $db */
require_once 'includes/connection.php';
session_start();

$id = $_GET['id'];
$getdata = "SELECT games.*, studios.studio
          FROM games
          INNER JOIN studios ON games.studio_id = studios.id
          WHERE games.ID = $id";
$formload = mysqli_query($db, $getdata);
$game = mysqli_fetch_assoc($formload);
if (!$game) {
    header("location: index.php");
    exit();
}


//Check if the form is submitted
if (isset($_POST['submit'])) {

    //Check what the values of all form fields are
    $title = mysqli_escape_string($db, $_POST['title']);
    $rating = mysqli_escape_string($db, $_POST['rating']);
    $genre = mysqli_escape_string($db, $_POST['genre']);
    $studio = mysqli_escape_string($db, $_POST['studio']);
    $multiplayer = mysqli_escape_string($db, $_POST['multiplayer']);

    //Server side validation
    require_once 'includes/albumerrors.php';

    //If no errors
    if (!empty($_POST['title']) && !empty($_POST['genre']) && !empty($_POST['rating']) && !empty($_POST['multiplayer']) && empty($errors)) {
        //Check value of the studio separately
        $getStudioQuery = "SELECT id FROM `studios` WHERE studio = '$studio'";
        $getStudioResult = mysqli_query($db, $getStudioQuery) or die('Error ' . mysqli_error($db) . ' with query ' . $getStudioQuery);
        //If studio exists
        if ($getStudioResult) {
            //Get studio's data and save ID of studio
            $fetchStudio = mysqli_fetch_assoc($getStudioResult);
            if ($fetchStudio) {
                $studioID = $fetchStudio['id'];
            }
        }

        //If studio Doesn't exist
        if ($fetchStudio == 0 || empty($fetchStudio)) {
            // Create new studio
            $newStudioQuery = "INSERT INTO `studios`(`studio`) VALUES ('$studio')";
            $newStudioResult = mysqli_query($db, $newStudioQuery) or die('Error ' . mysqli_error($db) . ' with query ' . $newStudioQuery);
            //If creating new studio is successful
            if ($newStudioResult) {
                //Save ID of studio
                $studioID = mysqli_insert_id($db);
                //Update database
                $query = "UPDATE `games` SET `title`='$title', `genre`='$genre', `rating`='$rating', `studio_id`='$studioID', `multiplayer`='$multiplayer' WHERE games.ID = $id";
                $result = mysqli_query($db, $query);
                // send back to index
                header("Location: index.php");
                exit();
            }
            //If studio does exist
        } else {
            // Update database
            $query = "UPDATE `games` SET `title`='$title', `genre`='$genre', `rating`='$rating', `studio_id`='$studioID', `multiplayer`='$multiplayer' WHERE games.ID = $id";
            $result = mysqli_query($db, $query);
            // send back to index
            header("Location: index.php");
            exit();
        }
    }
}
// If there are errors
else if (!empty($errors)) {
    $title = $_POST['title'];
    $genre = $_POST['genre'];
    $rating = $_POST['rating'];
    $studio = $_POST['studio'];
    $multiplayer = $_POST['multiplayer'];
} else {
    $title = $game['title'];
    $genre = $game['genre'];
    $rating = $game['rating'];
    $studio = $game['studio'];
    $multiplayer = $game['multiplayer'];
}

if (!isset($_SESSION['login']) || !$_SESSION['login']) {
    header("location: login.php");
    $_SESSION['create_redirect'] = false;
    $_SESSION['access_denied_warning'] = true;
    exit();
}


mysqli_close($db);
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.4/css/bulma.min.css">
    <title>Games List - Edit</title>
</head>
<body>
<div class="container px-4">
    <h1 class="title mt-4">Edit game</h1>

    <?php if (!empty($errors)): ?>
        <section class="content">
            <ul class="notification is-danger">
                <?php foreach ($errors as $error): ?>
                    <li><?= $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </section>
    <?php endif; ?>

    <section class="columns">
        <form class="column is-6" action="" method="post">
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="title">Title</label>
                </div>
                <div class="field-body">
                    <input class="input" id="title" type="text" name="title" placeholder="Title" value="<?= htmlentities($title)?>"/>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="genre">Genre(s)</label>
                </div>
                <div class="field-body">
                    <input class="input" id="genre" type="text" name="genre" placeholder="Genre" value="<?= htmlentities($genre)?>"/>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="rating">Rating</label>
                </div>
                <div class="field-body">
                    <input class="input" id="rating" type="number" name="rating" placeholder="Value between 1.0 - 10.0" step="0.1" value="<?= htmlentities($rating)?>"/>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="multiplayer">Studio</label>
                </div>
                <div class="field-body">
                    <input class="input" id="studio" type="text" name="studio" placeholder="Studio" value="<?= htmlentities($studio)?>"/>
                </div>
            </div>
            <div class="field is-horizontal">
                <div class="field-label is-normal">
                    <label class="label" for="multiplayer">Multiplayer</label>
                </div>
                <div class="field-body">
                    <input class="input" id="multiplayer" type="text" name="multiplayer" placeholder="Yes or No" value="<?= htmlentities($multiplayer)?>"/>
                </div>
            </div>

            <div class="field is-horizontal">
                <div class="field-label is-normal"></div>
                <div class="field-body">
                    <button class="button is-link is-fullwidth" type="submit" name="submit">Save</button>
                </div>
            </div>
        </form>
    </section>
    <a class="button mt-4" href="index.php">&laquo; Go back to the list</a>
</div>
</body>
</html>
